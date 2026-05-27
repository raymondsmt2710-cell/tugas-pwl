<?php

namespace App\Services;

use App\Models\Donation;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create a Snap transaction and return the token + redirect URL.
     */
    public function createSnapTransaction(Donation $donation): array
    {
        $campaign = $donation->campaign;

        $params = [
            'transaction_details' => [
                'order_id' => $donation->order_id,
                'gross_amount' => (int) $donation->donation_amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email,
            ],
            'item_details' => [
                [
                    'id' => 'CAMPAIGN-' . $campaign->id_campaign,
                    'price' => (int) $donation->donation_amount,
                    'quantity' => 1,
                    'name' => substr($campaign->title, 0, 50),
                ],
            ],
            'callbacks' => [
                'finish' => url('/donations/' . $donation->order_id . '/finish'),
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
            'token' => $snapToken,
            'redirect_url' => $this->getSnapRedirectUrl($snapToken),
            'client_key' => config('midtrans.client_key'),
        ];
    }

    /**
     * Get transaction status from Midtrans API.
     * Used for synchronization of stale/pending transactions.
     */
    public function getTransactionStatus(string $orderId): ?array
    {
        try {
            $status = Transaction::status($orderId);
            return (array) $status;
        } catch (\Exception $e) {
            // Transaction not found or API error
            return null;
        }
    }

    /**
     * Cancel a pending transaction on Midtrans.
     */
    public function cancelTransaction(string $orderId): bool
    {
        try {
            Transaction::cancel($orderId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify the signature of a Midtrans notification.
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return $expectedSignature === $signatureKey;
    }

    /**
     * Get the Snap.js script URL based on environment.
     */
    public function getSnapJsUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Get the client key for frontend usage.
     */
    public function getClientKey(): string
    {
        return config('midtrans.client_key');
    }

    /**
     * Get the Snap redirect URL based on environment.
     */
    public function getSnapRedirectUrl(string $token): string
    {
        $baseUrl = config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v2/vtweb/'
            : 'https://app.sandbox.midtrans.com/snap/v2/vtweb/';

        return $baseUrl . $token;
    }

    /**
     * Map Midtrans transaction_status to internal payment_status.
     */
    public function mapStatus(string $transactionStatus, ?string $fraudStatus = null): string
    {
        return match ($transactionStatus) {
            'capture' => ($fraudStatus === 'accept' || !$fraudStatus) ? 'paid' : 'pending',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel' => 'failed',
            'expire' => 'expired',
            default => 'pending',
        };
    }
}
