<div>
    <div wire:loading style="position: absolute">
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
                            <h5 class="mb-0 fw-bold">{{ __('Cash Expenses') }}</h5>
                            <span class="small text-muted">{{ __('Manage company cash expense reports and print advance declarations') }}</span>
                        </div>
                    </div>

                    <button class="btn btn-modern btn-modern-primary btn-sm" wire:click="new()">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('New Cash Expense') }}
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                            <tr class="bg-slate-50 border-bottom">
                                <td style="padding: 4px 8px;">
                                    <input type="text"
                                           wire:model="filter.date"
                                           class="form-control form-control-sm"
                                           placeholder="Filter Date"
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>
                                <td style="padding: 4px 8px;">
                                    <input type="text"
                                           wire:model="filter.no"
                                           class="form-control form-control-sm"
                                           placeholder="Filter No."
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>
                                <td style="padding: 4px 8px;">
                                    <input type="text"
                                           wire:model="filter.name"
                                           class="form-control form-control-sm"
                                           placeholder="Filter Person"
                                           onchange="this.dispatchEvent(new InputEvent('input'))"
                                    >
                                </td>
                                <td style="padding: 4px 8px; width: 140px;" class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                                            wire:click="clearFilterForm"
                                            title="Clear Filters">
                                        <i class="fa-solid fa-xmark"></i> Clear
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <x-column-title column="date" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Date"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="no" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="No"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="name" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Person"></x-column-title>
                                </th>
                                <th class="text-end" style="width: 160px;">{{ __('Actions') }}</th>
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
                                           class="btn btn-sm btn-outline-danger py-1 px-2 me-1"
                                           title="Print / PDF">
                                            <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                        </a>
                                        <button class="btn btn-sm btn-outline-primary py-1 px-2"
                                                wire:click="openEdit({{$cashExpense->id}})"
                                                title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="@if($cashExpense->id !== $this->activeId) d-none @endif actions bg-slate-50 border-bottom">
                                    <td colspan="4" class="p-3">
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <a href="{{ route('client.cash-expenses.show', [$cashExpense->id, 'locale' => 'lv']) }}"
                                               target="_blank"
                                               class="btn btn-modern btn-modern-secondary btn-sm">
                                                <i class="fa-solid fa-file-pdf text-danger me-1"></i> {{ __('Print / Download PDF (LV)') }}
                                            </a>
                                            <a href="{{ route('client.cash-expenses.show', [$cashExpense->id, 'locale' => 'en']) }}"
                                               target="_blank"
                                               class="btn btn-modern btn-modern-secondary btn-sm">
                                                <i class="fa-solid fa-file-pdf text-danger me-1"></i> {{ __('Print / Download PDF (EN)') }}
                                            </a>
                                            <button class="btn btn-modern btn-modern-primary btn-sm"
                                                    wire:click="openEdit({{$cashExpense->id}})">
                                                <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('Edit Report') }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                        {{ __('No cash expenses found.') }}
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