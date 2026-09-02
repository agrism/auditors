@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-10">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-business-time fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Darba laika uzskaites tabele (Excel)') }}</h5>
                        <span class="small text-muted">{{ __('Ģenerējiet un lejupielādējiet darba stundu uzskaites tabeli izvēlētajam uzņēmumam') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!! Form::model('data', ['method'=>'post', 'route'=>'admin.working-hours.handle']) !!}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="year" class="form-label small fw-semibold text-muted mb-1">{{ __('Gads') }} *</label>
                        {!! Form::select('year', $years, $selectedYear, ['class' => 'form-select form-select-sm', 'id' => 'year']) !!}
                    </div>

                    <div class="col-md-6">
                        <label for="month" class="form-label small fw-semibold text-muted mb-1">{{ __('Mēnesis') }} *</label>
                        {!! Form::select('month', $months, $selectedMonth, ['class' => 'form-select form-select-sm', 'id' => 'month']) !!}
                    </div>

                    <div class="col-md-12">
                        <label for="company_id" class="form-label small fw-semibold text-muted mb-1">{{ __('Uzņēmums') }} *</label>
                        {!! Form::select('company_id', $companies->pluck('title', 'id'), isset($data['company_id']) ? $data['company_id'] : null, ['class'=>'form-select form-select-sm', 'id'=>'company_id']) !!}
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-file-excel me-1"></i> {{ __('Lejupielādēt Excel tabeli') }}
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

