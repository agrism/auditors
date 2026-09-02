<div>
    <div wire:loading.delay wire:target="filter, new, openEdit, sortBy" style="position: absolute; z-index: 10;">
        <x-loading loading="true"></x-loading>
    </div>

    @if(!$this->isEditMode())
        <div class="col-lg-12">
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
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-regular fa-calendar text-primary-500"></i> {{ __('Datums') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.date"
                                   class="form-control form-control-sm bg-white"
                                   placeholder="Piem., 2026-09...">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-hashtag text-primary-500"></i> {{ __('Dokumenta Nr.') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.no"
                                   class="form-control form-control-sm bg-white font-monospace"
                                   placeholder="Piem., 01/2026...">
                        </div>
                        <div class="col-lg-5 col-md-8">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-user text-primary-500"></i> {{ __('Persona / Saņēmējs') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.name"
                                   class="form-control form-control-sm bg-white"
                                   placeholder="Meklēt pēc personas vārda...">
                        </div>
                        <div class="col-lg-1 col-md-4">
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary w-100 d-inline-flex align-items-center justify-content-center gap-1"
                                    wire:click="clearFilterForm"
                                    title="Notīrīt filtru"
                                    style="height: 31px;">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>{{ __('Notīrīt') }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                            <tr>
                                <th>
                                    <x-column-title column="date" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Datums"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="no" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Numurs"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="name" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Persona"></x-column-title>
                                </th>
                                <th class="text-end" style="width: 160px;">{{ __('Darbības') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($cashExpenses as $cashExpense)
                                <tr class="line text-truncate {{ (preg_match('/copy/', $cashExpense->id)) ? 'table-warning' : '' }}"
                                    wire:click="setActive({{$cashExpense->id}})"
                                    style="cursor: pointer;">
                                    <td class="text-truncate fw-medium">
                                        {{ $cashExpense->date }}
                                    </td>
                                    <td class="text-truncate">
                                        <span class="badge bg-light text-dark border">{{ $cashExpense->no }}</span>
                                    </td>
                                    <td class="text-truncate">
                                        {{ $cashExpense->name }}
                                    </td>
                                    <td class="text-end" onclick="event.stopPropagation();">
                                        <a href="{{ route('client.cash-expenses.show', [$cashExpense->id, 'locale' => 'lv']) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill bg-white shadow-xs fw-medium text-decoration-none me-1"
                                           title="PDF (LV)">
                                            <i class="fa-solid fa-file-pdf text-danger"></i> PDF
                                        </a>
                                        <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 px-2.5 py-1 rounded-pill shadow-xs fw-semibold"
                                                wire:click="openEdit({{$cashExpense->id}})"
                                                title="Labot">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="@if($cashExpense->id !== $this->activeId) d-none @endif actions bg-primary-50 bg-opacity-25 border-bottom border-primary-100">
                                    <td colspan="4" class="p-3">
                                        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 py-1">
                                            <a href="{{ route('client.cash-expenses.show', [$cashExpense->id, 'locale' => 'lv']) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium text-decoration-none">
                                                <i class="fa-solid fa-file-pdf text-danger"></i>
                                                <span>{{ __('PDF LV') }}</span>
                                            </a>
                                            <a href="{{ route('client.cash-expenses.show', [$cashExpense->id, 'locale' => 'en']) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium text-decoration-none">
                                                <i class="fa-solid fa-file-pdf text-danger"></i>
                                                <span>{{ __('PDF EN') }}</span>
                                            </a>
                                            <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill shadow-xs fw-semibold"
                                                    wire:click="openEdit({{$cashExpense->id}})">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>{{ __('Labot norēķinu') }}</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
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