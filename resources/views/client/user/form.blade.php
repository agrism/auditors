<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label small fw-semibold text-muted mb-1">{{ __('Vārds Uzvārds') }} *</label>
        {!! Form::text('name', isset($user) ? $user['name'] : null , ['class'=>'form-control form-control-sm', 'id'=>'name', 'placeholder'=>'Ievadiet lietotāja vārdu'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label small fw-semibold text-muted mb-1">{{ __('E-pasta adrese') }} *</label>
        {!! Form::text('email', isset($user) ? $user['email'] : null , ['class'=>'form-control form-control-sm', 'id'=>'email', 'placeholder'=>'vards@uznemums.lv'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label small fw-semibold text-muted mb-1">{{ __('Jaunā parole') }}</label>
        {!! Form::password('password', ['class'=>'form-control form-control-sm', 'id'=>'password', 'placeholder'=>'Atstājiet tukšu, lai nemainītu'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="password_repeat" class="form-label small fw-semibold text-muted mb-1">{{ __('Paroles atkārtojums') }}</label>
        {!! Form::password('password_repeat', ['class'=>'form-control form-control-sm', 'id'=>'password_repeat', 'placeholder'=>'Atkārtojiet jauno paroli'] ) !!}
    </div>
</div>