<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderDownloadMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  string  $recipientName  The buyer the links are addressed to.
     * @param  string  $reference  The order the plugins were bought on.
     * @param  list<array{name: string, url: string}>  $downloads  Every plugin on the order the buyer can download, with its link.
     */
    public function __construct(
        public string $recipientName,
        public string $reference,
        public array $downloads,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your download links for order {$this->reference}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.downloads',
        );
    }
}
