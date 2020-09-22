@extends('admin.layout.admin')

@section('content')

    <h3>Create Role</h3>


    <div class="panel-body">

        {!! Form::open( ['class'=>'form-horizontal', 'method'=>'post', 'route'=>['admin.roles.store']]) !!}
        @include('admin.roles.form')

        {!! Form::submit('Save')!!}
        {!! Form::close() !!}

    </div>

@stop

@section('sidebar')


@stop