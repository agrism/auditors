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
	{!! Form::label('name', 'Name', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::text('name', isset($partner) ? $partner['name'] : null , ['class'=>'form-control', 'placeholder'=>'Input name'] ) !!}
	</div>
</div>


<div class="form-group">
	{!! Form::label('address', 'Address', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::text('address', isset($partner) ? $partner['address'] : null , ['class'=>'form-control', 'placeholder'=>'Input address'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('registration_number', 'Reg.No', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::text('registration_number', isset($partner) ? $partner['registration_number'] : null , ['class'=>'form-control', 'placeholder'=>'Input Reg.no'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('vat_number', 'VAT.No', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::text('vat_number', isset($partner) ? $partner['vat_number'] : null , ['class'=>'form-control', 'placeholder'=>'Input VAT.no'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('bank', 'Bank', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::text('bank', isset($partner) ? $partner['bank'] : null , ['class'=>'form-control', 'placeholder'=>'Input Bank'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('swift', 'SWIFT', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::text('swift', isset($partner) ? $partner['swift'] : null , ['class'=>'form-control', 'placeholder'=>'Input swift'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('account_number', 'Account No.', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::text('account_number', isset($partner) ? $partner['account_number'] : null , ['class'=>'form-control', 'placeholder'=>'Input account number'] ) !!}
	</div>
</div>