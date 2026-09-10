<div>
    <div wire:loading.delay wire:target="filter, new, openEdit, sortBy" style="position: absolute; z-index: 10;">
        <x-loading loading="true"></x-loading>
    </div>

    @if(!$this->isEditMode())
        <div>
            <div class="card card-modern shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                            <i class="fa-solid fa-money-bill-transfer fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ __('Avansu norēķini') }}</h5>
                            <span class="small text-muted">{{ __('Pārvaldiet uzņēmuma avansu norēķinus un izdrukājiet avansa deklarācijas') }}</span>
                        </div>
                    </div>

                    <button class="btn btn-modern btn-modern-primary btn-sm" wire:click="new()">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('Jauns avansa norēķins') }}
                    </button>
                </div>

                <!-- Modern Business Filter Bar -->
                <div class="bg-slate-50 border-bottom px-4 py-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3 col-lg-3">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-regular fa-calendar text-primary-500"></i> {{ __('Datums') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.date"
                                   class="form-control form-control-sm bg-white"
                                   placeholder="Gads, mēnesis vai datums...">
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-hashtag text-primary-500"></i> {{ __('Dokumenta Nr.') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.no"
                                   class="form-control form-control-sm bg-white font-monospace"
                                   placeholder="Piem., 01/2026...">
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-user text-primary-500"></i> {{ __('Persona / Saņēmējs') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.name"
                                   class="form-control form-control-sm bg-white"
                                   placeholder="Meklēt pēc personas vārda...">
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary filter-btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5"
                                    wire:click="clearFilterForm"
                                    title="Notīrīt visus filtrus">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>{{ __('Notīrīt') }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0 w-100">
                            <thead>
                            <tr>
                                <th style="width: 140px; min-width: 110px;">
                                    <x-column-title column="no" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Numurs"></x-column-title>
                                </th>
                                <th class="d-none d-md-table-cell text-nowrap" style="width: 120px;">
                                    <x-column-title column="date" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Datums"></x-column-title>
                                </th>
                                <th style="min-width: 140px;">
                                    <x-column-title column="name" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Persona / Saņēmējs"></x-column-title>
                                </th>
                                <th class="d-none d-lg-table-cell text-center text-nowrap" style="width: 90px;">{{ __('Ieraksti') }}</th>
                                <th style="width: 120px; min-width: 100px;" class="text-end text-nowrap">{{ __('Kopsumma') }}</th>
                                <th class="text-end text-nowrap pe-3" style="width: 100px; min-width: 90px;">{{ __('Darbības') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($cashExpenses as $cashExpense)
                                <tr class="line {{ (preg_match('/copy/', $cashExpense->id)) ? 'table-warning' : '' }}"
                                    wire:click="openEdit({{$cashExpense->id}})"
                                    role="button"
                                    style="cursor: pointer;">
                                    <td>
                                        <span class="font-monospace fw-bold text-slate-800">{{ $cashExpense->no }}</span>
                                        <div class="d-md-none text-muted small mt-0.5">
                                            <span class="font-monospace">{{ $cashExpense->date }}</span>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell text-muted text-nowrap small">
                                        {{ $cashExpense->date }}
                                    </td>
                                    <td>
                                        <div class="fw-medium text-slate-800 text-truncate" style="max-width: 260px;" title="{{ $cashExpense->name }}">
                                            {{ $cashExpense->name ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell text-center">
                                        <span class="badge bg-slate-100 text-slate-700 rounded-pill px-2.5 py-1">
                                            {{ $cashExpense->lines_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <span class="font-monospace fw-bold text-slate-900">
                                            {{ number_format($cashExpense->total_amount ?? 0, 2) }} €
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap pe-3">
                                        <div class="dropdown eds-action-btn-group" onclick="event.stopPropagation();">
                                            <button class="btn eds-action-btn dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                <span class="d-inline-flex align-items-center gap-1">
                                                    <span>{{ __('Darbības') }}</span>
                                                </span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end eds-action-menu shadow">
                                                <li>
                                                    <a class="dropdown-item" href="#" wire:click.prevent="openEdit({{$cashExpense->id}})">
                                                        <i class="fa-solid fa-pen-to-square text-primary"></i>
                                                        <span>{{ __('Labot') }}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('client.cash-expenses.show', [$cashExpense->id, 'locale' => 'lv']) }}" target="_blank">
                                                        <i class="fa-solid fa-file-pdf text-danger"></i>
                                                        <span>PDF (LV)</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('client.cash-expenses.show', [$cashExpense->id, 'locale' => 'en']) }}" target="_blank">
                                                        <i class="fa-solid fa-file-pdf text-danger"></i>
                                                        <span>PDF (EN)</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                        {{ __('Nav atrasts neviens avansu norēķins.') }}
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($cashExpenses->hasPages())
                    <div class="card-footer bg-white border-top py-2 d-flex justify-content-end">
                        {{ $cashExpenses->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <livewire:cash-expenses.cash-expenses-form :cashExpenseId="$activeId"></livewire:cash-expenses.cash-expenses-form>
    @endif
</div>