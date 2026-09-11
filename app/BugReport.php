<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\BugReportItem;
use App\BugReportStatus;
use App\Enums\BugReportStatus as BugReportStatusEnum;

class BugReport extends Model
{
    protected $table = 'bug_reports';

    protected $fillable = [
        'user_id',
        'email',
        'section',
        'url',
        'status',
        'last_read_at',
    ];

    protected $casts = [
        'status' => BugReportStatusEnum::class,
        'last_read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(BugReportItem::class, 'bug_report_id')->orderBy('created_at', 'asc');
    }

    public function statuses()
    {
        return $this->hasMany(BugReportStatus::class, 'bug_report_id')->orderBy('created_at', 'asc');
    }

    public function latestStatusRecord()
    {
        return $this->hasOne(BugReportStatus::class, 'bug_report_id')->latestOfMany();
    }

    public function initialMessage()
    {
        return $this->hasOne(BugReportItem::class, 'bug_report_id')->oldestOfMany();
    }

    public function latestMessage()
    {
        return $this->hasOne(BugReportItem::class, 'bug_report_id')->latestOfMany();
    }

    /**
     * Change report status and record history
     */
    public function changeStatus(BugReportStatusEnum|string $status, ?int $userId = null, ?string $comment = null): BugReportStatus
    {
        $statusEnum = is_string($status) ? (BugReportStatusEnum::tryFrom($status) ?? BugReportStatusEnum::NEW) : $status;

        $record = $this->statuses()->create([
            'status' => $statusEnum->value,
            'user_id' => $userId,
            'comment' => $comment,
        ]);

        $this->status = $statusEnum;
        $this->save();

        return $record;
    }

    /**
     * Mark report as read by the client
     */
    public function markAsRead(): void
    {
        $this->last_read_at = now();
        $this->save();
    }

    /**
     * Check if report has unread admin replies for the client
     */
    public function isUnreadForClient(?int $userId = null): bool
    {
        $latestItem = $this->items->last() ?? $this->latestMessage;
        if (!$latestItem) {
            return false;
        }

        // If the latest message is an admin reply
        if ($latestItem->is_admin_reply) {
            if (!$this->last_read_at) {
                return true;
            }
            return $this->last_read_at < $latestItem->created_at;
        }

        return false;
    }

    /**
     * Get count of unread reports with unread admin replies for a specific user
     */
    public static function unreadCountForUser(?int $userId = null): int
    {
        $uid = $userId ?? \Illuminate\Support\Facades\Auth::id();
        if (!$uid) {
            return 0;
        }

        return static::where('user_id', $uid)
            ->whereHas('items', function ($q) {
                $q->where('is_admin_reply', true);
            })
            ->where(function ($q) {
                $q->whereNull('last_read_at')
                  ->orWhereRaw('last_read_at < (select max(created_at) from bug_report_items where bug_report_items.bug_report_id = bug_reports.id and bug_report_items.is_admin_reply = 1)');
            })
            ->count();
    }

    /**
     * Get string value of status
     */
    public function getStatusValueAttribute(): string
    {
        if ($this->status instanceof BugReportStatusEnum) {
            return $this->status->value;
        }

        return (string) ($this->status ?? 'new');
    }

    /**
     * Get enum representation of status
     */
    public function getStatusEnumAttribute(): BugReportStatusEnum
    {
        if ($this->status instanceof BugReportStatusEnum) {
            return $this->status;
        }

        return BugReportStatusEnum::tryFrom((string) $this->status) ?? BugReportStatusEnum::NEW;
    }

    /**
     * Asynchronously notify administrator at 7924@inbox.lv about a new client message
     */
    public function notifyAdmin(BugReportItem $item, bool $isNewThread = false): void
    {
        try {
            \Illuminate\Support\Facades\Mail::to('7924@inbox.lv')
                ->queue(new \App\Mail\NewBugReportMessageMail($this, $item, $isNewThread));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Kļūda nosūtot paziņojumu administratoram uz 7924@inbox.lv: ' . $e->getMessage(), [
                'report_id' => $this->id,
                'item_id' => $item->id,
            ]);
        }
    }

    /**
     * Asynchronously notify client via email about an admin reply
     */
    public function notifyClient(BugReportItem $item): void
    {
        $recipientEmail = $this->user ? $this->user->email : $this->email;
        if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            \Illuminate\Support\Facades\Mail::to($recipientEmail)
                ->queue(new \App\Mail\AdminBugReportReplyMail($this, $item));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Kļūda nosūtot paziņojumu klientam uz ' . $recipientEmail . ': ' . $e->getMessage(), [
                'report_id' => $this->id,
                'item_id' => $item->id,
            ]);
        }
    }
}
