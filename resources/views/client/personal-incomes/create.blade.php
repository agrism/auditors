@extends('client.layout.master')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{_('Create Personal Income')}}</h3>
        </div>
        <div class="panel-body">

            {!! Form::model('personalIncome', ['class'=>'form-horizontal', 'method'=>'post', 'action'=>'Client\PersonalIncomeController@store']) !!}
            @include('client.personal-incomes.form')

            {!! Form::submit('Create')!!}
            {!! Form::close() !!}

        </div>
    </div>
@stop