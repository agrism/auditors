<div class="form-group">
    {!! Form::label('name', 'Name', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', isset($user) ? $user['name'] : null , ['class'=>'form-control', 'placeholder'=>'Input title'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('email', 'e-mail', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('email', isset($user) ? $user['email'] : null , ['class'=>'form-control', 'placeholder'=>'Input email'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('password', 'password', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'password'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('password_repeat', 'repeat password', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::password('password_repeat', ['class'=>'form-control', 'placeholder'=>'password'] ) !!}
    </div>
</div>