@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-sitemap fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Uzņēmuma struktūrvienības') }}: <span class="text-primary-700">{{ $company['title'] ?? '' }}</span></h5>
                        <span class="small text-muted">{{ __('Pārvaldiet uzņēmuma struktūrvienības un lietotāju piesaistes') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ url(route('admin.company.structuralunits.create', $company['id'])) }}" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('Jauna struktūrvienība') }}
                    </a>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-modern btn-modern-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ') }}
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>{{ __('Nosaukums') }}</th>
                            <th class="text-end" style="width: 140px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($structuralunits as $unit)
                            <tr class="line">
                                <td class="text-muted font-monospace small">#{{ $unit->id }}</td>
                                <td>
                                    <div class="fw-semibold text-slate-800">{{ $unit->title }}</div>
                                    @if(isset($company->users) && count($company->users) > 0)
                                        <div class="mt-1 d-flex flex-wrap gap-2">
                                            @foreach($company->users as $user)
                                                @if(in_array($user->id, $unit->users->pluck('id')->all()))
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle small">
                                                        <i class="fa-solid fa-user-check me-1"></i> {{ $user->name }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="dropdown eds-action-btn-group">
                                        <button class="btn eds-action-btn dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{ __('Darbības') }}</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end eds-action-menu shadow">
                                            <li>
                                                <a class="dropdown-item" href="{{ url(route('admin.company.structuralunits.edit', [$company['id'], $unit->id])) }}">
                                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                                    <span>{{ __('Labot') }}</span>
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <a class="dropdown-item text-danger"
                                                   href="{{ url(route('admin.company.structuralunits.destroy', [$company['id'], $unit->id, 'method' => 'delete'])) }}"
                                                   onclick="return confirm('Vai tiešām vēlaties dzēst struktūrvienību?');">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                    <span>{{ __('Dzēst') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    {{ __('Nav reģistrēta neviena struktūrvienība.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop