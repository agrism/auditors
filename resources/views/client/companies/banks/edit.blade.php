@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Company Bank data Edit</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('bank', ['class'=>'form-horizontal form1', 'method'=>'put', 'route' => ['client.companies.bank.update', $bank->id], 'files' => true ]) !!}
            @include('client.companies.banks.form')

            {!! Form::submit('Update')!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop