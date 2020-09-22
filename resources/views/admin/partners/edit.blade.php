@extends('admin.layout.admin')

@section('content')

    <h3>Edit Company</h3>


    <div class="panel-body">

        {!! Form::model($partner, ['class'=>'form-horizontal', 'method'=>'put', 'route'=>['admin.partners.update', $partner->id]]) !!}
        @include('admin.partners.form')

        {!! Form::submit('Save')!!}
        {!! Form::close() !!}

    </div>

@stop

@section('sidebar')


@stop