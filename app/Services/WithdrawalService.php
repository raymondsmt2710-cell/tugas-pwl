<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class WithdrawalService
{
    /**
     * Create a withdrawal request.
     */
    public function requestWithdrawal(Campaign $campaign, User $user, array $data): Withdrawal
    {
        // Validate campaign ownership
        if ($campaign->id_user !== $user->id_user) {
            throw ValidationException::withMessages([
                'campaign' => 'Anda bukan pemilik kampanye ini.',
            ]);
        }

        // Validate available balance
        $amount = $data['amount'];
        $pendingWithdrawals = Withdrawal::forCampaign($campaign->id_campaign)
            ->whereIn('status', ['pending', 'under_review', 'approved'])
            ->sum('amount');

        $availableForWithdrawal = $campaign->available_balance - $pendingWithdrawals;

        if ($amount > $availableForWithdrawal) {
            throw ValidationException::withMessages([
                'amount' => "Saldo tersedia untuk penarikan: Rp " . number_format($availableForWithdrawal, 0, ',', '.'),
            ]);
        }

        if ($amount < 50000) {
            throw ValidationException::withMessages([
                'amount' => 'Minimum penarikan adalah Rp 50.000.',
            ]);
        }

        return DB::transaction(function () use ($campaign, $user, $data) {
            $withdrawal = Withdrawal::create([
                'id_campaign' => $campaign->id_campaign,
                'id_user' => $user->id_user,
                'amount' => $data['amount'],
                'bank_name' => $data['bank_name'],
                'account_number' => $data['account_number'],
                'account_holder' => $data['account_holder'],
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
            ]);

            Log::info("Withdrawal requested [{$withdrawal->id_withdrawal}]", [
                'campaign' => $campaign->id_campaign,
                'amount' => $data['amount'],
                'user' => $user->id_user,
            ]);

            return $withdrawal;
        });
    }

    /**
     * Mark withdrawal as under review (admin action).
     */
    public function markUnderReview(Withdrawal $withdrawal): Withdrawal
    {
        $withdrawal->update([
            'status' => 'under_review',
            'reviewed_at' => now(),
        ]);

        $withdrawal->user->notify(new \App\Notifications\WithdrawalStatusChanged($withdrawal, 'under_review'));

        return $withdrawal->fresh();
    }

    /**
     * Approve a withdrawal (admin action).
     */
    public function approve(Withdrawal $withdrawal, ?string $adminNotes = null): Withdrawal
    {
        $withdrawal->update([
            'status' => 'approved',
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
        ]);

        $withdrawal->user->notify(new \App\Notifications\WithdrawalStatusChanged($withdrawal, 'approved'));

        return $withdrawal->fresh();
    }

    /**
     * Reject a withdrawal (admin action).
     */
    public function reject(Withdrawal $withdrawal, ?string $adminNotes = null): Withdrawal
    {
        $withdrawal->update([
            'status' => 'rejected',
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
        ]);

        $withdrawal->user->notify(new \App\Notifications\WithdrawalStatusChanged($withdrawal, 'rejected'));

        return $withdrawal->fresh();
    }

    /**
     * Mark withdrawal as paid and deduct from campaign balance (admin action).
     */
    public function markAsPaid(Withdrawal $withdrawal): Withdrawal
    {
        return DB::transaction(function () use ($withdrawal) {
            $withdrawal->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Deduct from campaign balance
            $campaign = $withdrawal->campaign;
            $campaign->increment('withdrawal_amount', $withdrawal->amount);
            $campaign->refresh();
            $campaign->update([
                'available_balance' => $campaign->collected_amount - $campaign->withdrawal_amount,
            ]);

            $withdrawal->user->notify(new \App\Notifications\WithdrawalStatusChanged($withdrawal, 'paid'));

            Log::info("Withdrawal [{$withdrawal->id_withdrawal}] paid, campaign balance updated", [
                'campaign' => $campaign->id_campaign,
                'amount' => $withdrawal->amount,
                'new_balance' => $campaign->available_balance,
            ]);

            return $withdrawal->fresh();
        });
    }

    /**
     * Cancel a withdrawal (user action, only if pending/under_review).
     */
    public function cancel(Withdrawal $withdrawal, User $user): Withdrawal
    {
        if ($withdrawal->id_user !== $user->id_user) {
            throw ValidationException::withMessages([
                'withdrawal' => 'Anda tidak memiliki akses ke penarikan ini.',
            ]);
        }

        if (!$withdrawal->canBeCancelled()) {
            throw ValidationException::withMessages([
                'withdrawal' => 'Penarikan ini tidak dapat dibatalkan.',
            ]);
        }

        $withdrawal->update(['status' => 'rejected', 'admin_notes' => 'Dibatalkan oleh pengguna.']);

        return $withdrawal->fresh();
    }

    /**
     * Get withdrawal history for a user.
     */
    public function getUserWithdrawals(User $user, int $perPage = 10)
    {
        return Withdrawal::byUser($user->id_user)
            ->with('campaign')
            ->latest()
            ->paginate($perPage);
    }
}
