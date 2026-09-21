<div>
    <div wire:loading.delay wire:target="filter, deleteInvoice, shortcutInvoiceConfirm">
        <x-loading loading="true"></x-loading>
    </div>

    <div>

        @if(!$showInvoiceFom)
            <!-- EDS Document Action Toolbar -->
            <!-- Document Action Toolbar -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button class="btn eds-btn-primary"
                            role="button"
                            wire:click="openNewInvoice">
                        <i class="fa-solid fa-plus me-1"></i>
                        <span>Izveidot jaunu rēķinu</span>
                    </button>

                    <button class="btn eds-btn-outline"
                            role="button"
                            wire:click="shortcutInvoiceOpen">
                        <i class="fa-solid fa-bolt me-1 text-warning"></i>
                        <span>Ātrais rēķins</span>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary"
                            wire:click="export"
                            data-bs-toggle="tooltip" data-bs-placement="left" title="Eksportēt uz Excel">
                        <i class="fa-regular fa-file-excel text-success me-1"></i> {{ __('Eksportēt') }}
                    </button>
                </div>
            </div>

            <div class="card card-modern shadow-sm border-0 mb-3">
                <!-- EDS Filter Container -->
                <div class="eds-filter-container bg-white border-bottom p-3 p-lg-4" id="edsFilterCollapse">
                    <div class="row g-3">
                        <!-- Col 1: Veids -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <label class="eds-filter-label">{{ __('Veids') }}</label>
                            <select wire:model="filter.typeId" class="form-select eds-filter-control">
                                <option value=""></option>
                                @foreach($invoicetypes as $type)
                                    <option value="{{$type->id}}" @if($type->id === $filter['typeId']) selected @endif>{{$type->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Col 2: Izveidošanas periods no ... līdz ... -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <label class="eds-filter-label">{{ __('Izveidošanas periods no') }}</label>
                            <div class="eds-date-range">
                                <input type="text"
                                       wire:model="filter.dateFrom"
                                       class="form-control eds-filter-control date bg-white"
                                       readonly
                                       id="dp3"
                                       autocomplete="off"
                                       onchange="this.dispatchEvent(new InputEvent('input'))">
                                <span class="eds-date-sep">{{ __('līdz') }}</span>
                                <input type="text"
                                       wire:model="filter.dateTo"
                                       class="form-control eds-filter-control date bg-white"
                                       readonly
                                       id="dp4"
                                       autocomplete="off"
                                       onchange="this.dispatchEvent(new InputEvent('input'))">
                            </div>
                        </div>

                        <!-- Col 3: Numurs / Meklēt aprakstā -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <label class="eds-filter-label">{{ __('Numurs / Apraksts') }}</label>
                            <input type="text"
                                   wire:model.debounce.500ms="filter.details"
                                   class="form-control eds-filter-control">
                        </div>

                        <!-- Col 4: Partneris -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <label class="eds-filter-label">{{ __('Partneris') }}</label>
                            <select wire:model="filter.partnerId" class="form-select eds-filter-control">
                                <option value=""></option>
                                @foreach($partners as $partner)
                                    <option value="{{$partner->id}}" @if($partner->id === $filter['partnerId']) selected @endif>{{$partner->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Row 2 Col 1: Struktūrvienība -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <label class="eds-filter-label">{{ __('Struktūrvienība') }}</label>
                            <select wire:model="filter.structId" class="form-select eds-filter-control">
                                <option value=""></option>
                                @foreach($structuralunits as $type)
                                    <option value="{{$type->id}}" @if($type->id === $filter['structId']) selected @endif>{{$type->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="eds-filter-actions">
                        <button type="button"
                                class="btn eds-filter-btn"
                                wire:click="filterForm">
                            {{ __('Meklēt') }}
                        </button>
                        <a href="javascript:void(0)"
                           class="eds-filter-clear"
                           wire:click="clearFilterForm">
                            {{ __('Notīrīt') }}
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0 w-100">
                            <thead>
                            <tr>
                                <th style="min-width: 110px; max-width: 200px;">
                                    <x-column-title column="number" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Numurs"></x-column-title>
                                </th>
                                <th class="text-nowrap" style="width: 105px; min-width: 95px;">
                                    <x-column-title column="date" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Datums"></x-column-title>
                                </th>
                                <th class="d-none d-xl-table-cell text-center text-nowrap" style="width: 85px;">
                                    <x-column-title column="invoicetypename" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Veids"></x-column-title>
                                </th>
                                <th class="d-none d-xxl-table-cell text-nowrap" style="max-width: 130px;">
                                    <x-column-title column="structuralunitname" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Struktūrv."></x-column-title>
                                </th>
                                <th style="min-width: 140px; max-width: 240px;">
                                    <x-column-title column="partnername" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Partneris"></x-column-title>
                                </th>
                                <th class="d-none d-xxl-table-cell" style="max-width: 140px;">
                                    <x-column-title column="details_self" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Iekšējais komentārs"></x-column-title>
                                </th>
                                <th class="d-none d-xxl-table-cell text-center" style="width: 60px;">
                                    <x-column-title column="currency_name" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Valūta"></x-column-title>
                                </th>
                                <th class="text-end text-nowrap" style="width: 125px; min-width: 100px;">
                                    <x-column-title column="amount_total" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Summa"></x-column-title>
                                </th>
                                <th class="text-end text-nowrap pe-3" style="width: 95px; min-width: 90px;">{{ __('Darbības') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr class="line {{ (preg_match('/copy/i', $invoice->number ?? '')) ? 'table-warning is-copy-invoice' : '' }}">
                                        <td id="td{{$invoice->id}}" style="max-width: 170px;">
                                            <div class="d-flex align-items-center justify-content-between gap-1" style="max-width: 170px;">
                                                <span class="fw-bold text-slate-800 text-truncate" style="max-width: 150px;" title="{{ $invoice->number }}">
                                                    {{ \Illuminate\Support\Str::limit($invoice->number, 18, '...') }}
                                                </span>
                                                @if($invoice->is_locked)
                                                    <i class="fa-solid fa-lock text-warning flex-shrink-0" title="{{ __('Slēgts rēķins') }}" data-bs-toggle="tooltip" style="font-size: 0.8rem; cursor: help;"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-nowrap text-muted small">
                                            {{ $invoice->date }}
                                        </td>
                                        <td class="d-none d-xl-table-cell text-center text-truncate">
                                            <span class="badge bg-slate-100 text-slate-700 fw-normal">{{ $invoice->invoicetypename }}</span>
                                        </td>
                                        <td class="d-none d-xxl-table-cell text-truncate text-muted small" style="max-width: 130px;" title="{{ $invoice->structuralunitname }}">{{ $invoice->structuralunitname }}</td>
                                        <?php
                                        $partnername = html_entity_decode((string)$invoice->partnername, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                        $partnername = str_replace(
                                            'Sabiedrība ar ierobežotu atbildību', 'SIA', $partnername
                                        );
                                        $partnername = str_replace('Akciju sabiedrība', 'A/S', $partnername);
                                        ?>
                                        <td class="text-truncate" style="max-width: 240px;" title="{{ $partnername }}">
                                            <span class="fw-medium text-slate-800">{{ $partnername }}</span>
                                        </td>
                                        <td class="d-none d-xxl-table-cell text-truncate text-muted small" style="max-width: 140px;" title="{{ $invoice->details_self }}">{{ $invoice->details_self }}</td>
                                        <td class="d-none d-xxl-table-cell text-center text-muted small">{{ $invoice->currency_name }}</td>
                                        <td class="text-end text-nowrap">
                                            <span class="fw-bold text-slate-900">{{ number_format($invoice->amount_total, 2, '.', ' ') }}</span>
                                            <span class="d-inline text-muted small ms-0.5">{{ $invoice->currency_name ?? 'EUR' }}</span>
                                        </td>
                                        <td class="text-end text-nowrap pe-3">
                                            <div class="dropdown eds-action-btn-group">
                                                <button class="btn eds-action-btn dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    <span>Darbības</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end eds-action-menu shadow">
                                                    @if(!$invoice->is_locked)
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)" wire:click.prevent="editInvoice({{$invoice->id}})">
                                                                <i class="fa-solid fa-pen-to-square text-primary"></i>
                                                                <span>Labot</span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0)" wire:click.prevent="copyInvoiceById({{$invoice->id}})">
                                                            <i class="fa-regular fa-copy text-primary"></i>
                                                            <span>Kopēt</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('client.invoices.show', [$invoice->id, 'locale' => 'lv']) }}" target="_blank">
                                                            <i class="fa-solid fa-file-pdf text-danger"></i>
                                                            <span>PDF (LV)</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('client.invoices.show', [$invoice->id, 'locale' => 'en']) }}" target="_blank">
                                                            <i class="fa-solid fa-file-pdf text-danger"></i>
                                                            <span>PDF (EN)</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('client.invoices.xml', $invoice->id) }}">
                                                            <i class="fa-solid fa-file-code text-primary"></i>
                                                            <span>Saglabāt XML</span>
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    @if($invoice->is_locked)
                                                        @if(\Auth::user()->isAdmin())
                                                            <li>
                                                                <a class="dropdown-item text-warning"
                                                                   href="javascript:void(0)"
                                                                   wire:click.prevent="unlockInvoiceById({{$invoice->id}})">
                                                                    <i class="fa-solid fa-unlock"></i>
                                                                    <span>Atslēgt</span>
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <span class="dropdown-item text-muted disabled">
                                                                    <i class="fa-solid fa-lock"></i>
                                                                    <span>Slēgts</span>
                                                                </span>
                                                            </li>
                                                        @endif
                                                    @else
                                                        <li>
                                                            <a class="dropdown-item text-secondary"
                                                                href="javascript:void(0)"
                                                                wire:click.prevent="lockInvoiceById({{$invoice->id}})">
                                                                <i class="fa-solid fa-lock"></i>
                                                                <span>Slēgt</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger"
                                                                href="javascript:void(0)"
                                                                wire:click.prevent="deleteInvoiceById({{$invoice->id}})">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                                <span>Dzēst</span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $invoices->links() }}


                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
        @else
            <div>
                <livewire:invoice-form :invoiceId="$activeInvoiceId"></livewire:invoice-form>
            </div>
        @endif
    </div>

    <script>
        initDatepicker('.date');
    </script>

    <x-modal id="shortcut_invoice"
             title="Izveidot rēķinu"
             titleClass="bg-primary text-white"
             confirmAction="shortcutInvoiceConfirm"
             cancelAction="shortcutInvoiceCancel"
             confirmActionClass="btn-primary"
             confirmActionLabel="Izveidot"
             cancelActionLabel="Atcelt"
    >
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">Datums</label>
            <input type="text" class="date form-control @error('shortcutInvoice.date')is-invalid @enderror"
                   readonly
                   placeholder="Datums"
                   onchange="this.dispatchEvent(new InputEvent('input'))"
                   wire:model.defer="shortcutInvoice.date">
            @error('shortcutInvoice.date') <small class="text-danger error">{{ $message }}</small>@enderror
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">Numurs</label>
            <input type="text" class="form-control @error('shortcutInvoice.number')is-invalid @enderror"
                   placeholder="Rēķina numurs"
                   wire:model.defer="shortcutInvoice.number">
            @error('shortcutInvoice.number') <small class="text-danger error">{{ $message }}</small>@enderror
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">Struktūrvienība</label>
            <select
                    wire:model="shortcutInvoice.structId"
                    class="form-control text-end @error('shortcutInvoice.structId')is-invalid @enderror"
            >
                @foreach($structuralunits as $struct)
                    <option value="{{$struct->id ?? null}}">{{$struct->title ?? 'n/a'}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">Veids</label>
            <select
                    wire:model="shortcutInvoice.typeId"
                    class="form-control text-end @error('shortcutInvoice.typeId')is-invalid @enderror"
            >
                @foreach($invoicetypes as $type)
                    <option value="{{$type->id ?? null}}">{{$type->title ?? 'n/a'}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">Partneris</label>
            <livewire:partner-select :selectedPartnerId="$shortcutInvoice['partnerId']"></livewire:partner-select>
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">Apraksts</label>
            <input type="text" class="form-control @error('shortcutInvoice.details')is-invalid @enderror"
                   placeholder="Rēķina apraksts"
                   wire:model.defer="shortcutInvoice.details">
            @error('shortcutInvoice.details') <small class="text-danger error">{{ $message }}</small>@enderror
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">Summa bez PVN</label>
            <input type="number" step="0.01" class="form-control @error('shortcutInvoice.amountWithoutVat')is-invalid @enderror"
                   placeholder="0.00"
                   wire:model="shortcutInvoice.amountWithoutVat">
            @error('shortcutInvoice.amountWithoutVat') <small class="text-danger error">{{ $message }}</small>@enderror
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">PVN likme</label>
            <select
                    wire:model="shortcutInvoice.vatId"
                    class="form-control text-end @error('shortcutInvoice.vatId')is-invalid @enderror"
            >
                @foreach($shortcutInvoice['vatRates'] as $vatRate)
                    <option value="{{$vatRate['id']}}">{{$vatRate['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">PVN summa</label>
            <input type="text" class="form-control @error('shortcutInvoice.amountVat')is-invalid @enderror"
                   readonly
                   placeholder="0.00"
                   wire:model="shortcutInvoice.amountVat">
            @error('shortcutInvoice.amountVat') <small class="text-danger error">{{ $message }}</small>@enderror
        </div>
        <div class="mb-2">
            <label for="" class="form-label small fw-semibold">Summa ar PVN</label>
            <input type="number" step="0.01" class="form-control @error('shortcutInvoice.amountWithVat')is-invalid @enderror"
                   placeholder="0.00"
                   wire:model="shortcutInvoice.amountWithVat">
            @error('shortcutInvoice.amountWithVat') <small class="text-danger error">{{ $message }}</small>@enderror
        </div>
    </x-modal>


    <x-modal id="delete_invoice"
             title="Brīdinājums"
             titleClass="bg-danger text-white"
             confirmAction="deleteInvoiceConfirm"
             cancelAction="deleteInvoiceCancel"
             confirmActionClass="btn-danger"
             confirmActionLabel="Dzēst"
             cancelActionLabel="Atcelt"
    >
        Vai tiešām vēlaties dzēst rēķinu Nr.: <strong>{{$activeInvoiceNo}}</strong>?
    </x-modal>

    <x-modal id="copy_invoice"
             title="Kopēt rēķinu"
             titleClass="bg-primary text-white"
             confirmAction="copyInvoiceConfirm"
             cancelAction="copyInvoiceCancel"
             confirmActionClass="btn-primary"
             confirmActionLabel="Kopēt"
             cancelActionLabel="Atcelt"
    >
        Vai vēlaties izveidot kopiju rēķinam Nr.: <strong>{{$activeInvoiceNo}}</strong>?
    </x-modal>

    <x-modal id="lock_invoice"
             title="Slēgt rēķinu"
             titleClass="bg-primary text-white"
             confirmAction="lockInvoiceConfirm"
             cancelAction="lockInvoiceCancel"
             confirmActionClass="btn-primary"
             confirmActionLabel="Slēgt"
             cancelActionLabel="Atcelt"
    >
        Vai tiešām vēlaties slēgt rēķinu Nr.: <strong>{{$activeInvoiceNo}}</strong>?<br>
        <span class="small text-muted">Pēc slēgšanas rēķinu vairs nevarēs labot vai dzēst.</span>
    </x-modal>

    <x-modal id="unlock_invoice"
             title="Atslēgt rēķinu"
             titleClass="bg-primary text-white"
             confirmAction="unlockInvoiceConfirm"
             cancelAction="unlockInvoiceCancel"
             confirmActionClass="btn-primary"
             confirmActionLabel="Atslēgt"
             cancelActionLabel="Atcelt"
    >
        Vai tiešām vēlaties atslēgt rēķinu Nr.: <strong>{{$activeInvoiceNo}}</strong>?<br>
        <span class="small text-muted">Pēc atslēgšanas rēķinu atkal būs iespējams labot un dzēst.</span>
    </x-modal>

</div>