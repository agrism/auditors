@extends('admin.layout.admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1">
                <i class="fa-solid fa-list-check text-primary me-2"></i>Lietotāju aktivitātes žurnāls
            </h1>
            <p class="text-slate-500 mb-0 small">Pārskatiet lietotāju pieprasījumus, darbības, IP adreses un sistēmas notikumus reāllaikā.</p>
        </div>
        <div>
            <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-rotate me-1"></i> Atsvaidzināt
            </a>
        </div>
    </div>

    <!-- Summary Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-database fs-5"></i>
                    </div>
                    <div>
                        <div class="text-slate-500 small fw-medium">Kopā ierakstu</div>
                        <div class="fs-4 fw-bold text-slate-900">{{ number_format($totalCount, 0, '.', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-bolt fs-5"></i>
                    </div>
                    <div>
                        <div class="text-slate-500 small fw-medium">Šodienas aktivitāte</div>
                        <div class="fs-4 fw-bold text-slate-900">{{ number_format($todayCount, 0, '.', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                    <div>
                        <div class="text-slate-500 small fw-medium">Aktīvie lietotāji</div>
                        <div class="fs-4 fw-bold text-slate-900">{{ number_format($uniqueUsersCount, 0, '.', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning-subtle text-warning p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-network-wired fs-5"></i>
                    </div>
                    <div>
                        <div class="text-slate-500 small fw-medium">Unikālās IP adreses</div>
                        <div class="fs-4 fw-bold text-slate-900">{{ number_format($uniqueIpsCount, 0, '.', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.logs.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Lietotājs</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">-- Visi lietotāji --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Metode</label>
                    <select name="method" class="form-select form-select-sm">
                        <option value="">-- Visas --</option>
                        <option value="GET" {{ request('method') == 'GET' ? 'selected' : '' }}>GET</option>
                        <option value="POST" {{ request('method') == 'POST' ? 'selected' : '' }}>POST</option>
                        <option value="PUT" {{ request('method') == 'PUT' ? 'selected' : '' }}>PUT</option>
                        <option value="DELETE" {{ request('method') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                    </select>
                </div>

                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Meklēt URL / Datus</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="URL vai saturs...">
                </div>

                <div class="col-md-3 col-sm-6">
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
                    @if(request()->anyFilled(['user_id', 'method', 'search', 'ip', 'from_date', 'to_date']))
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary btn-sm" title="Notīrīt filtrus">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light border-bottom">
                    <tr class="text-slate-600 small text-uppercase fw-bold">
                        <th style="width: 70px;">ID</th>
                        <th style="width: 170px;">Laiks</th>
                        <th style="width: 180px;">Lietotājs</th>
                        <th style="width: 90px;">Metode</th>
                        <th>URL Pieprasījums</th>
                        <th style="width: 130px;">IP Adrese</th>
                        <th style="width: 100px;" class="text-end">Dati</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-muted small">#{{ $log->id }}</td>
                            <td>
                                <div class="small fw-semibold text-slate-800">{{ $log->created_at->format('d.m.Y H:i:s') }}</div>
                                <div class="text-muted small" style="font-size: 0.75rem;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($log->user)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center small fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="small fw-semibold text-slate-900">{{ $log->user->name }}</div>
                                            <div class="text-muted small" style="font-size: 0.75rem;">{{ $log->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary small fw-normal">Viesis / Sistēma</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $methodClass = match($log->method) {
                                        'GET' => 'bg-info-subtle text-info border border-info-subtle',
                                        'POST' => 'bg-success-subtle text-success border border-success-subtle',
                                        'PUT', 'PATCH' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                        'DELETE' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                        default => 'bg-secondary-subtle text-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $methodClass }} font-monospace fw-bold">{{ $log->method }}</span>
                            </td>
                            <td>
                                <span class="font-monospace small text-slate-800 text-break" style="font-size: 0.82rem;">
                                    {{ $log->url }}
                                </span>
                            </td>
                            <td>
                                <span class="font-monospace small text-muted">{{ $log->ip }}</span>
                            </td>
                            <td class="text-end">
                                @if(!empty($log->data) && $log->data !== '[]' && $log->data !== 'null')
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#logModal{{ $log->id }}">
                                        <i class="fa-solid fa-code me-1"></i> Dati
                                    </button>

                                    <!-- Data Details Modal -->
                                    <div class="modal fade text-start" id="logModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-3">
                                                <div class="modal-header bg-dark text-white">
                                                    <h5 class="modal-title fs-6">
                                                        <i class="fa-solid fa-file-code me-2 text-primary-400"></i>Ieraksta #{{ $log->id }} parametri
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4 bg-light">
                                                    <div class="mb-2 small text-muted">
                                                        <strong>URL:</strong> <span class="font-monospace">{{ $log->method }} {{ $log->url }}</span>
                                                    </div>
                                                    <div class="mb-3 small text-muted">
                                                        <strong>Laiks:</strong> {{ $log->created_at->format('d.m.Y H:i:s') }} (IP: {{ $log->ip }})
                                                    </div>
                                                    <pre class="bg-dark text-light p-3 rounded-3 font-monospace small mb-0" style="max-height: 400px; overflow-y: auto;">{{ $log->data }}</pre>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Aizvērt</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 mb-2 d-block text-slate-400"></i>
                                Nav atrasts neviens aktivitātes ieraksts.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Rāda no {{ $logs->firstItem() }} līdz {{ $logs->lastItem() }} (kopā: {{ $logs->total() }})
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
