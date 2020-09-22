@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Company data Edit</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('company', ['class'=>'form-horizontal form1', 'method'=>'put', 'route' => ['client.companies.update', $company->id], 'files' => true ]) !!}
            @include('client.companies.form')

            {!! Form::submit('Update')!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop