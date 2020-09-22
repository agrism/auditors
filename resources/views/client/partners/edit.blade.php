@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Edit Partner</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('partner', ['class'=>'form-horizontal', 'method'=>'put', 'route'=>['client.partners.update', $partner->id]]) !!}
            @include('client.partners.form')

            {!! Form::submit('Update')!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop