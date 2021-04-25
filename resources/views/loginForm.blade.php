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
	<?php /*
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
    */ ?>

    <!-- s -->
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="" style="position:relative; height: 100vh;">

                <div class="card o-hidden border-0 shadow-lg p-3 mb-5 rounded" style="position: absolute;top: 50%;left:50%;transform: translate(-50%,-50%);width: 400px">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
{{--                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>--}}
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Sign in</h1>
                                    </div>
                                    <form class="user" method="post" action="/sign-in">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- f -->



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