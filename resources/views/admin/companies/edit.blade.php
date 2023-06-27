@extends('admin.layout.admin')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Edit Company</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('company', ['class'=>'form-horizontal', 'method'=>'put', 'route'=>['admin.companies.update', $company->id]]) !!}
            @include('admin.companies.form')

            {!! Form::submit('Update')!!}
            {!! Form::close() !!}

        </div>

    </div>
@stop