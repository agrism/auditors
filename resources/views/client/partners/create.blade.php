@extends('client.layout.master') 

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{{_('Create Partner')}}</h3>
	</div>
	<div class="panel-body">

		{!! Form::model('partner', ['class'=>'form-horizontal', 'method'=>'post', 'action'=>'Client\PartnerController@store']) !!}
		@include('client.partners.form')

		{!! Form::submit('Create')!!}
		{!! Form::close() !!}

	</div>
</div>
@stop