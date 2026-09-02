@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-users-gear fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Sistēmas lietotāju saraksts') }}</h5>
                        <span class="small text-muted">{{ __('Pārvaldiet visus sistēmā reģistrētos lietotājus, to uzņēmumu piesaistes un piekļuves tiesības') }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.users.create') }}" class="btn btn-modern btn-modern-primary btn-sm">
                    <i class="fa-solid fa-user-plus me-1"></i> {{ __('Jauns lietotājs') }}
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>{{ __('Vārds, Uzvārds') }}</th>
                            <th>{{ __('E-pasts') }}</th>
                            <th class="text-end" style="width: 170px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr class="line text-truncate">
                                <td class="text-muted font-monospace small">#{{ $user->id }}</td>
                                <td class="text-truncate">
                                    <div class="fw-semibold text-slate-800">{{ $user->name }}</div>
                                </td>
                                <td class="text-truncate">
                                    <span class="text-slate-600 font-monospace small">
                                        <i class="fa-regular fa-envelope text-slate-400 me-1"></i>{{ $user->email }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('admin.users.companies.show', $user->id) }}"
                                           class="btn btn-sm btn-outline-info d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           title="{{ __('Uzņēmumu piesaistes') }}">
                                            <i class="fa-solid fa-building" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           title="{{ __('Labot lietotāju') }}">
                                            <i class="fa-solid fa-pen-to-square" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.prepare-login-as-user', [$user->id]) }}"
                                           class="btn btn-sm btn-outline-warning d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           title="{{ __('Ieiet kā lietotājam') }}">
                                            <i class="fa-solid fa-right-to-bracket" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.users.destroy', [$user->id, 'method' => 'delete']) }}"
                                           class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           onclick="return confirm('Vai tiešām vēlaties dzēst šo lietotāju?');"
                                           title="{{ __('Dzēst lietotāju') }}">
                                            <i class="fa-solid fa-trash-can" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    {{ __('Nav atrasts neviens lietotājs.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop