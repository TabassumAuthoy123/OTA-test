<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketIssuedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ticket Issued - ' . ($this->mailData['booking_no'] ?? ''),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.ticketIssued',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
