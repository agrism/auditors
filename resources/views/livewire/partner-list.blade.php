<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-users-line fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Partneru saraksts') }}</h5>
                        <span class="small text-muted">{{ __('Pārvaldiet uzņēmuma partnerus, klientus un piegādātājus') }}</span>
                    </div>
                </div>

                <button class="btn btn-modern btn-modern-primary btn-sm"
                        wire:click="openEdit('')"
                        data-bs-toggle="modal"
                        data-bs-target="#partnerEditModal_">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('Pievienot partneri') }}
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                        <tr class="bg-slate-50 border-bottom">
                            <td style="padding: 4px 8px;">
                                <input type="text"
                                       wire:model="filter.name"
                                       class="form-control form-control-sm"
                                       placeholder="Filtrēt pēc nosaukuma"
                                       onchange="this.dispatchEvent(new InputEvent('input'))"
                                >
                            </td>
                            <td style="padding: 4px 8px;">
                                <input type="text"
                                       wire:model="filter.address"
                                       class="form-control form-control-sm"
                                       autocomplete="off"
                                       placeholder="Filtrēt pēc adreses"
                                       onchange="this.dispatchEvent(new InputEvent('input'))"
                                >
                            </td>
                            <td style="padding: 4px 8px;">
                                <input type="text"
                                       wire:model="filter.registration_number"
                                       class="form-control form-control-sm"
                                       autocomplete="off"
                                       placeholder="Filtrēt pēc reģ. nr."
                                       onchange="this.dispatchEvent(new InputEvent('input'))"
                                >
                            </td>
                            <td style="padding: 4px 8px;" class="text-end">
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2"
                                        wire:click="clearFilterForm"
                                        title="Notīrīt filtru">
                                    <i class="fa-solid fa-xmark"></i> Notīrīt
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <x-column-title column="name" :sortColumn="$sortColumn"
                                                :sortDirection="$sortDirection" title="Nosaukums"></x-column-title>
                            </th>
                            <th>
                                <x-column-title column="address" :sortColumn="$sortColumn"
                                                :sortDirection="$sortDirection" title="Adrese"></x-column-title>
                            </th>
                            <th>
                                <x-column-title column="registration_number" :sortColumn="$sortColumn"
                                                :sortDirection="$sortDirection"
                                                title="Reģistrācijas Nr."></x-column-title>
                            </th>
                            <th style="width: 50px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($partners as $partner)
                            <tr class="line text-truncate {{ (preg_match('/copy/',$partner->id)) ? 'table-warning' : '' }}"
                                wire:click="openEdit({{$partner->id}})"
                                role="button"
                                style="cursor: pointer;">
                                <td class="text-truncate fw-medium">
                                    {{ $partner->name }}
                                </td>
                                <td class="text-truncate text-muted">
                                    {{ $partner->address }}
                                </td>
                                <td class="text-truncate">
                                    <span class="badge bg-light text-dark border">{{ $partner->registration_number }}</span>
                                </td>
                                <td class="text-end">
                                    <i class="fa-solid fa-chevron-right text-muted opacity-25"></i>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                    Nav atrasts neviens partneris.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($partners->hasPages())
                <div class="card-footer bg-white border-top py-2 d-flex justify-content-end">
                    {{ $partners->links() }}
                </div>
            @endif
        </div>
    </div>

    <x-modal id="handle_partner"
             title="{{ $active['id'] ? 'Labot' : 'Pievienot' }} partneri"
             titleClass="bg-primary text-white"
             confirmAction="savePartnerConfirm"
             cancelAction="savePartnerCancel"
             confirmActionClass="btn-primary"
             cancelActionLabel="Atcelt"
             confirmActionLabel="Saglabāt"
    >
        <div class="modal-body p-3">
            <div class="mb-3">
                <label for="" class="form-label small fw-semibold d-flex justify-content-between">
                    <span>Nosaukums</span>
                    @if($active['name'])
                        <a class="small text-primary text-decoration-none" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$active['name']}}&search%5Bwhere%5D=name" target="_blank">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Pārbaudīt firmas.lv
                        </a>
                    @endif
                </label>
                <input type="text" class="form-control @error('active.name')is-invalid @enderror"
                       placeholder="Partnera nosaukums"
                       wire:model="active.name">
                @error('active.name') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="" class="form-label small fw-semibold d-flex justify-content-between">
                        <span>Reģ. Nr.</span>
                        @if($active['regNo'])
                            <a class="small text-primary text-decoration-none" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$active['regNo']}}&search%5Bwhere%5D=code" target="_blank">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Pārbaudīt
                            </a>
                        @endif
                    </label>
                    <input type="text" class="form-control @error('active.regNo')is-invalid @enderror"
                           placeholder="Reģistrācijas Nr."
                           wire:model="active.regNo">
                    @error('active.regNo') <small class="text-danger error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-6">
                    <label for="" class="form-label small fw-semibold d-flex justify-content-between">
                        <span>PVN Nr.</span>
                        @if($active['vatNo'])
                            <?php
                            $countryCode = preg_replace('/[^A-Z]/', '', substr(trim($active['vatNo']), 0, 2));
                            if(strlen($countryCode) === 2){
                                $number = substr(trim($active['vatNo']), 2);
                                if($number){
                                ?>
                                <a class="small text-primary text-decoration-none" href="https://ec.europa.eu/taxation_customs/vies/viesquer.do?ms={{$countryCode}}&iso={{$countryCode}}&vat={{$number}}" target="_blank">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> VIES
                                </a>
                                <?php
                                }
                            }
                            ?>
                        @endif
                    </label>
                    <input type="text" class="form-control @error('active.vatNo')is-invalid @enderror"
                           placeholder="PVN Nr."
                           wire:model="active.vatNo">
                    @error('active.vatNo') <small class="text-danger error">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="" class="form-label small fw-semibold">Adrese</label>
                <input type="text" class="form-control @error('active.address')is-invalid @enderror"
                       placeholder="Juridiskā adrese"
                       wire:model.defer="active.address">
                @error('active.address') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>

            <hr class="my-3">

            <div class="row g-2 mb-3">
                <div class="col-md-8">
                    <label for="" class="form-label small fw-semibold">Banka</label>
                    <input type="text" class="form-control @error('active.bank')is-invalid @enderror"
                           placeholder="Bankas nosaukums"
                           wire:model.defer="active.bank">
                    @error('active.bank') <small class="text-danger error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label small fw-semibold">SWIFT</label>
                    <input type="text" class="form-control @error('active.swift')is-invalid @enderror"
                           placeholder="SWIFT / BIC"
                           wire:model.defer="active.swift">
                    @error('active.swift') <small class="text-danger error">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="mb-2">
                <label for="" class="form-label small fw-semibold">Bankas konts (IBAN)</label>
                <input type="text" class="form-control @error('active.accountNumber')is-invalid @enderror"
                       placeholder="LV00UNLA0000000000000"
                       wire:model.defer="active.accountNumber">
                @error('active.accountNumber') <small class="text-danger error">{{ $message }}</small>@enderror
            </div>
        </div>
    </x-modal>
</div>