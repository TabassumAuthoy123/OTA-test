<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RechargeStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope(): Envelope
    {
        $status = $this->mailData['status'] ?? 'Update';
        return new Envelope(
            subject: "Recharge Request {$status}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.rechargeStatus',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
