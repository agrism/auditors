@extends('admin.layout.admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1">
                <i class="fa-solid fa-headset text-primary me-2"></i>Klientu saziņas un kļūdu ziņojumi
            </h1>
            <p class="text-slate-500 mb-0 small">Pārskatiet visus lietotāju iesniegtos kļūdu ziņojumus, jautājumus un sniedziet oficiālas administratora atbildes.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.bug-reports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-rotate me-1"></i> Atsvaidzināt
            </a>
        </div>
    </div>

    <!-- Summary Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('admin.bug-reports.index', array_merge(request()->except('page'), ['status' => 'all'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white @if($statusFilter === 'all') border-start border-4 border-primary @endif">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-comments fs-5"></i>
                        </div>
                        <div>
                            <div class="text-slate-500 small fw-medium">Kopā ziņojumu</div>
                            <div class="fs-4 fw-bold text-slate-900">{{ number_format($counts['all'], 0, '.', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('admin.bug-reports.index', array_merge(request()->except('page'), ['status' => 'new'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white @if($statusFilter === 'new') border-start border-4 border-danger @endif">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-danger-subtle text-danger p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-bell fs-5"></i>
                        </div>
                        <div>
                            <div class="text-slate-500 small fw-medium">Jauni</div>
                            <div class="fs-4 fw-bold text-danger">{{ number_format($counts['new'], 0, '.', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('admin.bug-reports.index', array_merge(request()->except('page'), ['status' => 'in_progress'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white @if($statusFilter === 'in_progress') border-start border-4 border-warning @endif">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning-subtle text-warning p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-spinner fs-5"></i>
                        </div>
                        <div>
                            <div class="text-slate-500 small fw-medium">Izskatīšanā</div>
                            <div class="fs-4 fw-bold text-slate-900">{{ number_format($counts['in_progress'], 0, '.', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('admin.bug-reports.index', array_merge(request()->except('page'), ['status' => 'answered'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white @if($statusFilter === 'answered') border-start border-4 border-success @endif">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-reply-all fs-5"></i>
                        </div>
                        <div>
                            <div class="text-slate-500 small fw-medium">Atbildēti</div>
                            <div class="fs-4 fw-bold text-slate-900">{{ number_format($counts['answered'], 0, '.', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ route('admin.bug-reports.index', array_merge(request()->except('page'), ['status' => 'closed'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-white @if($statusFilter === 'closed') border-start border-4 border-secondary @endif">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-secondary-subtle text-secondary p-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-check fs-5"></i>
                        </div>
                        <div>
                            <div class="text-slate-500 small fw-medium">Slēgti</div>
                            <div class="fs-4 fw-bold text-slate-900">{{ number_format($counts['closed'], 0, '.', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.bug-reports.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Statuss</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>-- Visi statusi --</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Jauns ({{ $counts['new'] }})</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Izskatīšanā ({{ $counts['in_progress'] }})</option>
                        <option value="answered" {{ request('status') === 'answered' ? 'selected' : '' }}>Atbildēts ({{ $counts['answered'] }})</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Slēgts ({{ $counts['closed'] }})</option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Lietotājs</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">-- Visi lietotāji --</option>
                        <option value="guest" {{ request('user_id') === 'guest' ? 'selected' : '' }}>Viesis / Neautorizēts</option>
                        <option value="registered" {{ request('user_id') === 'registered' ? 'selected' : '' }}>Reģistrētie lietotāji</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Meklēt tekstā / URL / ID</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Meklēt ziņās, sadaļā...">
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Datums no - līdz</label>
                    <div class="input-group input-group-sm">
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="col-md-2 col-sm-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filtrēt
                    </button>
                    @if(request()->anyFilled(['status', 'user_id', 'search', 'from_date', 'to_date']))
                        <a href="{{ route('admin.bug-reports.index') }}" class="btn btn-outline-secondary btn-sm" title="Notīrīt filtrus">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Bug Reports Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.825rem;">
                <thead class="table-light border-bottom">
                    <tr class="text-slate-600 small text-uppercase fw-bold font-monospace">
                        <th style="width: 70px;" class="ps-3">ID</th>
                        <th style="width: 140px;">Datums</th>
                        <th style="width: 200px;">Klients / Lietotājs</th>
                        <th style="min-width: 180px;">Sadaļa / Avots</th>
                        <th style="min-width: 280px;">Pēdējā ziņa</th>
                        <th style="width: 80px;" class="text-center">Faili</th>
                        <th style="width: 120px;" class="text-center">Statuss</th>
                        <th style="width: 100px;" class="text-end pe-3">Darbība</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        @php
                            $firstItem = $report->items->first();
                            $latestItem = $report->items->last();
                            $totalAttachments = $report->items->sum(function($i) { return is_array($i->attachments) ? count($i->attachments) : 0; });
                        @endphp
                        <tr class="line @if($report->status_value === 'new') table-danger bg-opacity-10 @elseif($report->status_value === 'answered') table-success bg-opacity-10 @endif">
                            <td class="ps-3 font-monospace fw-bold text-slate-800">
                                #{{ $report->id }}
                            </td>
                            <td>
                                <div class="small fw-semibold text-slate-900">{{ $report->created_at->format('d.m.Y H:i') }}</div>
                                <div class="text-muted small" style="font-size: 0.725rem;">{{ $report->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($report->user)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center small fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="small fw-semibold text-slate-900">{{ $report->user->name }}</div>
                                            <div class="text-muted small" style="font-size: 0.725rem;">{{ $report->user->email }}</div>
                                        </div>
                                    </div>
                                @elseif(!empty($report->email))
                                    <div>
                                        <span class="badge bg-secondary-subtle text-secondary small fw-normal">Viesis</span>
                                        <div class="small fw-semibold text-slate-800">{{ $report->email }}</div>
                                    </div>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary small fw-normal">Viesis / Sistēma</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($report->section))
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium text-truncate d-inline-block" style="max-width: 220px; border-radius: 2px; border-color: #cbd5e1;" title="{{ $report->section }}">
                                        <i class="fa-solid fa-folder me-1 text-primary"></i>{{ $report->section }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                                @if(!empty($firstItem->message))
                                    <div class="text-muted small text-truncate mt-1" style="max-width: 220px; font-size: 0.725rem;" title="{{ $firstItem->message }}">
                                        {{ Str::limit($firstItem->message, 50) }}
                                    </div>
                                @endif
                                @if(!empty($report->url))
                                    <div class="mt-0.5">
                                        <a href="{{ $report->url }}" target="_blank" class="text-decoration-none text-muted small text-truncate d-inline-block" style="max-width: 220px; font-size: 0.725rem;" title="{{ $report->url }}">
                                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>{{ parse_url($report->url, PHP_URL_PATH) ?? $report->url }}
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="text-truncate text-slate-800 fw-medium" style="max-width: 340px;" title="{{ $latestItem->message ?? ($firstItem->message ?? '') }}">
                                    @if($latestItem && $latestItem->is_admin_reply)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 me-1" style="border-radius: 2px; font-size: 0.7rem;">
                                            <i class="fa-solid fa-reply me-1"></i>Admins
                                        </span>
                                    @endif
                                    {{ $latestItem->message ?? ($firstItem->message ?? '-') }}
                                </div>
                                @if($report->items->count() > 1)
                                    <div class="text-muted mt-0.5" style="font-size: 0.725rem;">
                                        <i class="fa-regular fa-comments me-1"></i>{{ $report->items->count() }} ziņas sarunā
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($totalAttachments > 0)
                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="border-radius: 2px; border-color: #cbd5e1; font-size: 0.725rem;" title="{{ $totalAttachments }} pievienoti faili">
                                        <i class="fa-solid fa-paperclip me-1 text-primary"></i>{{ $totalAttachments }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $report->status_enum->badgeClass() }} px-2 py-1 font-monospace" style="border-radius: 2px; font-size: 0.725rem;">
                                    <i class="{{ $report->status_enum->icon() }} me-1"></i> {{ $report->status_enum->label() }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.bug-reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary px-2.5 py-1" style="border-radius: 2px;">
                                    <i class="fa-solid fa-folder-open me-1"></i> Atvērt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 mb-2 d-block text-slate-400"></i>
                                Nav atrasts neviens saziņas vai kļūdu ziņojums.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Rāda no {{ $reports->firstItem() }} līdz {{ $reports->lastItem() }} (kopā: {{ $reports->total() }})
                </div>
                <div>
                    {{ $reports->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
