@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-10">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-building-user fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Lietotāja uzņēmumu piesaistes') }}: <span class="text-primary-700">{{ $user->name }}</span></h5>
                        <span class="small text-muted">{{ __('Izvēlieties uzņēmumus, kuriem lietotājam ir piešķirta piekļuve') }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.users.index') }}" class="btn btn-modern btn-modern-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ uz lietotājiem') }}
                </a>
            </div>

            <div class="card-body p-0">
                {!! Form::open(['class'=>'form-horizontal', 'method'=>'put', 'route'=>['admin.users.companies.update', $user->id]]) !!}
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>{{ __('Uzņēmuma nosaukums') }}</th>
                            <th>{{ __('Reģistrācijas Nr.') }}</th>
                            <th class="text-center" style="width: 140px;">{{ __('Piekļuve') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($companies as $company)
                            <tr class="line text-truncate">
                                <td class="text-muted font-monospace small">#{{ $company->id }}</td>
                                <td>
                                    <div class="fw-semibold text-slate-800">{{ $company->title }}</div>
                                </td>
                                <td>
                                    <span class="font-monospace text-slate-700">{{ $company->registration_number ?: '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block mb-0">
                                        {!! Form::checkbox('company_id[]', $company->id, $company->users->count() > 0 ? true : false, ['class' => 'form-check-input', 'id' => 'company_'.$company->id]) !!}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    {{ __('Sistēmā nav reģistrēts neviens uzņēmums.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-top py-3 d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-modern btn-modern-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ') }}
                    </a>

                    <button type="submit" class="btn btn-modern btn-modern-primary btn-sm">
                        <i class="fa-solid fa-check me-1"></i> {{ __('Saglabāt piesaistes') }}
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop