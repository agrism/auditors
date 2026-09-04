@extends('admin.layout.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-modern">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Invoices in System') }}</h5>
                        <span class="small text-muted">{{ __('Browse and manage all recorded company invoices') }}</span>
                    </div>
                </div>
                <a href="{{ url(route('admin.invoices.create')) }}" class="btn btn-modern btn-modern-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('Create Invoice') }}
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 70px;">#</th>
                                <th>{{ __('Number') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Partner') }}</th>
                                <th>{{ __('Currency') }}</th>
                                <th class="text-end">{{ __('Amount') }}</th>
                                <th class="text-center" style="width: 100px;">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr class="{{ (preg_match('/copy/i', $invoice->number ?? '')) ? 'table-warning is-copy-invoice' : '' }}">
                                    <td class="text-center text-muted small fw-semibold">#{{ $invoice->id }}</td>
                                    <td>
                                        <span class="fw-semibold text-slate-900">{{ $invoice->number ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            <i class="fa-regular fa-calendar me-1"></i> {{ $invoice->date ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-slate-800">
                                            <i class="fa-regular fa-building me-1 text-muted"></i>
                                            {{ $invoice->company->title ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-slate-700">
                                            {{ $invoice->partner->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-slate-100 text-slate-700 font-monospace">
                                            {{ $invoice->currency->name ?? 'EUR' }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold font-monospace text-slate-900">
                                        {{ number_format((float)$invoice->amount_total, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url(route('admin.invoices.show', $invoice->id)) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="View details">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-receipt fs-1 mb-2 d-block opacity-25"></i>
                                        {{ __('No invoices found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top d-flex justify-content-center">
                    {!! $invoices->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop