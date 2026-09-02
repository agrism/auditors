<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BackupCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $backupDetails;

    /**
     * Create a new message instance.
     */
    public function __construct(array $backupDetails)
    {
        $this->backupDetails = $backupDetails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $status = $this->backupDetails['success'] ?? false ? '✓ Veiksmīgi' : '✗ Kļūda';
        $appName = $this->backupDetails['subject_name'] ?? 'auditors.lv';
        $date = date('Y-m-d H:i');

        return new Envelope(
            subject: "[{$appName}] Datubāzes rezerves kopija ({$status}) - {$date}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.backup-completed',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
