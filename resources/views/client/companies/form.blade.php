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
    {!! Form::label('title', 'Title', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('title', isset($company) ? $company['title'] : null , ['class'=>'form-control', 'placeholder'=>'Input title'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('address', 'address', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('address', isset($company) ? $company['address'] : null , ['class'=>'form-control', 'placeholder'=>'Input address'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('registration_number', 'Reg.No', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('registration_number', isset($company) ? $company['registration_number'] : null , ['class'=>'form-control', 'placeholder'=>'Input Reg.no'] ) !!}
    </div>
</div>
<hr>


<div class="form-group">
    {!! Form::label('bank', 'Default bank', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('bank', isset($company) ? $company['bank'] : null , ['class'=>'form-control', 'placeholder'=>'Input bank name'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('swift', 'SWIFT', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('swift', isset($company) ? $company['swift'] : null , ['class'=>'form-control', 'placeholder'=>'Input SWIFT'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('account_number', 'Account number', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('account_number', isset($company) ? $company['account_number'] : null , ['class'=>'form-control', 'placeholder'=>'Input account number'] ) !!}
    </div>
</div>
<hr>

@if(isset($company) && isset($company['vatNumbers']) )
    @foreach($company['vatNumbers'] as $vat)

        <div class="form-group">
            {!! Form::hidden('vat_id[]', $vat->id) !!}
            {!! Form::label('vat_number', 'Vat number', ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-9">
                {!! Form::text('vat_number[]', isset($vat) ? $vat['vat_number'] : null , ['class'=>'form-control', 'placeholder'=>'Input account number'] ) !!}
            </div>
            <div class="btn btn-xs btn-danger fa fa-remove remove-vat-number"></div>
        </div>

    @endforeach
@endif

{{-- empty vissible vat no input --}}
<div class="form-group">
    {!! Form::label('vat_number', 'Vat number', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('vat_number[]', null , ['class'=>'form-control', 'placeholder'=>'Input account number'] ) !!}
    </div>
    <div class="btn btn-xs btn-danger fa fa-remove remove-vat-number"></div>
</div>

{{-- empty invissible vat no input for clone --}}
<div class="form-group vat_number_div hide">
    {!! Form::label('vat_number', 'Vat number', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('vat_number[]', null , ['class'=>'form-control', 'placeholder'=>'Input account number'] ) !!}
    </div>
    <div class="btn btn-xs btn-danger fa fa-remove remove-vat-number"></div>
</div>


<div id="div1"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">
        <div class="btn btn-primary fa-plus fa" id="addVatNuber"></div>
    </label>
</div>

<script>
    $(document).ready(function () {


        $('#addVatNuber').on('click', function () {
            var clonedDiv = $('.vat_number_div').clone();
            clonedDiv.find('input:text').val('');
            clonedDiv.removeClass('vat_number_div');
            clonedDiv.removeClass('hide');

            ($('#div1')).before(clonedDiv);

        });


        $(document).on('click', '.remove-vat-number', function () {

            count = $('input[name*="vat_number"]').length;

            if (count > 2) {
                $(this).parent().remove();
            }
        });
    });
</script>