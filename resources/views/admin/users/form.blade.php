@if (count($errors) > 0)
    <div class="alert alert-danger rounded-3 mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label small fw-semibold text-muted mb-1">{{ __('Vārds, Uzvārds') }} *</label>
        {!! Form::text('name', isset($user) ? $user['name'] : null, ['class'=>'form-control form-control-sm', 'placeholder'=>'Piem., Jānis Bērziņš', 'id'=>'name'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label small fw-semibold text-muted mb-1">{{ __('E-pasts') }} *</label>
        {!! Form::text('email', isset($user) ? $user['email'] : null, ['class'=>'form-control form-control-sm font-monospace', 'placeholder'=>'lietotajs@piemers.lv', 'id'=>'email'] ) !!}
    </div>
</div>