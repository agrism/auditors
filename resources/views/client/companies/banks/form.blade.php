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
        <label for="payment_receiver" class="form-label small fw-semibold text-muted mb-1">{{ __('Maksājuma saņēmējs') }} *</label>
        {!! Form::text('payment_receiver', isset($bank) ? $bank['payment_receiver'] : null , ['class'=>'form-control form-control-sm', 'id'=>'payment_receiver', 'placeholder'=>'Piem., SIA Mans Uzņēmums'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="comment" class="form-label small fw-semibold text-muted mb-1">{{ __('Piezīme / Komentārs') }}</label>
        {!! Form::text('comment', isset($bank) ? $bank['comment'] : null , ['class'=>'form-control form-control-sm', 'id'=>'comment', 'placeholder'=>'Piem., Galvenais norēķinu konts'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="bank" class="form-label small fw-semibold text-muted mb-1">{{ __('Banka') }} *</label>
        {!! Form::text('bank', isset($bank) ? $bank['bank'] : null , ['class'=>'form-control form-control-sm', 'id'=>'bank', 'placeholder'=>'Piem., Swedbank AS'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="swift" class="form-label small fw-semibold text-muted mb-1">{{ __('SWIFT / BIC kods') }}</label>
        {!! Form::text('swift', isset($bank) ? $bank['swift'] : null , ['class'=>'form-control form-control-sm font-monospace', 'id'=>'swift', 'placeholder'=>'HABALV22'] ) !!}
    </div>

    <div class="col-md-12">
        <label for="account_number" class="form-label small fw-semibold text-muted mb-1">{{ __('Bankas konts (IBAN)') }} *</label>
        {!! Form::text('account_number', isset($bank) ? $bank['account_number'] : null , ['class'=>'form-control form-control-sm font-monospace', 'id'=>'account_number', 'placeholder'=>'LV00UNLA0000000000000'] ) !!}
    </div>
</div>


