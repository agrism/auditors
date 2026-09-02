@extends('admin.layout.admin')

@section('content')
    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0 my-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-id-badge fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Sistēmas lietotāju lomas') }}</h5>
                        <span class="small text-muted">{{ __('Pārvaldiet sistēmas lomas un to piesaistītās tiesības') }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.roles.create') }}" class="btn btn-modern btn-modern-primary btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('Jauna loma') }}
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>{{ __('Lomas kods') }}</th>
                            <th>{{ __('Nosaukums / Apraksts') }}</th>
                            <th class="text-end" style="width: 170px;">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($roles as $role)
                            <tr class="line text-truncate">
                                <td class="text-muted font-monospace small">#{{ $role->id }}</td>
                                <td>
                                    <span class="badge bg-primary-50 text-primary-700 border border-primary-100 font-monospace">{{ $role->name }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-slate-800">{{ $role->label ?: '-' }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('admin.roles.permissions.show', $role->id) }}"
                                           class="btn btn-sm btn-outline-info d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           title="{{ __('Tiesību piesaiste') }}">
                                            <i class="fa-solid fa-key" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                                           class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           title="{{ __('Labot lomu') }}">
                                            <i class="fa-solid fa-pen-to-square" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.destroy', [$role->id, 'method' => 'delete']) }}"
                                           class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                           style="width: 28px; height: 28px;"
                                           onclick="return confirm('Vai tiešām vēlaties dzēst šo lomu?');"
                                           title="{{ __('Dzēst lomu') }}">
                                            <i class="fa-solid fa-trash-can" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    {{ __('Nav reģistrēta neviena loma.') }}
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