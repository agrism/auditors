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
                            <th class="text-end" style="width: 140px;">{{ __('Darbības') }}</th>
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
                                <div class="dropdown eds-action-btn-group">
                                    <button class="btn eds-action-btn dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <span>{{ __('Darbības') }}</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end eds-action-menu shadow">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.companies.edit', $company->id) }}">
                                                <i class="fa-solid fa-pen-to-square text-primary"></i>
                                                <span>{{ __('Labot') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.companies.users.show', $company->id) }}">
                                                <i class="fa-solid fa-users text-success"></i>
                                                <span>{{ __('Lietotāji') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.company.structuralunits.index', $company->id) }}">
                                                <i class="fa-solid fa-sitemap text-secondary"></i>
                                                <span>{{ __('Struktūrvienības') }}</span>
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <a class="dropdown-item text-danger"
                                               href="{{ route('admin.companies.destroy', [$company->id, 'method' => 'delete']) }}"
                                               onclick="return confirm('Vai tiešām vēlaties dzēst uzņēmumu?');">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span>{{ __('Dzēst') }}</span>
                                            </a>
                                        </li>
                                    </ul>
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