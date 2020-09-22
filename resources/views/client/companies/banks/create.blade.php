@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Create new Payment receiver</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('bank', ['class'=>'form-horizontal', 'method'=>'post', 'action'=>'Client\CompanyBankController@store']) !!}
            @include('client.companies.banks.form')

            {!! Form::submit('Create')!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop