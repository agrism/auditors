@extends('admin.layout.admin')

@section('content')

    <h3>Edit Permission</h3>


    <div class="panel-body">

        {!! Form::model($permission, ['class'=>'form-horizontal', 'method'=>'put', 'route'=>['admin.permissions.update', $permission->id]]) !!}
        @include('admin.permissions.form')

        {!! Form::submit('Save')!!}
        {!! Form::close() !!}

    </div>

@stop

@section('sidebar')


@stop