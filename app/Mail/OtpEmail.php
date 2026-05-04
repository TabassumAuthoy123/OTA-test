<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $name,
        public readonly string $otp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'FaithTrip — Your Password Reset OTP');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.otpEmail', with: [
            'name' => $this->name,
            'otp'  => $this->otp,
        ]);
    }
}
