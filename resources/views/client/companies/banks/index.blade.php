@extends('client.layout.master')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-building-columns fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Papildu maksājumu saņēmēji') }}</h5>
                        <span class="small text-muted">{{ __('Pārvaldiet uzņēmuma papildu bankas kontus un rekvizītus') }}</span>
                    </div>
                </div>

                <div>
                    <a href="{{ route('client.companies.bank.create') }}" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('Pievienot bankas kontu') }}
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>{{ __('Maksājuma saņēmējs') }}</th>
                            <th>{{ __('Banka') }}</th>
                            <th>{{ __('Piezīme') }}</th>
                            <th class="text-end" style="width: 100px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($banks as $bank)
                            <tr class="line">
                                <td class="text-muted font-monospace small">#{{ $bank->id }}</td>
                                <td class="fw-semibold text-slate-800">{{ $bank->payment_receiver ?: '-' }}</td>
                                <td>{{ $bank->bank ?: '-' }}</td>
                                <td class="text-muted small">{{ $bank->comment ?: '-' }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('client.companies.bank.edit', $bank->id) }}"
                                           class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 26px; height: 26px;"
                                           title="{{ __('Labot') }}">
                                            <i class="fa-solid fa-pen" style="font-size: 0.75rem;"></i>
                                        </a>

                                        <form action="{{ route('client.companies.bank.destroy', $bank->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Vai tiešām vēlaties dzēst šo bankas kontu?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                                    style="width: 26px; height: 26px;"
                                                    title="{{ __('Dzēst') }}">
                                                <i class="fa-solid fa-trash-can" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    {{ __('Nav reģistrēts neviens papildu bankas konts.') }}
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