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
        <label for="title" class="form-label small fw-semibold text-muted mb-1">{{ __('Nosaukums') }} *</label>
        {!! Form::text('title', isset($company) ? $company['title'] : null, ['class'=>'form-control form-control-sm', 'placeholder'=>'Piem., Mans Uzņēmums SIA', 'id'=>'title'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="registration_number" class="form-label small fw-semibold text-muted mb-1">{{ __('Reģistrācijas Nr.') }}</label>
        {!! Form::text('registration_number', isset($company) ? $company['registration_number'] : null, ['class'=>'form-control form-control-sm font-monospace', 'placeholder'=>'40000000000', 'id'=>'registration_number'] ) !!}
    </div>

    <div class="col-md-6">
        <label for="dp" class="form-label small fw-semibold text-muted mb-1">{{ __('Slēgto datu datums') }}</label>
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white text-muted"><i class="fa-regular fa-calendar"></i></span>
            {!! Form::text('closed_data_date', isset($company) ? $company['closed_data_date'] : null, ['class'=>'form-control form-control-sm', 'placeholder'=>'DD.MM.YYYY', 'id'=>'dp', 'readonly'] ) !!}
        </div>
        <div class="form-text small text-muted">{{ __('Rēķini pirms šī datuma būs slēgti rediģēšanai.') }}</div>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function () {
            $('#dp').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });
        });
    </script>
@endsection