@extends('admin.layout.admin')

@section('content')

    <h3 >Create Permission</h3>


    <div class="panel-body">

        {!! Form::open( ['class'=>'form-horizontal', 'method'=>'post', 'route'=>['admin.permissions.store']]) !!}
        @include('admin.permissions.form')

        {!! Form::submit('Save')!!}
        {!! Form::close() !!}

    </div>

@stop

@section('sidebar')


@stop