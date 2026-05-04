<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepartureReminderAgent extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Upcoming Flight Alert: ' . ($this->data['booking_no'] ?? '') . ' departs in ~10 hours',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.departureReminderAgent',
            with: ['d' => $this->data],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
