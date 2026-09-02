@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-building-user fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Sistēmas uzņēmumu saraksts') }}</h5>
                        <span class="small text-muted">{{ __('Pārvaldiet visus sistēmā reģistrētos uzņēmumus, to lietotājus un iestatījumus') }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.companies.create') }}" class="btn btn-modern btn-modern-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('Jauns uzņēmums') }}
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>{{ __('Nosaukums') }}</th>
                            <th style="width: 200px;">{{ __('Reģistrācijas Nr.') }}</th>
                            <th style="width: 180px;">{{ __('Slēgto datu datums') }}</th>
                            <th class="text-end" style="width: 170px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($companies as $company)
                            <tr class="line text-truncate">
                                <td class="text-muted font-monospace small">#{{ $company->id }}</td>
                                <td class="text-truncate">
                                    <div class="fw-semibold text-slate-800">{{ $company->title }}</div>
                                </td>
                                <td class="text-truncate">
                                    @if(!empty($company->registration_number))
                                        <span class="font-monospace text-slate-700 fw-medium">{{ $company->registration_number }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-truncate text-muted">
                                    {{ $company->closed_data_date ?: '-' }}
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('admin.companies.users.show', $company->id) }}"
                                           class="btn btn-sm btn-outline-info d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           title="{{ __('Lietotāji') }}">
                                            <i class="fa-solid fa-users" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.companies.edit', $company->id) }}"
                                           class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           title="{{ __('Labot') }}">
                                            <i class="fa-solid fa-pen-to-square" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.companies.show', $company->id) }}"
                                           class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           title="{{ __('Informācija') }}">
                                            <i class="fa-solid fa-circle-info" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.companies.destroy', [$company->id, 'method' => 'delete']) }}"
                                           class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           onclick="return confirm('Vai tiešām vēlaties dzēst uzņēmumu?');"
                                           title="{{ __('Dzēst') }}">
                                            <i class="fa-solid fa-trash-can" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    {{ __('Nav reģistrēts neviens uzņēmums.') }}
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