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

class NewBugReportMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public BugReport $bugReport;
    public BugReportItem $item;
    public bool $isNewThread;

    /**
     * Create a new message instance.
     */
    public function __construct(BugReport $bugReport, BugReportItem $item, bool $isNewThread = false)
    {
        $this->bugReport = $bugReport;
        $this->item = $item;
        $this->isNewThread = $isNewThread;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $author = $this->item->user ? $this->item->user->name : ($this->bugReport->user ? $this->bugReport->user->name : ($this->bugReport->email ?? 'Viesis'));
        $prefix = $this->isNewThread ? 'Jauns pieteikums' : 'Jauna klienta ziņa';

        return new Envelope(
            from: new Address('noreplay@auditors.lv', 'Auditors.lv'),
            subject: "[Auditors.lv] {$prefix} #{$this->bugReport->id} no {$author}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-bug-report-message',
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
