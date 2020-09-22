@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Edit Invoice</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('invoice', ['class'=>'form-horizontal form1', 'method'=>'put', 'route' => ['client.invoices.update', $invoice->id], 'files' => true ]) !!}
            @include('client.invoices.form')

            {!! Form::submit('Update', ['class'=>'btn btn-primary'])!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop