@extends('client.layout.master')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-handshake fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Partneru saraksts') }}</h5>
                        <span class="small text-muted">{{ __('Pārvaldiet uzņēmuma partnerus, klientus un piegādātājus') }}</span>
                    </div>
                </div>

                <div>
                    <a href="{{ route('client.partners.create') }}" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('Pievienot partneri') }}
                    </a>
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
                            <th>{{ __('Adrese') }}</th>
                            <th class="text-end" style="width: 100px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($partners as $partner)
                            <tr class="line">
                                <td class="text-muted font-monospace small">#{{ $partner->id }}</td>
                                <td class="fw-semibold text-slate-800">{{ $partner->name }}</td>
                                <td>
                                    <span class="font-monospace text-slate-700">{{ $partner->registration_number ?: '-' }}</span>
                                </td>
                                <td class="text-muted small">{{ $partner->address ?: '-' }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('client.partners.edit', $partner->id) }}"
                                           class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 26px; height: 26px;"
                                           title="{{ __('Labot') }}">
                                            <i class="fa-solid fa-pen" style="font-size: 0.75rem;"></i>
                                        </a>

                                        <a href="{{ route('client.partners.delete', $partner->id) }}"
                                           onclick="return confirm('Vai tiešām vēlaties dzēst šo partneri?');"
                                           class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 26px; height: 26px;"
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
                                    {{ __('Nav reģistrēts neviens partneris.') }}
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