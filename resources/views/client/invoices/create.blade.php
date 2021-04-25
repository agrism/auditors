@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Create Invoice</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('partner', ['class'=>'form-horizontal form1', 'method'=>'post', 'action'=>'Client\InvoiceController@store']) !!}
            @include('client.invoices.form')

            <div class="form-group" style="margin-top: 10px;">
                <div class="col-sm-12">
                    {!! Form::submit('Create', ['class'=>'btn btn-primary', 'name'=>'submit-name'])!!}
                    {!! Form::submit('Update and return to list', ['class'=>'btn btn-primary', 'name'=>'submit-name'])!!}
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop