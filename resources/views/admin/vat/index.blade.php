@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-10">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-percent fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('PVN deklarācijas eksports (EDS XML)') }}</h5>
                        <span class="small text-muted">{{ __('Ģenerējiet PVN deklarācijas XML failu iesniegšanai VID EDS sistēmā') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('admin.vat.handle') }}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="company_id" class="form-label small fw-semibold text-muted mb-1">{{ __('Uzņēmums') }} *</label>
                            <select name="company_id" id="company_id" class="form-select form-select-sm">
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @if(($data['company_id'] ?? null) == $company->id) selected @endif>{{ $company->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="dp1" class="form-label small fw-semibold text-muted mb-1">{{ __('Periods no') }} *</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                                {!! Form::text('from', isset($data['from']) ? $data['from'] : null, ['class'=>'form-control form-control-sm', 'id'=>'dp1', 'placeholder'=>'DD.MM.YYYY', 'autocomplete'=>'off']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="dp2" class="form-label small fw-semibold text-muted mb-1">{{ __('Periods līdz') }} *</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                                {!! Form::text('to', isset($data['to']) ? $data['to'] : null, ['class'=>'form-control form-control-sm', 'id'=>'dp2', 'placeholder'=>'DD.MM.YYYY', 'autocomplete'=>'off']) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="payable" class="form-label small fw-semibold text-muted mb-1">{{ __('Maksājamā summa (EUR)') }}</label>
                            <div class="input-group input-group-sm">
                                {!! Form::text('payable', isset($data['payable']) ? $data['payable'] : null, ['class'=>'form-control form-control-sm font-monospace', 'id'=>'payable', 'placeholder'=>'0.00', 'autocomplete'=>'off']) !!}
                                <span class="input-group-text bg-white text-muted">€</span>
                            </div>
                            <div class="form-text small text-muted">{{ __('Norādiet vēlamo maksājamo PVN summu vai atstājiet tukšu automātiskam aprēķinam.') }}</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-modern btn-modern-primary btn-sm">
                            <i class="fa-solid fa-file-code me-1"></i> {{ __('Ģenerēt PVN deklarācijas failu') }}
                        </button>
                    </div>
                </form>

                @if(file_exists(public_path('test.xml')))
                    <div class="mt-4 p-3 bg-slate-50 border rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check text-success fs-4"></i>
                            <div>
                                <div class="fw-semibold text-slate-800">{{ __('PVN EDS XML fails ir sagatavots') }}</div>
                                <span class="small text-muted font-monospace">{{ url('test.xml') }}</span>
                            </div>
                        </div>
                        <a href="{{ url('test.xml') }}" target="_blank" class="btn btn-modern btn-modern-primary btn-sm" download>
                            <i class="fa-solid fa-download me-1"></i> {{ __('Lejupielādēt XML failu') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#dp1, #dp2').datepicker({
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