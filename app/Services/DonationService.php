<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DonationService
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    /**
     * Create a new donation and initiate Midtrans Snap payment.
     *
     * @return array{donation: Donation, snap_token: string, redirect_url: string, client_key: string}
     */
    public function createDonation(Campaign $campaign, array $data, ?User $user = null): array
    {
        return DB::transaction(function () use ($campaign, $data, $user) {
            $orderId = Donation::generateOrderId();

            $donation = Donation::create([
                'id_campaign' => $campaign->id_campaign,
                'id_user' => $user?->id_user,
                'donor_name' => $data['donor_name'],
                'donor_email' => $data['donor_email'],
                'donor_message' => $data['donor_message'] ?? null,
                'is_anonymous' => $data['is_anonymous'] ?? false,
                'donation_amount' => $data['donation_amount'],
                'payment_status' => 'pending',
                'order_id' => $orderId,
            ]);

            // Create Midtrans Snap transaction
            $snap = $this->midtransService->createSnapTransaction($donation);

            // Store the payment token
            $donation->update(['payment_token' => $snap['token']]);

            Log::info("Donation created [{$orderId}]", [
                'campaign' => $campaign->id_campaign,
                'amount' => $donation->donation_amount,
            ]);

            return [
                'donation' => $donation,
                'snap_token' => $snap['token'],
                'redirect_url' => $snap['redirect_url'],
                'client_key' => $snap['client_key'],
            ];
        });
    }

    /**
     * Handle payment notification from Midtrans webhook.
     * Processes: settlement, capture, pending, deny, cancel, expire.
     */
    public function handlePaymentNotification(array $payload): Donation
    {
        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        $donation = Donation::where('order_id', $orderId)->firstOrFail();

        // Idempotency: prevent re-processing finalized donations
        if (in_array($donation->payment_status, ['paid', 'failed', 'expired'])) {
            Log::info("Donation [{$orderId}] already finalized as [{$donation->payment_status}], skipping.");
            return $donation;
        }

        return DB::transaction(function () use ($donation, $transactionStatus, $fraudStatus, $paymentType, $payload) {
            $newStatus = $this->midtransService->mapStatus($transactionStatus, $fraudStatus);

            $updateData = [
                'payment_status' => $newStatus,
                'payment_method' => $paymentType,
            ];

            if ($newStatus === 'paid') {
                $updateData['paid_at'] = now();
            }

            $donation->update($updateData);

            // Update campaign balance on successful payment
            if ($newStatus === 'paid') {
                $this->creditCampaignBalance($donation);
            }

            Log::info("Donation [{$donation->order_id}] webhook processed", [
                'midtrans_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'new_status' => $newStatus,
                'payment_type' => $paymentType,
            ]);

            return $donation->fresh();
        });
    }

    /**
     * Synchronize a single donation's status with Midtrans API.
     * Used when webhook was missed or for manual verification.
     */
    public function syncTransactionStatus(Donation $donation): Donation
    {
        if (!$donation->order_id) {
            return $donation;
        }

        $status = $this->midtransService->getTransactionStatus($donation->order_id);

        if (!$status) {
            Log::warning("Could not fetch Midtrans status for [{$donation->order_id}]");
            return $donation;
        }

        $transactionStatus = $status['transaction_status'] ?? 'pending';
        $fraudStatus = $status['fraud_status'] ?? null;
        $paymentType = $status['payment_type'] ?? null;

        $newStatus = $this->midtransService->mapStatus($transactionStatus, $fraudStatus);

        // Only update if status actually changed
        if ($newStatus !== $donation->payment_status) {
            return DB::transaction(function () use ($donation, $newStatus, $paymentType) {
                $updateData = [
                    'payment_status' => $newStatus,
                    'payment_method' => $paymentType,
                ];

                if ($newStatus === 'paid' && !$donation->paid_at) {
                    $updateData['paid_at'] = now();
                }

                $donation->update($updateData);

                if ($newStatus === 'paid') {
                    $this->creditCampaignBalance($donation);
                }

                Log::info("Donation [{$donation->order_id}] synced to [{$newStatus}]");

                return $donation->fresh();
            });
        }

        return $donation;
    }

    /**
     * Synchronize all pending donations older than given minutes.
     * Called by scheduled command to catch missed webhooks.
     */
    public function syncStalePendingDonations(int $olderThanMinutes = 30): array
    {
        $staleDonations = Donation::where('payment_status', 'pending')
            ->where('created_at', '<', now()->subMinutes($olderThanMinutes))
            ->get();

        $results = ['synced' => 0, 'paid' => 0, 'expired' => 0, 'failed' => 0];

        foreach ($staleDonations as $donation) {
            $before = $donation->payment_status;
            $updated = $this->syncTransactionStatus($donation);

            if ($updated->payment_status !== $before) {
                $results['synced']++;
                match ($updated->payment_status) {
                    'paid' => $results['paid']++,
                    'expired' => $results['expired']++,
                    'failed' => $results['failed']++,
                    default => null,
                };
            }
        }

        return $results;
    }

    /**
     * Expire donations that have been pending for over 24 hours
     * and Midtrans has no record of them.
     */
    public function expireStaleDonations(): int
    {
        $count = Donation::where('payment_status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['payment_status' => 'expired']);

        if ($count > 0) {
            Log::info("Expired {$count} stale pending donations.");
        }

        return $count;
    }

    /**
     * Get donation history for a user.
     */
    public function getUserDonations(User $user, int $perPage = 10)
    {
        return Donation::byUser($user->id_user)
            ->with('campaign')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get successful donations for a specific campaign.
     */
    public function getCampaignDonations(Campaign $campaign, int $perPage = 10)
    {
        return Donation::forCampaign($campaign->id_campaign)
            ->successful()
            ->latest()
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Credit the campaign balance after a successful donation.
     */
    private function creditCampaignBalance(Donation $donation): void
    {
        $campaign = $donation->campaign;

        $campaign->increment('collected_amount', $donation->donation_amount);

        // Recalculate available balance
        $campaign->refresh();
        $campaign->update([
            'available_balance' => $campaign->collected_amount - $campaign->withdrawal_amount,
        ]);

        // Notify campaign owner
        $campaign->user->notify(new \App\Notifications\DonationReceived($donation));

        Log::info("Campaign [{$campaign->id_campaign}] balance credited +{$donation->donation_amount}");
    }
}
