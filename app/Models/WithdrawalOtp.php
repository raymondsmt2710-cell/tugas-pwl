<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalOtp extends Model
{
    protected $fillable = [
        'user_id',
        'withdrawal_id',
        'otp_code',
        'expires_at',
        'is_used',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function withdrawal(): BelongsTo
    {
        return $this->belongsTo(Withdrawal::class, 'withdrawal_id', 'id_withdrawal');
    }

    public function isValid(): bool
    {
        return !$this->is_used
            && $this->expires_at->isFuture()
            && $this->attempts < 5;
    }

    public static function generate(int $userId, int $withdrawalId): self
    {
        // Invalidate previous OTPs for this withdrawal
        static::where('user_id', $userId)
            ->where('withdrawal_id', $withdrawalId)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        return static::create([
            'user_id' => $userId,
            'withdrawal_id' => $withdrawalId,
            'otp_code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(10),
        ]);
    }
}
