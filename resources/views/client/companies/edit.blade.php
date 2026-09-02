@extends('client.layout.master')

@section('content')
    <div class="card card-modern shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
            <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                <i class="fa-solid fa-id-card fs-5"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold">{{ __('Uzņēmuma dati') }}</h5>
                <span class="small text-muted">{{ __('Labot uzņēmuma pamatinformāciju un rekvizītus') }}</span>
            </div>
        </div>
        <div class="card-body p-4">
            {!! Form::model('company', ['class'=>'form-horizontal form1', 'method'=>'put', 'route' => ['client.companies.update', $company->id], 'files' => true ]) !!}
            @include('client.companies.form')

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-modern btn-modern-primary px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('Saglabāt izmaiņas') }}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop