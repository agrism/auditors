@extends('client.layout.master')

@section('content')
    <div class="col-lg-10">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-gear fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Sistēmas iestatījumi') }}</h5>
                        <span class="small text-muted">{{ __('Uzņēmuma konfigurācijas parametri un vērtības') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                {!! Form::open(['route'=> 'client.companies.settings.store']) !!}
                <div class="table-responsive mb-3">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 260px;">{{ __('Parametrs') }}</th>
                            <th>{{ __('Vērtība') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($settings as $setting)
                            <tr>
                                <td class="text-muted font-monospace small">{{ $setting->id ?? '-' }}</td>
                                <td>
                                    <span class="font-monospace fw-semibold text-slate-800">{{ $setting->variable }}</span>
                                </td>
                                <td>
                                    {!! Form::text($setting->variable, $setting->content, ['class' => 'form-control form-control-sm font-monospace']) !!}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    {{ __('Nav iestatījumu ierakstu.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-check me-1"></i> {{ __('Saglabāt iestatījumus') }}
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop