@extends('admin.layout.admin') 

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Create Company</h3>
	</div>
	<div class="panel-body">

		{!! Form::model('company', ['class'=>'form-horizontal', 'method'=>'post', 'action'=>'Admin\CompanyController@store']) !!}
		@include('admin.companies.form')

		{!! Form::submit('Create')!!}
		{!! Form::close() !!}

	</div>
</div>
@stop