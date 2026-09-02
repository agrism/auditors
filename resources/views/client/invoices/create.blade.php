@extends('client.layout.master')

@section('content')
    <div class="card card-modern shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
            <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold">{{ __('Jauns rēķins') }}</h5>
                <span class="small text-muted">{{ __('Izveidot jaunu rēķinu') }}</span>
            </div>
        </div>
        <div class="card-body p-4">

            {!! Form::model('partner', ['class'=>'form-horizontal form1', 'method'=>'post', 'action'=>'App\Http\Controllers\Client\InvoiceController@store']) !!}
            @include('client.invoices.form')

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <button type="submit" name="submit-name" value="Create" class="btn btn-modern btn-modern-primary px-3">
                    <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('Izveidot') }}
                </button>
                <button type="submit" name="submit-name" value="Update and return to list" class="btn btn-modern btn-success px-3">
                    <i class="fa-solid fa-check me-1"></i> {{ __('Saglabāt un atgriezties') }}
                </button>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop