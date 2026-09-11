<?php

namespace App\Mail;

use App\BugReport;
use App\BugReportItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminBugReportReplyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public BugReport $bugReport;
    public BugReportItem $item;

    /**
     * Create a new message instance.
     */
    public function __construct(BugReport $bugReport, BugReportItem $item)
    {
        $this->bugReport = $bugReport;
        $this->item = $item;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreplay@auditors.lv', 'Auditors.lv'),
            subject: "[Auditors.lv] Atbilde uz Jūsu pieteikumu #{$this->bugReport->id}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-bug-report-reply',
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
