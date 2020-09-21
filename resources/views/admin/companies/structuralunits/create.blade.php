@extends('admin.layout.admin')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Company: {{$company->title}}: Create Structural Unit for </h3>
        </div>
        <div class="panel-body">

            {!! Form::model('structuralunit', ['class'=>'form-horizontal', 'method'=>'post', 'action'=>['Admin\StructuralunitController@store', $company['id']]]) !!}
            @include('admin.companies.structuralunits.form')

            {!! Form::submit('Create')!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop