@extends('admin.layout.admin')

@section('content')

    <h3>Edit Role</h3>


    <div class="panel-body">

        {!! Form::model($role, ['class'=>'form-horizontal', 'method'=>'put', 'route'=>['admin.roles.update', $role->id]]) !!}
        @include('admin.roles.form')

        {!! Form::submit('Save')!!}
        {!! Form::close() !!}

    </div>

@stop

@section('sidebar')


@stop