<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $verificationLink;

    /**
     * Create a new message instance.
     */
    public function __construct($verificationLink)
    {
        $this->verificationLink = $verificationLink;
    }

    public function build()
    {
        return $this->view('auth.email-verification-send')
        ->with(['verificationLink' => $this->verificationLink])
            ->subject('Email Verrification');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'mail.email-verification',
            with: [
                'verificationLink' => $this->verificationLink,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
