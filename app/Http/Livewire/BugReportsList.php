<?php

namespace App\Http\Livewire;

use App\BugReport;
use App\BugReportItem;
use App\Enums\BugReportStatus;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BugReportsList extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $selectedReportId = null;
    public $statusFilter = 'all';
    public $search = '';
    public $replyMessage = '';
    public $replyAttachments = [];
    public $successFlash = '';

    protected $listeners = [
        'refreshBugReports' => '$refresh',
        'bugReportCreated' => 'handleNewBugReportCreated',
        'openBugReportDetail' => 'openReport',
        'resetBugReportsList' => 'closeReportDetail',
        'closeDetail' => 'closeReportDetail',
    ];

    public function handleNewBugReportCreated()
    {
        $this->resetPage();
        $this->selectedReportId = null;
        $this->successFlash = 'Jauns paziņojums veiksmīgi pievienots!';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openReport($id)
    {
        $report = BugReport::where('user_id', Auth::id())->where('id', $id)->first();
        if ($report) {
            $report->markAsRead();
            $this->emit('unreadBugReportsUpdated');
            $this->selectedReportId = $report->id;
            $this->replyMessage = '';
            $this->replyAttachments = [];
            $this->successFlash = '';
            $this->resetErrorBag();
            $this->resetValidation();
        }
    }

    public function closeReportDetail()
    {
        $this->selectedReportId = null;
        $this->replyMessage = '';
        $this->replyAttachments = [];
        $this->successFlash = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function sendReply()
    {
        $this->validate([
            'replyMessage' => 'required|string|min:2|max:5000',
            'replyAttachments.*' => 'nullable|file|max:15360|mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt,csv,zip',
        ], [
            'replyMessage.required' => 'Lūdzu, ievadiet atbildes tekstu.',
            'replyMessage.min' => 'Atbildes tekstam jābūt vismaz 2 simbolus garam.',
            'replyAttachments.*.max' => 'Fails nedrīkst pārsniegt 15 MB.',
            'replyAttachments.*.mimes' => 'Neatbalstīts faila formāts.',
        ]);

        $report = BugReport::where('user_id', Auth::id())->where('id', $this->selectedReportId)->firstOrFail();
        $user = Auth::user();
        $isAdmin = $user && method_exists($user, 'isAdmin') ? $user->isAdmin() : false;

        $storedAttachments = [];
        if (!empty($this->replyAttachments)) {
            foreach ($this->replyAttachments as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('bug_reports', 'public');
                    $storedAttachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'url' => Storage::disk('public')->url($path),
                        'size' => $file->getSize(),
                        'mime' => $file->getClientMimeType(),
                    ];
                }
            }
        }

        $item = $report->items()->create([
            'user_id' => $user ? $user->id : null,
            'message' => $this->replyMessage,
            'attachments' => !empty($storedAttachments) ? $storedAttachments : null,
            'is_admin_reply' => false,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        if ($report->status === BugReportStatus::ANSWERED || $report->status === BugReportStatus::CLOSED || $report->status_value === 'answered' || $report->status_value === 'closed') {
            $report->changeStatus(BugReportStatus::IN_PROGRESS, $user ? $user->id : null, 'Atbilde no klienta');
        }

        $report->markAsRead();
        $this->emit('unreadBugReportsUpdated');

        // Notify administrator via async email
        $report->notifyAdmin($item, false);

        $this->replyMessage = '';
        $this->replyAttachments = [];
        $this->successFlash = 'Atbilde veiksmīgi nosūtīta!';
    }

    public function closeTicket($id)
    {
        $report = BugReport::where('user_id', Auth::id())->where('id', $id)->first();
        if ($report) {
            $report->changeStatus(BugReportStatus::CLOSED, Auth::id(), 'Pieteikumu slēdza klients');
            $this->successFlash = 'Pieteikums atzīmēts kā slēgts.';
        }
    }

    public function reopenTicket($id)
    {
        $report = BugReport::where('user_id', Auth::id())->where('id', $id)->first();
        if ($report) {
            $report->changeStatus(BugReportStatus::IN_PROGRESS, Auth::id(), 'Pieteikumu atkārtoti atvēra klients');
            $this->successFlash = 'Pieteikums atvērts atkārtoti.';
        }
    }

    public function render()
    {
        $userId = Auth::id();

        $selectedReport = null;
        if ($this->selectedReportId) {
            $selectedReport = BugReport::where('user_id', $userId)
                ->where('id', $this->selectedReportId)
                ->with(['items.user', 'statuses.user'])
                ->first();
        }

        $query = BugReport::where('user_id', $userId)->with(['items', 'statuses']);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty(trim($this->search))) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('section', 'like', $s)
                  ->orWhere('id', 'like', $s)
                  ->orWhereHas('items', function ($iq) use ($s) {
                      $iq->where('message', 'like', $s);
                  });
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(10);

        $counts = [
            'all' => BugReport::where('user_id', $userId)->count(),
            'new' => BugReport::where('user_id', $userId)->where('status', BugReportStatus::NEW->value)->count(),
            'in_progress' => BugReport::where('user_id', $userId)->where('status', BugReportStatus::IN_PROGRESS->value)->count(),
            'answered' => BugReport::where('user_id', $userId)->where('status', BugReportStatus::ANSWERED->value)->count(),
            'closed' => BugReport::where('user_id', $userId)->where('status', BugReportStatus::CLOSED->value)->count(),
        ];

        return view('livewire.bug-reports-list', [
            'reports' => $reports,
            'selectedReport' => $selectedReport,
            'counts' => $counts,
        ]);
    }
}
