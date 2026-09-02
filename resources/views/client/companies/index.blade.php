@extends('client.layout.master')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-building fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Izvēlieties uzņēmumu') }}</h5>
                        <span class="small text-muted">{{ __('Atlasiet uzņēmumu, kuru vēlaties pārvaldīt') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>{{ __('Nosaukums') }}</th>
                            <th>{{ __('Reģistrācijas Nr.') }}</th>
                            <th class="text-end" style="width: 120px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($user->companies as $company)
                            <tr class="line">
                                <td class="text-muted font-monospace small">#{{ $company->id }}</td>
                                <td>
                                    <a href="{{ route('client.companies.show', $company->id) }}" class="fw-semibold text-primary text-decoration-none">
                                        {{ $company->title }}
                                    </a>
                                </td>
                                <td>
                                    <span class="font-monospace text-slate-700">{{ $company->registration_number ?: '-' }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('client.companies.show', $company->id) }}" class="btn btn-modern btn-modern-primary btn-sm">
                                        <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> {{ __('Atvērt') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    {{ __('Jums nav piešķirts neviens uzņēmums.') }}
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