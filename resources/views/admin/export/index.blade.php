@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-10">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-file-export fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Datu eksports (Tildes Jumis XML)') }}</h5>
                        <span class="small text-muted">{{ __('Eksportējiet rēķinus un finanšu dokumentus XML formātā') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!! Form::model('data', ['method'=>'get', 'route'=>'admin.export']) !!}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="company_id" class="form-label small fw-semibold text-muted mb-1">{{ __('Uzņēmums') }} *</label>
                        {!! Form::select('company_id', $companies->pluck('title', 'id'), isset($data['company_id']) ? $data['company_id'] : null, ['class'=>'form-select form-select-sm', 'id'=>'company_id'] ) !!}
                    </div>

                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                        <button type="button" id="lastMonth" class="btn btn-modern btn-modern-secondary btn-sm">
                            <i class="fa-regular fa-calendar-days me-1"></i> {{ __('Iepriekšējais mēnesis') }}
                        </button>
                    </div>

                    <div class="col-md-6">
                        <label for="dp1" class="form-label small fw-semibold text-muted mb-1">{{ __('Datums no') }}</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                            {!! Form::text('from', isset($data['from']) ? $data['from'] : null, ['class'=>'form-control form-control-sm', 'id'=>'dp1', 'placeholder'=>'DD.MM.YYYY', 'autocomplete'=>'off']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="dp2" class="form-label small fw-semibold text-muted mb-1">{{ __('Datums līdz') }}</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
                            {!! Form::text('to', isset($data['to']) ? $data['to'] : null, ['class'=>'form-control form-control-sm', 'id'=>'dp2', 'placeholder'=>'DD.MM.YYYY', 'autocomplete'=>'off']) !!}
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-file-code me-1"></i> {{ __('Ģenerēt eksporta failu') }}
                    </button>
                </div>
                {!! Form::close() !!}

                @if(!empty($data['company_id']))
                    <div class="mt-4 p-3 bg-slate-50 border rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check text-success fs-4"></i>
                            <div>
                                <div class="fw-semibold text-slate-800">{{ __('Eksporta fails ir sagatavots') }}</div>
                                <span class="small text-muted font-monospace">{{ url('test.xml') }}</span>
                            </div>
                        </div>
                        <a href="{{ url('test.xml') }}" target="_blank" class="btn btn-modern btn-modern-primary btn-sm" download>
                            <i class="fa-solid fa-download me-1"></i> {{ __('Lejupielādēt XML') }}
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

            $('#lastMonth').click(function(){
                $('#dp1').val('<?= date('d.m.Y', strtotime('first day of last month')) ?>');
                $('#dp2').val('<?= date('d.m.Y', strtotime('last day of last month')) ?>');
            });
        });
    </script>
@endsection