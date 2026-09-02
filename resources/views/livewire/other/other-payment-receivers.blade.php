<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-building-columns fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Citi maksājumu saņēmēji') }}</h5>
                        <span class="small text-muted">{{ __('Pārvaldiet papildu maksājumu saņēmēju un pakalpojumu sniedzēju bankas rekvizītus') }}</span>
                    </div>
                </div>

                <button class="btn btn-modern btn-modern-primary btn-sm"
                        wire:click="openEdit('')"
                        data-bs-toggle="modal"
                        data-bs-target="#handle_payment_receiver">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('Pievienot saņēmēju') }}
                </button>
            </div>

                <!-- Modern Business Filter Bar -->
                <div class="bg-slate-50 border-bottom px-4 py-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-user-tag text-primary-500"></i> {{ __('Saņēmējs') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.payment_receiver"
                                   class="form-control form-control-sm bg-white"
                                   placeholder="Meklēt saņēmēju...">
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-building-columns text-primary-500"></i> {{ __('Banka') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.bank"
                                   class="form-control form-control-sm bg-white"
                                   placeholder="Bankas nosaukums...">
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-bolt text-primary-500"></i> {{ __('SWIFT') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.swift"
                                   class="form-control form-control-sm bg-white font-monospace"
                                   placeholder="SWIFT kods...">
                        </div>
                        <div class="col-xl-2 col-lg-6 col-md-6">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-money-check text-primary-500"></i> {{ __('Bankas konts') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.account_number"
                                   class="form-control form-control-sm bg-white font-monospace"
                                   placeholder="LV00...">
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-8">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-comment text-primary-500"></i> {{ __('Komentārs') }}
                            </label>
                            <input type="text"
                                   wire:model.debounce.400ms="filter.comment"
                                   class="form-control form-control-sm bg-white"
                                   placeholder="Piezīmes...">
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-4">
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary filter-btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1"
                                    wire:click="clearFilterForm"
                                    title="Notīrīt filtru">
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
                                    <x-column-title column="payment_receiver" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Saņēmējs"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="bank" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Banka"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="swift" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="SWIFT"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="account_number" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Bankas konts"></x-column-title>
                                </th>
                                <th>
                                    <x-column-title column="comment" :sortColumn="$sortColumn"
                                                    :sortDirection="$sortDirection" title="Komentārs"></x-column-title>
                                </th>
                                <th style="width: 50px;"></th>
                            </tr>
                            </thead>
                        <tbody>
                        @forelse($paymentReceivers as $receiver)
                            <tr class="line text-truncate {{ (preg_match('/copy/', $receiver->id)) ? 'table-warning' : '' }}"
                                wire:click="openEdit({{$receiver->id}})"
                                role="button"
                                style="cursor: pointer;">
                                <td class="text-truncate fw-medium">
                                    {{ $receiver->payment_receiver }}
                                </td>
                                <td class="text-truncate text-muted">
                                    {{ $receiver->bank }}
                                </td>
                                <td class="text-truncate text-muted">
                                    {{ $receiver->swift }}
                                </td>
                                <td class="text-truncate font-monospace small">
                                    {{ $receiver->account_number }}
                                </td>
                                <td class="text-truncate text-muted small">
                                    {{ $receiver->comment }}
                                </td>
                                <td class="text-end">
                                    <i class="fa-solid fa-chevron-right text-muted opacity-25"></i>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    Nav atrasts neviens maksājumu saņēmējs.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($paymentReceivers->hasPages())
                <div class="card-footer bg-white border-top py-2 d-flex justify-content-end">
                    {{ $paymentReceivers->links() }}
                </div>
            @endif
        </div>
    </div>

    <x-modal id="handle_payment_receiver"
             title="{{ $active['id'] ? 'Labot' : 'Pievienot' }} maksājumu saņēmēju"
             titleClass="bg-primary text-white"
             confirmAction="savePaymentReceiverConfirm"
             cancelAction="savePaymentReceiverCancel"
             confirmActionClass="btn-primary"
             cancelActionLabel="Atcelt"
             confirmActionLabel="Saglabāt"
    >
        <div class="modal-body p-3">
            <div class="mb-3">
                <label for="" class="form-label small fw-semibold">Saņēmēja nosaukums</label>
                <input type="text" class="form-control @error('active.payment_receiver') is-invalid @enderror"
                       placeholder="Piem., Valsts kase"
                       wire:model.lazy="active.payment_receiver">
                @error('active.payment_receiver') <small class="text-danger error">{{ $message }}</small> @enderror
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-7">
                    <label for="" class="form-label small fw-semibold">Banka</label>
                    <input type="text" class="form-control @error('active.bank') is-invalid @enderror"
                           placeholder="Bankas nosaukums"
                           wire:model.lazy="active.bank">
                    @error('active.bank') <small class="text-danger error">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-5">
                    <label for="" class="form-label small fw-semibold">SWIFT / BIC</label>
                    <input type="text" class="form-control @error('active.swift') is-invalid @enderror"
                           placeholder="SWIFT kods"
                           wire:model.lazy="active.swift">
                    @error('active.swift') <small class="text-danger error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="" class="form-label small fw-semibold">Bankas konts (IBAN)</label>
                <input type="text" class="form-control @error('active.account_number') is-invalid @enderror"
                       placeholder="LV00UNLA0000000000000"
                       wire:model.lazy.defer="active.account_number">
                @error('active.account_number') <small class="text-danger error">{{ $message }}</small> @enderror
            </div>

            <div class="mb-2">
                <label for="" class="form-label small fw-semibold">Komentārs / Piezīmes</label>
                <input type="text" class="form-control @error('active.comment') is-invalid @enderror"
                       placeholder="Piezīmes par maksājuma mērķi..."
                       wire:model.lazy.defer="active.comment">
                @error('active.comment') <small class="text-danger error">{{ $message }}</small> @enderror
            </div>
        </div>
    </x-modal>
</div>