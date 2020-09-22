@extends('admin.layout.admin')

@section('content')

    <h3>Create Company</h3>


    <div class="panel-body">

        {!! Form::open( ['class'=>'form-horizontal', 'method'=>'post', 'route'=>['admin.partners.store']]) !!}
        @include('admin.partners.form')

        {!! Form::submit('Save')!!}
        {!! Form::close() !!}

    </div>

@stop

@section('sidebar')


@stop