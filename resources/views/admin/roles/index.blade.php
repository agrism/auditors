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
                            <th class="text-end" style="width: 140px;">{{ __('Darbības') }}</th>
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
                                    <div class="dropdown eds-action-btn-group">
                                        <button class="btn eds-action-btn dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{ __('Darbības') }}</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end eds-action-menu shadow">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.roles.edit', $role->id) }}">
                                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                                    <span>{{ __('Labot lomu') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.roles.permissions.show', $role->id) }}">
                                                    <i class="fa-solid fa-key text-info"></i>
                                                    <span>{{ __('Tiesību piesaiste') }}</span>
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <a class="dropdown-item text-danger"
                                                   href="{{ route('admin.roles.destroy', [$role->id, 'method' => 'delete']) }}"
                                                   onclick="return confirm('Vai tiešām vēlaties dzēst šo lomu?');">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                    <span>{{ __('Dzēst lomu') }}</span>
                                                </a>
                                            </li>
                                        </ul>
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