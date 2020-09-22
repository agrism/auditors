@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Create Invoice</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('partner', ['class'=>'form-horizontal form1', 'method'=>'post', 'action'=>'Client\InvoiceController@store']) !!}
            @include('client.invoices.form')

            {!! Form::submit('Create')!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop