<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label small fw-semibold text-muted mb-1">{{ __('Nosaukums / Vārds Uzvārds') }} *</label>
        {!! Form::text('name', isset($partner) ? $partner['name'] : null , ['class'=>'form-control form-control-sm', 'id'=>'name', 'placeholder'=>'Piem., SIA Partneris'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="registration_number" class="form-label small fw-semibold text-muted mb-1">{{ __('Reģistrācijas Nr. / Personas kods') }} *</label>
        {!! Form::text('registration_number', isset($partner) ? $partner['registration_number'] : null , ['class'=>'form-control form-control-sm font-monospace', 'id'=>'registration_number', 'placeholder'=>'40000000000'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="vat_number" class="form-label small fw-semibold text-muted mb-1">{{ __('PVN reģistrācijas Nr.') }}</label>
        {!! Form::text('vat_number', isset($partner) ? $partner['vat_number'] : null , ['class'=>'form-control form-control-sm font-monospace', 'id'=>'vat_number', 'placeholder'=>'LV40000000000'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="address" class="form-label small fw-semibold text-muted mb-1">{{ __('Juridiskā adrese') }}</label>
        {!! Form::text('address', isset($partner) ? $partner['address'] : null , ['class'=>'form-control form-control-sm', 'id'=>'address', 'placeholder'=>'Piem., Rīga, Brīvības iela 1'] ) !!}
    </div>

    <div class="col-md-4">
        <label for="bank" class="form-label small fw-semibold text-muted mb-1">{{ __('Banka') }}</label>
        {!! Form::text('bank', isset($partner) ? $partner['bank'] : null , ['class'=>'form-control form-control-sm', 'id'=>'bank', 'placeholder'=>'Piem., Swedbank AS'] ) !!}
    </div>

    <div class="col-md-4">
        <label for="swift" class="form-label small fw-semibold text-muted mb-1">{{ __('SWIFT / BIC kods') }}</label>
        {!! Form::text('swift', isset($partner) ? $partner['swift'] : null , ['class'=>'form-control form-control-sm font-monospace', 'id'=>'swift', 'placeholder'=>'HABALV22'] ) !!}
    </div>

    <div class="col-md-4">
        <label for="account_number" class="form-label small fw-semibold text-muted mb-1">{{ __('Bankas konts (IBAN)') }}</label>
        {!! Form::text('account_number', isset($partner) ? $partner['account_number'] : null , ['class'=>'form-control form-control-sm font-monospace', 'id'=>'account_number', 'placeholder'=>'LV00UNLA0000000000000'] ) !!}
    </div>
</div>