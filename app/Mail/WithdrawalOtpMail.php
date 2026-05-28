<?php

namespace App\Mail;

use App\Models\WithdrawalOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawalOtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public WithdrawalOtp $otp,
        public string $userName,
        public string $amount
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Penarikan Dana - Autopahala',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.withdrawal-otp',
        );
    }
}
