@extends('client.layout.master')

@section('navigation')

@stop

@section('content')


	<?php /*
<form method="POST" action="/auth/login">
  {!! csrf_field() !!}


  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::label('email', 'Email') !!}
        {!! Form::text('email', old('email'), ['class' => 'form-control']) !!}
        {{ $errors->first('email', '<p class="help-block">:message</p>') }}
  </div>


  <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::label('password', 'Password') !!}
        {!! Form::text('password', Null, ['class' => 'form-control']) !!}
        {{ $errors->first('password', '<p class="help-block">:message</p>') }}
  </div>



  <div>
    <button type="submit">Login</button>
  </div>
</form>

*/?>


    <!-- Button -->
    <div class="form-group col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <label class="col-md-12 control-label" for="singlebutton"></label>
            <div class="col-md-12 center-block btn-group-vertical">
                <a class="btn btn-block btn-social btn-facebook" href="{!! \URL::route('login.facebook') !!}">
                    <i class="fa fa-facebook"></i> Sign in with Facebook
                </a>
            </div>
        </div>
    </div>

    <!-- Button -->
    {{--<div class="form-group col-md-12">--}}
    {{--    <div class="col-md-4"></div>--}}
    {{--    <div class="col-md-4">--}}
    {{--        <label class="col-md-12 control-label" for="singlebutton"></label>--}}
    {{--        <div class="col-md-12 center-block btn-group-vertical">--}}
    {{--            <a class="btn btn-block btn-social btn-linkedin" href="{!! \URL::route('login.linkedin') !!}">--}}
    {{--                <i class="fa fa-linkedin"></i> Sign in with LinkedIn--}}
    {{--            </a>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}

    <!-- Button -->
    {{--<div class="form-group col-md-12">--}}
    {{--    <div class="col-md-4"></div>--}}
    {{--    <div class="col-md-4">--}}
    {{--        <label class="col-md-12 control-label" for="singlebutton"></label>--}}
    {{--        <div class="col-md-12 center-block btn-group-vertical">--}}
    {{--            <a class="btn btn-block btn-social btn-twitter" href="{!! \URL::route('login.twitter') !!}">--}}
    {{--                <i class="fa fa-twitter"></i> Sign in with Twitter--}}
    {{--            </a>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}

    <!-- Button -->
    {{--<div class="form-group col-md-12">--}}
    {{--    <div class="col-md-4"></div>--}}
    {{--    <div class="col-md-4">--}}
    {{--        <label class="col-md-12 control-label" for="singlebutton"></label>--}}
    {{--        <div class="col-md-12 center-block btn-group-vertical">--}}
    {{--            <a class="btn btn-block btn-social btn-google-plus" href="{!! \URL::route('login.google') !!}">--}}
    {{--                <i class="fa fa-google-plus"></i> Sign in with Google--}}
    {{--            </a>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}


@stop

@section('sidebar')
    @parent


@stop