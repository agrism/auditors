<div>
    <div wire:loading.delay wire:target="filter, deleteInvoice, shortcutInvoiceConfirm">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">

        @if(!$showInvoiceFom)
            <div class="card card-modern shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                            <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ __('Rēķinu saraksts') }}</h5>
                            <span class="small text-muted">{{ __('Pārvaldiet, veidojiet un filtrējiet uzņēmuma rēķinus') }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-modern btn-modern-primary btn-sm"
                                role="button"
                                wire:click="openNewInvoice">
                            <i class="fa-solid fa-plus me-1"></i> {{ __('Jauns rēķins') }}
                        </button>

                        <button class="btn btn-modern btn-modern-secondary btn-sm"
                                role="button"
                                wire:click="shortcutInvoiceOpen">
                            <i class="fa-solid fa-bolt me-1 text-warning"></i> {{ __('Ātrais ieraksts') }}
                        </button>

                        <button class="btn btn-modern btn-modern-secondary btn-sm"
                                wire:click="export"
                                data-bs-toggle="tooltip" data-bs-placement="left" title="Eksportēt">
                            <i class="fa-regular fa-file-excel text-success me-1"></i> {{ __('Eksportēt') }}
                        </button>
                    </div>
                </div>

                <!-- Modern Business Filter Bar -->
                <div class="bg-slate-50 border-bottom px-4 py-3">
                    <div class="row g-2 align-items-end">
                        <!-- Date Range Filter -->
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-regular fa-calendar text-primary-500"></i> {{ __('Datuma periods') }}
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="text"
                                       wire:model="filter.dateFrom"
                                       class="form-control form-control-sm date bg-white"
                                       readonly
                                       id="dp3"
                                       autocomplete="off"
                                       placeholder="No"
                                       onchange="this.dispatchEvent(new InputEvent('input'))">
                                <span class="input-group-text bg-white text-muted px-1.5"><i class="fa-solid fa-arrow-right" style="font-size: 0.65rem;"></i></span>
                                <input type="text"
                                       wire:model="filter.dateTo"
                                       class="form-control form-control-sm date bg-white"
                                       readonly
                                       id="dp4"
                                       autocomplete="off"
                                       placeholder="Līdz"
                                       onchange="this.dispatchEvent(new InputEvent('input'))">
                            </div>
                        </div>

                        <!-- Invoice Type Filter -->
                        <div class="col-xl-2 col-lg-3 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-file-invoice text-primary-500"></i> {{ __('Rēķina veids') }}
                            </label>
                            <select wire:model="filter.typeId" class="form-select form-select-sm bg-white">
                                <option value="">{{ __('- Visi veidi -') }}</option>
                                @foreach($invoicetypes as $type)
                                    <option value="{{$type->id}}" @if($type->id === $filter['typeId']) selected @endif>{{$type->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Structural Unit Filter -->
                        <div class="col-xl-2 col-lg-3 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-sitemap text-primary-500"></i> {{ __('Struktūrvienība') }}
                            </label>
                            <select wire:model="filter.structId" class="form-select form-select-sm bg-white">
                                <option value="">{{ __('- Visas struktūrv. -') }}</option>
                                @foreach($structuralunits as $type)
                                    <option value="{{$type->id}}" @if($type->id === $filter['structId']) selected @endif>{{$type->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Partner Filter -->
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-handshake text-primary-500"></i> {{ __('Partneris') }}
                            </label>
                            <select wire:model="filter.partnerId" class="form-select form-select-sm bg-white">
                                <option value="">{{ __('- Visi partneri -') }}</option>
                                @foreach($partners as $partner)
                                    <option value="{{$partner->id}}" @if($partner->id === $filter['partnerId']) selected @endif>{{$partner->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Description / Search -->
                        <div class="col-xl-2 col-lg-5 col-md-8">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-magnifying-glass text-primary-500"></i> {{ __('Meklēt aprakstā') }}
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="text"
                                       wire:model.debounce.500ms="filter.details"
                                       class="form-control form-control-sm bg-white"
                                       placeholder="Ievadiet tekstu...">
                            </div>
                        </div>

                        <!-- Clear Filters Button -->
                        <div class="col-xl-1 col-lg-3 col-md-4">
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary filter-btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1"
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
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                            <tr>
                                <th>
                                    <x-column-title column="number" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Numurs"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="date" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Datums"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="invoicetypename" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Veids"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="structuralunitname" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Struktūrv."></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="partnername" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Partneris"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="details_self" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Iekšējais komentārs"></x-column-title>
                                </th>
                                <th class="text-center">
                                    <x-column-title column="currency_name" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection"
                                                    title="Valūta"></x-column-title>
                                </th>
                                <th class="text-end">
                                    <x-column-title column="amount_total" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Summa"></x-column-title>
                                </th>
                                <th class="text-end" style="min-width: 170px;">{{ __('Darbības') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr class="line text-truncate {{ (preg_match('/copy/i', $invoice->number ?? '')) ? 'table-warning is-copy-invoice' : '' }}"
                                        wire:click="setActiveInvoiceId({{$invoice->id}})"
                                        style="cursor: pointer">
                                        <td id="td{{$invoice->id}}">
                                            <span class="font-monospace fw-bold text-slate-800">{{ $invoice->number }}</span>
                                        </td>
                                        <td class="text-truncate text-muted">
                                            {{ $invoice->date }}
                                        </td>
                                        <td class="text-truncate">
                                            <span class="badge bg-slate-100 text-slate-700 fw-normal">{{ $invoice->invoicetypename }}</span>
                                        </td>
                                        <td class="text-truncate text-muted">{{ $invoice->structuralunitname }}</td>
                                        <?php
                                        $partnername = str_replace(
                                            'Sabiedrība ar ierobežotu atbildību', 'SIA', $invoice->partnername
                                        );
                                        $partnername = str_replace('Akciju sabiedrība', 'A/S', $partnername);
                                        ?>
                                        <td class="text-truncate" style="max-width: 180px;">
                                            <span class="fw-medium text-slate-800">{{ $partnername }}</span>
                                        </td>
                                        <td class="text-truncate text-muted small" style="max-width: 150px;">{{ $invoice->details_self }}</td>
                                        <td class="text-center text-truncate text-muted small">{{ $invoice->currency_name }}</td>
                                        <td class="text-end text-truncate">
                                            <span class="font-monospace fw-bold text-slate-900">{{ number_format($invoice->amount_total, 2) }}</span>
                                        </td>
                                        <td class="text-end" onclick="event.stopPropagation();">
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <a href="{{ route('client.invoices.show', [$invoice->id, 'locale' => 'lv']) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-2 py-0.5 rounded-pill bg-white shadow-xs fw-medium text-decoration-none"
                                                   title="PDF LV">
                                                    <i class="fa-solid fa-file-pdf text-danger"></i> LV
                                                </a>
                                                <a href="{{ route('client.invoices.show', [$invoice->id, 'locale' => 'en']) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-2 py-0.5 rounded-pill bg-white shadow-xs fw-medium text-decoration-none"
                                                   title="PDF EN">
                                                    <i class="fa-solid fa-file-pdf text-danger"></i> EN
                                                </a>
                                                <a href="{{ route('client.invoices.xml', $invoice->id) }}"
                                                   class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 px-2 py-0.5 rounded-pill bg-white shadow-xs fw-medium text-decoration-none"
                                                   title="E-rēķins XML (Peppol BIS 3.0)">
                                                    <i class="fa-solid fa-file-code text-primary"></i> XML
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center p-1 rounded-circle shadow-xs"
                                                        wire:click="setActiveInvoiceId({{$invoice->id}}); $set('showInvoiceFom', true)"
                                                        style="width: 28px; height: 28px;"
                                                        title="Labot rēķinu">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center p-1 rounded-circle bg-white shadow-xs"
                                                        wire:click="setActiveInvoiceId({{$invoice->id}})"
                                                        style="width: 28px; height: 28px;"
                                                        title="Papildu darbības">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                                              <tr class="@if($invoice->id !== $activeInvoiceId) d-none @endif actions bg-primary-50 bg-opacity-25 border-bottom border-primary-100">
                                        <td colspan="100" class="p-3">
                                            <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 py-1">
                                                {{-- PDF LV Button --}}
                                                <a href="{{ route('client.invoices.show', [$invoice->id, 'locale' => 'lv']) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium text-decoration-none">
                                                    <i class="fa-solid fa-file-pdf text-danger"></i>
                                                    <span>PDF LV</span>
                                                </a>

                                                {{-- PDF EN Button --}}
                                                <a href="{{ route('client.invoices.show', [$invoice->id, 'locale' => 'en']) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium text-decoration-none">
                                                    <i class="fa-solid fa-file-pdf text-danger"></i>
                                                    <span>PDF EN</span>
                                                </a>

                                                {{-- XML E-Invoice Button --}}
                                                <a href="{{ route('client.invoices.xml', $invoice->id) }}"
                                                   class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium text-decoration-none"
                                                   title="E-rēķins XML (Peppol BIS 3.0)">
                                                    <i class="fa-solid fa-file-code text-primary"></i>
                                                    <span>XML (E-rēķins)</span>
                                                </a>

                                                {{-- Copy Invoice --}}
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium"
                                                        wire:click="copyInvoice"
                                                        title="Kopēt rēķinu">
                                                    <i class="fa-regular fa-copy text-primary"></i>
                                                    <span>{{ __('Kopēt') }}</span>
                                                </button>

                                                @if($invoice->is_locked)
                                                    @if(\Auth::user()->isAdmin())
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-warning d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium unlockButton1"
                                                                data-toggle="modal"
                                                                data-target="#myModalUnLock"
                                                                invoice-id="{{ $invoice->id }}"
                                                                current-invoice-href="{{ route('client.invoices.getCurrentInvoice', $invoice->id) }}"
                                                                edit-invoice-number-href="{{ route('client.invoices.updateInvoiceNumber', $invoice->id) }}"
                                                                action-url="{{ url(route('client.invoices.unlock', $invoice->id)) }}">
                                                            <i class="fa-solid fa-unlock"></i>
                                                            <span>{{ __('Atslēgt') }}</span>
                                                        </button>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary-emphasis border px-3 py-2 rounded-pill">
                                                            <i class="fa-solid fa-lock me-1"></i> {{ __('Slēgts') }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium lockButton1"
                                                            data-toggle="modal"
                                                            data-target="#myModalLock"
                                                            invoice-id="{{ $invoice->id }}"
                                                            current-invoice-href="{{ route('client.invoices.getCurrentInvoice', $invoice->id) }}"
                                                            edit-invoice-number-href="{{ route('client.invoices.updateInvoiceNumber', $invoice->id) }}"
                                                            action-url="{{ url(route('client.invoices.lock', $invoice->id)) }}">
                                                        <i class="fa-solid fa-lock"></i>
                                                        <span>{{ __('Slēgt') }}</span>
                                                    </button>

                                                    {{-- Edit Invoice --}}
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill shadow-xs fw-semibold"
                                                            wire:click="$set('showInvoiceFom', true)">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        <span>{{ __('Labot') }}</span>
                                                    </button>

                                                    {{-- Delete Invoice --}}
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-white shadow-xs fw-medium deleteButton1"
                                                            action-url="{{ url(route('client.invoices.destroy', [$invoice->id, 'method' => 'delete'])) }}"
                                                            wire:click="deleteInvoice">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                        <span>{{ __('Dzēst') }}</span>
                                                    </button>
                                                @endif
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

</div>