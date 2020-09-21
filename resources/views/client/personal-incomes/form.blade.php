@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    {!! Form::label('partner_id', _('Partner'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::select('partner_id', isset($partners) ? $partners : [], null , ['class'=>'form-control', 'placeholder'=>_('Select partner')] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('partner_registration_number', _('Registration No'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::text('partner_registration_number', null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'income_net_amount', 'readonly'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('personal_income_type_id', _('Income type'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::select('personal_income_type_id', isset($personalIncomeTypes) ? $personalIncomeTypes : [], null , ['class'=>'form-control', 'placeholder'=>_('Select income type')] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('income_period_date_from', _('Income period date From-To'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5 row">
        <div class="col-sm-6">
            {!! Form::text('income_period_date_from', null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'income_period_date_from', 'readonly'] ) !!}
        </div>
        <div class="col-sm-6">
            {!! Form::text('income_period_date_till', null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'income_period_date_till', 'readonly'] ) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('income_paid_date', _('Income paid date'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::text('income_paid_date', null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'income_paid_date', 'readonly'] ) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('income_gross_amount', _('Income amount (gross)'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::text('income_gross_amount', null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'income_gross_amount'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('income_net_amount', _('Income amount (net)'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::text('income_net_amount', null , ['class'=>'form-control', 'placeholder'=>'', 'id'=>'income_net_amount'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('income_tax_rate_id', _('Income tax'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::select('income_tax_rate_id', isset($personalIncomeTaxRates) ? $personalIncomeTaxRates->pluck('name', 'id') : [], null , ['class'=>'form-control', 'placeholder'=>_('Select tax rate')] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('personal_income_cost_rate_id', _('Taxable cost rate'), ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-5">
        {!! Form::select('personal_income_cost_rate_id', isset($personalIncomeCostRates) ? $personalIncomeCostRates->pluck('name', 'id') : [], null , ['class'=>'form-control', 'placeholder'=>_('Select taxable cost rate')] ) !!}
    </div>
</div>


@section('js')
    <script>
        $(document).ready(function () {
            $('#income_period_date_from').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                //                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });

            $('#income_period_date_till').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                //                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });

            $('#income_paid_date').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                //                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });
        });

    </script>
@stop


