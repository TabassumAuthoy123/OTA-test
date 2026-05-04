<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepartureReminderPassenger extends Mailable
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
            subject: 'Flight Reminder: Your flight departs in ~10 hours — ' . ($this->data['booking_no'] ?? ''),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.departureReminderPassenger',
            with: ['d' => $this->data],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
