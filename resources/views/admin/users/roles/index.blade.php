@extends('layouts.admin')

@section('content')

    <h1>Users Roles for user: {{ $user->name }} </h1>

    {!! Form::open(['route'=>'admin.users.assignrole.save','method'=>'get']) !!}
    {!! Form::hidden('partner_id', $partnerId) !!}
    {!! Form::hidden('user_id', $user->id) !!}
    <ul>
        @foreach($allRoles as $role)
            <li>{!! Form::checkbox('role[]', $role->id, ($roles->contains('id', $role->id)) ? true : false   ) !!} {{ $role->name }}</li>
        @endforeach
    </ul>
    {!! Form::submit('Submit') !!}
    {!! Form::close() !!}
@stop

@section('sidebar')


@stop