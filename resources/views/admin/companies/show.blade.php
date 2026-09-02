@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-building fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $company->title }} <span class="badge bg-primary-50 text-primary-700 border ms-1 font-monospace">#{{ $company->id }}</span></h5>
                        <span class="small text-muted">{{ __('Uzņēmuma pārskats un saistītā informācija') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.company.structuralunits.index', $company->id) }}" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-sitemap me-1"></i> {{ __('Struktūrvienības') }}
                    </a>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-modern btn-modern-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ uz sarakstu') }}
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
                            <th>{{ __('Reģistrācijas Nr.') }}</th>
                            <th class="text-end" style="width: 170px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-muted font-monospace small">#{{ $company->id }}</td>
                            <td>
                                <div class="fw-semibold text-slate-800">{{ $company->title }}</div>
                            </td>
                            <td>
                                <span class="font-monospace text-slate-700">{{ $company->registration_number ?: '-' }}</span>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop