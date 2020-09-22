@extends('admin.layout.admin')

@section('content')

    <h1>Partners:</h1>
    <ul>
        @foreach($partners as $partner)
            <li>{{$partner->name}} {!! link_to_route( 'admin.partners.edit', 'Edit', ['id'=>$partner->id]) !!}</li>
        @endforeach
        <li>{!! link_to_route( 'admin.partners.create', 'Create new partner') !!}</li>
    </ul>


@stop

@section('sidebar')


@stop