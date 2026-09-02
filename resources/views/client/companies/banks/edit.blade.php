@extends('client.layout.master')

@section('content')
    <div class="col-lg-10">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-building-columns fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Labot maksājumu saņēmēja datus') }}</h5>
                        <span class="small text-muted">{{ __('Rediģējiet bankas rekvizītus un konta informāciju') }}</span>
                    </div>
                </div>

                <a href="{{ route('client.companies.bank.index') }}" class="btn btn-modern btn-modern-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ') }}
                </a>
            </div>

            <div class="card-body p-4">
                {!! Form::model('bank', ['class'=>'form-horizontal', 'method'=>'put', 'route'=>['client.companies.bank.update', $bank->id]]) !!}
                @include('client.companies.banks.form')

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('client.companies.bank.index') }}" class="btn btn-modern btn-modern-secondary btn-sm">
                        {{ __('Atcelt') }}
                    </a>
                    <button type="submit" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-check me-1"></i> {{ __('Saglabāt izmaiņas') }}
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop