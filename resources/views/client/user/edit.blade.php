@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Edit user</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('user', ['class'=>'form-horizontal', 'method'=>'put', 'route'=>['client.user.update', $user->id]]) !!}
            @include('client.user.form')

            {!! Form::submit('Update')!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop