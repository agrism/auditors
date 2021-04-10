@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Edit Invoice</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('invoice', ['class'=>'form-horizontal form1', 'method'=>'put', 'route' => ['client.invoices.update', $invoice->id], 'files' => true ]) !!}
            @include('client.invoices.form')

            {!! Form::submit('Save', ['class'=>'btn btn-primary', 'name'=>'submit-name'])!!}
            {!! Form::submit('Update and return to list', ['class'=>'btn btn-primary', 'name'=>'submit-name'])!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop