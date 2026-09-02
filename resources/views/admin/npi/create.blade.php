@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-calculator fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('NPI aprēķins') }} <span class="badge bg-slate-100 text-slate-700 border font-monospace ms-1">konts 2410</span></h5>
                        <span class="small text-muted">{{ __('Nākamo periodu izdevumu sadalījums pa mēnešiem un XML faila ģenerēšana') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                @if (count($errors) > 0)
                    <div class="alert alert-danger rounded-3 mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {!! Form::model('data', ['method'=>'post', 'route'=>'admin.npi.handle']) !!}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="company" class="form-label small fw-semibold text-muted mb-1">{{ __('Uzņēmums / Partneris') }}</label>
                        {!! Form::text('company', request()->get('company'), ['class'=>'form-control form-control-sm', 'id'=>'company', 'placeholder'=>'Piem., Mans Uzņēmums SIA']) !!}
                    </div>

                    <div class="col-md-6">
                        <label for="description" class="form-label small fw-semibold text-muted mb-1">{{ __('Pakalpojuma apraksts') }}</label>
                        {!! Form::text('description', request()->get('description'), ['class'=>'form-control form-control-sm', 'id'=>'description', 'placeholder'=>'Piem., Apdrošināšana, Telpu noma...']) !!}
                    </div>

                    <div class="col-md-6">
                        <label for="invoice_no" class="form-label small fw-semibold text-muted mb-1">{{ __('Rēķina Nr.') }}</label>
                        {!! Form::text('invoice_no', request()->get('invoice_no'), ['class'=>'form-control form-control-sm font-monospace', 'id'=>'invoice_no', 'placeholder'=>'Piem., REK-2026-001']) !!}
                    </div>

                    <div class="col-md-6">
                        <label for="invoice_amount" class="form-label small fw-semibold text-muted mb-1">{{ __('Rēķina summa (EUR)') }} *</label>
                        <div class="input-group input-group-sm">
                            {!! Form::text('invoice_amount', request()->get('invoice_amount'), ['class'=>'form-control form-control-sm font-monospace fw-bold', 'id'=>'invoice_amount', 'placeholder'=>'0.00']) !!}
                            <span class="input-group-text bg-white text-muted">€</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="dp0" class="form-label small fw-semibold text-muted mb-1">{{ __('Rēķina datums') }} *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                            {!! Form::text('invoice_date', request()->get('invoice_date'), ['class'=>'form-control form-control-sm', 'id'=>'dp0', 'placeholder'=>'DD.MM.YYYY', 'autocomplete'=>'off']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="dp1" class="form-label small fw-semibold text-muted mb-1">{{ __('Periods no') }} *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                            {!! Form::text('period_from', request()->get('period_from') ?: request()->get('invoice_date'), ['class'=>'form-control form-control-sm', 'id'=>'dp1', 'placeholder'=>'DD.MM.YYYY', 'autocomplete'=>'off']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="dp2" class="form-label small fw-semibold text-muted mb-1">{{ __('Periods līdz') }} *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                            {!! Form::text('period_till', request()->get('period_till'), ['class'=>'form-control form-control-sm', 'id'=>'dp2', 'placeholder'=>'DD.MM.YYYY', 'autocomplete'=>'off']) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="dp3" class="form-label small fw-semibold text-muted mb-1">{{ __('Atskaites datums') }} *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                            {!! Form::text('report_date', request()->get('report_date'), ['class'=>'form-control form-control-sm', 'id'=>'dp3', 'placeholder'=>'DD.MM.YYYY', 'autocomplete'=>'off']) !!}
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-calculator me-1"></i> {{ __('Aprēķināt NPI sadalījumu') }}
                    </button>

                    @if(isset($dataArray) && is_array($dataArray) && count($dataArray) > 0)
                        @if(empty($fileName))
                            <button type="submit" class="btn btn-modern btn-modern-primary btn-sm" name="submitValue" value="Generate NPI xml">
                                <i class="fa-solid fa-file-code me-1"></i> {{ __('Ģenerēt NPI XML') }}
                            </button>
                        @endif
                    @endif
                </div>

                @if(!empty($fileName))
                    <div class="mt-4 p-3 bg-slate-50 border rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check text-success fs-4"></i>
                            <div>
                                <div class="fw-semibold text-slate-800">{{ __('NPI XML fails ir sagatavots') }}</div>
                                <span class="small text-muted font-monospace">{{ $fileName }}</span>
                            </div>
                        </div>
                        <a href="/{{ $fileName }}" target="_blank" class="btn btn-modern btn-modern-primary btn-sm" download>
                            <i class="fa-solid fa-download me-1"></i> {{ __('Lejupielādēt XML failu') }}
                        </a>
                    </div>
                @endif

                {!! Form::close() !!}
            </div>
        </div>

        @if(isset($dataArray) && is_array($dataArray) && count($dataArray) > 0)
            <div class="card card-modern shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 bg-emerald-50 text-emerald-600 p-2 d-inline-flex">
                            <i class="fa-solid fa-list-check fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ __('NPI aprēķina rezultāti') }}</h5>
                            <span class="small text-muted">{{ count($dataArray) }} {{ __('mēnešu periodi') }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                            <tr>
                                <th style="width: 110px;">{{ __('Datums') }}</th>
                                <th>{{ __('Partneris') }}</th>
                                <th style="width: 140px;">{{ __('Dokumenta Nr.') }}</th>
                                <th>{{ __('Apraksts') }}</th>
                                <th style="width: 100px;" class="text-center">{{ __('Dienas') }}</th>
                                <th style="width: 140px;" class="text-end">{{ __('Summa') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $totalAmount = 0.00; @endphp
                            @foreach($dataArray as $data)
                                @php $totalAmount += floatval(str_replace(',', '', $data['amount'])); @endphp
                                <tr class="line text-truncate">
                                    <td class="text-muted small">{{ $data['date'] }}</td>
                                    <td>
                                        <div class="fw-semibold text-slate-800">{{ $data['partner_name'] ?: '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="font-monospace text-slate-700 small">{{ $data['number'] ?: '-' }}</span>
                                    </td>
                                    <td class="text-muted small">{{ $data['comment'] ?: '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-slate-100 text-slate-700 font-monospace">{{ $data['days'] }}</span>
                                    </td>
                                    <td class="text-end font-monospace fw-bold text-slate-900">
                                        {{ number_format(floatval(str_replace(',', '', $data['amount'])), 2) }} €
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="bg-slate-50 border-top fw-semibold">
                                <td colspan="5" class="text-end text-slate-700 py-3">{{ __('KOPĀ:') }}</td>
                                <td class="text-end font-monospace fw-bold text-primary-700 fs-6 py-3">
                                    {{ number_format($totalAmount, 2) }} €
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#dp0, #dp1, #dp2, #dp3').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6]
            });
        });
    </script>
@endsection
