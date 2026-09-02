@if (count($errors) > 0)
    <div class="alert alert-danger rounded-3 mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label for="partner_id" class="form-label small fw-semibold text-muted mb-1">{{ __('Partneris / Saņēmējs') }} *</label>
        {!! Form::select('partner_id', isset($partners) ? $partners : [], null , ['class'=>'form-select form-select-sm', 'id'=>'partner_id', 'placeholder'=>'-- Izvēlieties partneri --'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="income_net_amount_reg" class="form-label small fw-semibold text-muted mb-1">{{ __('Personas kods / Reģistrācijas Nr.') }}</label>
        {!! Form::text('partner_registration_number', null , ['class'=>'form-control form-control-sm font-monospace bg-light', 'placeholder'=>'Automātiski no partnera datiem', 'id'=>'income_net_amount_reg', 'readonly'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="personal_income_type_id" class="form-label small fw-semibold text-muted mb-1">{{ __('Ienākuma veids') }} *</label>
        {!! Form::select('personal_income_type_id', isset($personalIncomeTypes) ? $personalIncomeTypes : [], null , ['class'=>'form-select form-select-sm', 'id'=>'personal_income_type_id', 'placeholder'=>'-- Izvēlieties ienākuma veidu --'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="income_paid_date" class="form-label small fw-semibold text-muted mb-1">{{ __('Izmaksas datums') }} *</label>
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
            {!! Form::text('income_paid_date', null , ['class'=>'form-control form-control-sm', 'placeholder'=>'DD.MM.YYYY', 'id'=>'income_paid_date', 'autocomplete'=>'off'] ) !!}
        </div>
    </div>

    <div class="col-md-6">
        <label for="income_period_date_from" class="form-label small fw-semibold text-muted mb-1">{{ __('Ienākumu periods no') }}</label>
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
            {!! Form::text('income_period_date_from', null , ['class'=>'form-control form-control-sm', 'placeholder'=>'DD.MM.YYYY', 'id'=>'income_period_date_from', 'autocomplete'=>'off'] ) !!}
        </div>
    </div>

    <div class="col-md-6">
        <label for="income_period_date_till" class="form-label small fw-semibold text-muted mb-1">{{ __('Ienākumu periods līdz') }}</label>
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
            {!! Form::text('income_period_date_till', null , ['class'=>'form-control form-control-sm', 'placeholder'=>'DD.MM.YYYY', 'id'=>'income_period_date_till', 'autocomplete'=>'off'] ) !!}
        </div>
    </div>

    <div class="col-md-6">
        <label for="income_gross_amount" class="form-label small fw-semibold text-muted mb-1">{{ __('Bruto summa (EUR)') }} *</label>
        <div class="input-group input-group-sm">
            {!! Form::text('income_gross_amount', null , ['class'=>'form-control form-control-sm font-monospace fw-bold', 'placeholder'=>'0.00', 'id'=>'income_gross_amount'] ) !!}
            <span class="input-group-text bg-white text-muted">€</span>
        </div>
    </div>

    <div class="col-md-6">
        <label for="income_net_amount" class="form-label small fw-semibold text-muted mb-1">{{ __('Neto summa (EUR)') }}</label>
        <div class="input-group input-group-sm">
            {!! Form::text('income_net_amount', null , ['class'=>'form-control form-control-sm font-monospace', 'placeholder'=>'0.00', 'id'=>'income_net_amount'] ) !!}
            <span class="input-group-text bg-white text-muted">€</span>
        </div>
    </div>

    <div class="col-md-6">
        <label for="income_tax_rate_id" class="form-label small fw-semibold text-muted mb-1">{{ __('IIN likme') }}</label>
        {!! Form::select('income_tax_rate_id', isset($personalIncomeTaxRates) ? $personalIncomeTaxRates->pluck('name', 'id') : [], null , ['class'=>'form-select form-select-sm', 'id'=>'income_tax_rate_id', 'placeholder'=>'-- Izvēlieties nodokļa likmi --'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="personal_income_cost_rate_id" class="form-label small fw-semibold text-muted mb-1">{{ __('Attaisnoto izdevumu norma') }}</label>
        {!! Form::select('personal_income_cost_rate_id', isset($personalIncomeCostRates) ? $personalIncomeCostRates->pluck('name', 'id') : [], null , ['class'=>'form-select form-select-sm', 'id'=>'personal_income_cost_rate_id', 'placeholder'=>'-- Izvēlieties normu --'] ) !!}
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function () {
            $('#income_period_date_from, #income_period_date_till, #income_paid_date').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });
        });
    </script>
@stop


