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
		{!! Form::text('name', isset($user) ? $user['name'] : null , ['class'=>'form-control', 'placeholder'=>'Input title'] ) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('email', 'e-mail', ['class'=>'col-sm-2 control-label']) !!}
	<div class="col-sm-10">
		{!! Form::text('email', isset($user) ? $user['email'] : null , ['class'=>'form-control', 'placeholder'=>'Input email'] ) !!}
	</div>
</div>