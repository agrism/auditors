<div>
    <div wire:loading.delay wire:target="close, expenseLineConfirm, expenseLineDeleteConfirm, openEdit, openDelete" style="position: absolute; z-index: 10;">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">
                            {{ __('Avansa norēķins') }}
                            @if($this->get()->no ?? null)
                                <span class="badge bg-primary-50 text-primary-700 border border-primary-100 ms-1 font-monospace">#{{ $this->get()->no }}</span>
                            @endif
                        </h5>
                        <span class="small text-muted">{{ __('Pārskatiet un rediģējiet avansa norēķina datus, čekus un izdevumu rindas') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @if($this->get()->id ?? null)
                        <a href="{{ route('client.cash-expenses.show', [$this->get()->id, 'locale' => 'lv']) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium text-decoration-none"
                           title="Drukāt PDF (LV)">
                            <i class="fa-solid fa-file-pdf text-danger"></i> {{ __('PDF LV') }}
                        </a>
                        <a href="{{ route('client.cash-expenses.show', [$this->get()->id, 'locale' => 'en']) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium text-decoration-none"
                           title="Drukāt PDF (EN)">
                            <i class="fa-solid fa-file-pdf text-danger"></i> {{ __('PDF EN') }}
                        </a>
                    @endif
                    <button class="btn btn-modern btn-modern-secondary btn-sm" wire:click="close">
                        <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ uz sarakstu') }}
                    </button>
                </div>
            </div>

            <!-- General Information Card -->
            <div class="bg-slate-50 border-bottom p-4">
                <h6 class="fw-bold text-slate-800 mb-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-info text-primary-500"></i>
                    {{ __('Galvenā informācija') }}
                </h6>
                <div class="row g-3">
                    <div class="col-lg-3 col-md-4">
                        <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                            <i class="fa-regular fa-calendar text-primary-500"></i> {{ __('Datums') }}
                        </label>
                        <input type="text"
                               class="form-control form-control-sm bg-white date @error('cashExpense.date') is-invalid @enderror"
                               placeholder="DD.MM.YYYY"
                               readonly
                               onchange="this.dispatchEvent(new InputEvent('input'))"
                               wire:model="cashExpense.date">
                        @error('cashExpense.date') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                            <i class="fa-solid fa-hashtag text-primary-500"></i> {{ __('Dokumenta Nr.') }}
                        </label>
                        <input type="text"
                               class="form-control form-control-sm bg-white font-monospace @error('cashExpense.no') is-invalid @enderror"
                               placeholder="Piem., 01/2026"
                               wire:model.lazy="cashExpense.no">
                        @error('cashExpense.no') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-lg-6 col-md-4">
                        <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                            <i class="fa-solid fa-user text-primary-500"></i> {{ __('Persona / Saņēmējs') }}
                        </label>
                        <livewire:employee-select
                                name="employee_id"
                                onchange="this.dispatchEvent(new InputEvent('input'))"
                                key="{{ now() }}"
                                :selectedEmployeeId="$cashExpense['employee_id']"
                        />
                        @error('cashExpense.employee_id') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <!-- Expense Lines Section -->
            <div class="card-body p-0">
                <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2 bg-white">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="fw-bold text-slate-800 mb-0 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-receipt text-primary-500"></i>
                            {{ __('Izdevumu dokumenti un čeki') }}
                        </h6>
                        <span class="badge bg-slate-100 text-slate-700 rounded-pill px-2.5 py-1">
                            {{ count($this->get()->lines ?? []) }} {{ __('ieraksti') }}
                        </span>
                    </div>

                    <button class="btn btn-modern btn-modern-primary btn-sm" wire:click="expenseLineOpen">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('Pievienot izdevumu') }}
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr>
                            <th style="width: 50px;">Nr.</th>
                            <th style="width: 100px;">Datums</th>
                            <th style="width: 110px;">Dok. Nr.</th>
                            <th>Partneris</th>
                            <th>Apraksts</th>
                            <th style="width: 90px;">Konts</th>
                            <th style="width: 90px;">Budžets</th>
                            <th style="width: 120px;" class="text-end">Bez PVN</th>
                            <th style="width: 100px;" class="text-end">PVN</th>
                            <th style="width: 130px;" class="text-end">Kopā ar PVN</th>
                            <th style="width: 90px;" class="text-end">{{ __('Darbības') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $total = 0.00;
                        $totalVat = 0.00;
                        $totalWithoutVat = 0.00;
                        ?>
                        @forelse($this->get()->lines ?? [] as $line)
                            <?php 
                            $total += floatval(preg_replace('/[^0-9.]/', '', $line->amount_with_vat));
                            $totalVat += floatval(preg_replace('/[^0-9.]/', '', $line->amount_vat));
                            $totalWithoutVat += floatval(preg_replace('/[^0-9.]/', '', $line->amount_without_vat));
                            ?>
                            <tr class="line text-truncate">
                                <td class="text-muted small">{{ $line->no }}</td>
                                <td class="text-muted small">{{ $line->date }}</td>
                                <td>
                                    <span class="font-monospace small fw-medium text-slate-700">{{ $line->document_no ?: '-' }}</span>
                                </td>
                                <td class="text-truncate">
                                    <div class="fw-semibold text-slate-800">{{ $line->partner_name ?: '-' }}</div>
                                    @if(!empty($line->partner_vat_number))
                                        <div class="small text-muted font-monospace" style="font-size: 0.75rem;">PVN: {{ $line->partner_vat_number }}</div>
                                    @endif
                                </td>
                                <td class="text-truncate text-muted small" style="max-width: 200px;">
                                    {{ $line->description ?: '-' }}
                                </td>
                                <td>
                                    @if(!empty($line->account_code))
                                        <span class="badge bg-slate-100 text-slate-700 font-monospace">{{ $line->account_code }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($line->budget_code))
                                        <span class="badge bg-slate-100 text-slate-700 font-monospace">{{ $line->budget_code }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-end text-truncate font-monospace text-slate-700">
                                    {{ $line->amount_without_vat }} €
                                </td>
                                <td class="text-end text-truncate font-monospace text-slate-700">
                                    {{ $line->amount_vat }} €
                                </td>
                                <td class="text-end text-truncate font-monospace fw-bold text-slate-900">
                                    {{ $line->amount_with_vat }} €
                                </td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                                style="width: 26px; height: 26px;"
                                                wire:click="openEdit({{$line->id}})"
                                                title="Labot rindu">
                                            <i class="fa-solid fa-pen-to-square" style="font-size: 0.75rem;"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center p-1 rounded-circle bg-white shadow-xs"
                                                style="width: 26px; height: 26px;"
                                                wire:click="openDelete({{$line->id}})"
                                                title="Dzēst rindu">
                                            <i class="fa-solid fa-trash-can" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    Nav pievienots neviens izdevumu dokuments.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                        @if(count($this->get()->lines ?? []) > 0)
                            <tfoot>
                            <tr class="bg-slate-50 border-top fw-semibold">
                                <td colspan="7" class="text-end text-slate-700 py-3">{{ __('KOPĀ:') }}</td>
                                <td class="text-end font-monospace text-slate-800 py-3">{{ number_format($totalWithoutVat, 2) }} €</td>
                                <td class="text-end font-monospace text-slate-800 py-3">{{ number_format($totalVat, 2) }} €</td>
                                <td class="text-end font-monospace fw-bold text-primary-700 fs-6 py-3">{{ number_format($total, 2) }} €</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top py-3 d-flex align-items-center justify-content-between">
                <button class="btn btn-modern btn-modern-secondary btn-sm" wire:click="close">
                    <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ uz sarakstu') }}
                </button>

                <button class="btn btn-modern btn-modern-primary btn-sm" wire:click="expenseLineOpen">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('Pievienot izdevumu') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Datepicker Initialization -->
    <script>
        initDatepicker('.date');
    </script>

    <!-- Delete Confirmation Modal -->
    <x-modal id="expense_line_delete"
             title="Dzēst izdevumu"
             titleClass="bg-danger text-white"
             confirmAction="expenseLineDeleteConfirm"
             cancelAction="expenseLineDeleteCancel"
             confirmActionClass="btn-danger"
             confirmActionLabel="Dzēst"
             cancelActionLabel="Atcelt"
    >
        <div class="p-2 text-center">
            <i class="fa-solid fa-triangle-exclamation text-danger fs-1 mb-3"></i>
            <p class="mb-0 fw-medium text-slate-800">{{ __('Vai tiešām vēlaties dzēst šo izdevumu rindu?') }}</p>
            <span class="small text-muted">{{ __('Šī darbība ir neatgriezeniska.') }}</span>
        </div>
    </x-modal>

    <!-- Expense Line Add / Edit Modal -->
    <x-modal id="expense_line"
             title="{{ $expenseLine['id'] ? 'Labot' : 'Pievienot' }} izdevumu"
             titleClass="bg-primary text-white"
             confirmAction="expenseLineConfirm"
             cancelAction="expenseLineCancel"
             confirmActionClass="btn-primary"
             confirmActionLabel="{{ $expenseLine['id'] ? 'Saglabāt' : 'Pievienot' }}"
             cancelActionLabel="Atcelt"
    >
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Kārtas Nr.') }}</label>
                <input type="text"
                       class="form-control form-control-sm @error('expenseLine.no') is-invalid @enderror"
                       placeholder="Nr."
                       wire:model.defer="expenseLine.no">
                @error('expenseLine.no') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Datums') }}</label>
                <input type="text"
                       class="date form-control form-control-sm @error('expenseLine.date') is-invalid @enderror"
                       readonly
                       placeholder="DD.MM.YYYY"
                       onchange="this.dispatchEvent(new InputEvent('input'))"
                       wire:model="expenseLine.date">
                @error('expenseLine.date') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Dokumenta Nr.') }}</label>
                <input type="text"
                       class="form-control form-control-sm font-monospace @error('expenseLine.document_no') is-invalid @enderror"
                       placeholder="Dok. Nr."
                       wire:model.defer="expenseLine.document_no">
                @error('expenseLine.document_no') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold text-muted mb-1">{{ __('Partneris') }}</label>
            <livewire:partner-select
                    key="{{ now() }}"
                    :selectedPartnerId="$expenseLine['partner_id']"
            ></livewire:partner-select>
            @error('expenseLine.partner_id') <small class="text-danger error">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold text-muted mb-1">{{ __('Izdevuma apraksts') }}</label>
            <input type="text"
                   class="form-control form-control-sm @error('expenseLine.description') is-invalid @enderror"
                   placeholder="Piem., Biroja preces, degviela..."
                   onchange="this.dispatchEvent(new InputEvent('input'))"
                   wire:model.defer="expenseLine.description">
            @error('expenseLine.description') <small class="text-danger error">{{ $message }}</small>@enderror
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Summa ar PVN') }}</label>
                <input type="number" step="0.01"
                       class="form-control form-control-sm font-monospace fw-bold @error('expenseLine.amount_with_vat') is-invalid @enderror"
                       placeholder="0.00"
                       onchange="this.dispatchEvent(new InputEvent('input'))"
                       wire:model.lazy="expenseLine.amount_with_vat">
                @error('expenseLine.amount_with_vat') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Budžets') }}</label>
                <livewire:budget-select
                        key="{{ now() }}"
                        :selectedBudgetId="$expenseLine['budget_id']"
                ></livewire:budget-select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Konts') }}</label>
                <livewire:account-select
                        key="{{ now() }}"
                        :selectedAccountId="$expenseLine['account_id']"
                ></livewire:account-select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('PVN formula') }}</label>
                <select class="form-select form-select-sm" key="{{ now() }}" wire:model="expenseLine.vat_calculator_name">
                    @foreach(\App\Services\VatCalculator::factory(100)->getCalculator() as $calcKey => $calcVal)
                        <option value="{{$calcVal}}">{{$calcVal}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Calculated Live VAT Box -->
        <div class="bg-slate-50 border rounded-3 p-3 mt-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small">{{ __('Summa bez PVN:') }}</span>
                <span class="font-monospace fw-medium text-slate-800">{{ number_format(floatval($expenseLine['amount_without_vat']), 2) }} €</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small">{{ __('PVN summa:') }}</span>
                <span class="font-monospace fw-medium text-slate-800">{{ number_format(floatval($expenseLine['amount_vat']), 2) }} €</span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                <span class="fw-bold text-slate-900">{{ __('Kopā ar PVN:') }}</span>
                <span class="font-monospace fw-bold text-primary-700 fs-6">{{ number_format(floatval($expenseLine['amount_with_vat']), 2) }} €</span>
        </div>
    </x-modal>
</div>