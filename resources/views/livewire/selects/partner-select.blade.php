<div>
    <div class="input-group input-group-sm" style="width: 100%">
        <select wire:model="selectedPartnerId" class="form-control form-control-sm" name="partner_id">
            @foreach($partners ?? [] as $partner)
                <option value="{{$partner['id']}}">{{$partner['name']}}</option>
            @endforeach
        </select>
        <span id="basic-addon1"
              data-bs-toggle="modal"
              role="button"
              wire:click="edit({{ $selectedPartnerId }})">
            <div class="input-group-append">
                <span class="input-group-text fa fa-edit"></span>
            </div>
{{--            <div type="button1" class="btn btn-xs fa fa-edit" style="cursor: pointer;"></div>--}}
        </span>
    </div>


    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="partnerEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-handshake me-2"></i> {{ $selectedPartnerId > 0 ? __('Labot partnera datus') : __('Pievienot partneri') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close" wire:click="cancel()"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label small fw-semibold text-muted mb-0">{{ __('Nosaukums / Vārds Uzvārds') }} *</label>
                            @if($selectedPartnerName)
                                <a class="small text-primary text-decoration-none" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$selectedPartnerName}}&search%5Bwhere%5D=name" target="_blank">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> {{ __('firmas.lv') }}
                                </a>
                            @endif
                        </div>
                        <input type="text" class="form-control form-control-sm @error('selectedPartnerName') is-invalid @enderror"
                               placeholder="Piem., Zeme, SIA vai Bērziņš Dainis"
                               wire:model="selectedPartnerName">
                        <div class="form-text small text-muted" style="font-size: 0.75rem;">
                            <span class="text-success fw-semibold">PAREIZI:</span> Zeme, SIA vai Bērziņš Dainis | 
                            <span class="text-danger fw-semibold">NEPAREIZI:</span> SIA Zeme vai Dainis Bērziņš
                        </div>
                        @error('selectedPartnerName') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label small fw-semibold text-muted mb-0">{{ __('Reģistrācijas Nr.') }}</label>
                                @if($selectedPartnerRegNo)
                                    <a class="small text-primary text-decoration-none" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$selectedPartnerRegNo}}&search%5Bwhere%5D=code" target="_blank">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> {{ __('firmas.lv') }}
                                    </a>
                                @endif
                            </div>
                            <input type="text" class="form-control form-control-sm font-monospace @error('selectedPartnerRegNo') is-invalid @enderror"
                                   placeholder="40000000000"
                                   wire:model="selectedPartnerRegNo">
                            @error('selectedPartnerRegNo') <small class="text-danger error">{{ $message }}</small>@enderror
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label small fw-semibold text-muted mb-0">{{ __('PVN Nr.') }}</label>
                                @if($selectedPartnerVatNo)
                                    @php
                                        $countryCode = preg_replace('/[^A-Z]/', '', substr(trim($selectedPartnerVatNo), 0, 2));
                                        $number = strlen($countryCode) === 2 ? substr(trim($selectedPartnerVatNo), 2) : '';
                                    @endphp
                                    @if($number)
                                        <a class="small text-primary text-decoration-none" href="https://ec.europa.eu/taxation_customs/vies/viesquer.do?ms={{$countryCode}}&iso={{$countryCode}}&vat={{$number}}" target="_blank">
                                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> {{ __('VIES') }}
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <input type="text" class="form-control form-control-sm font-monospace @error('selectedPartnerVatNo') is-invalid @enderror"
                                   placeholder="LV40000000000"
                                   wire:model="selectedPartnerVatNo">
                            @error('selectedPartnerVatNo') <small class="text-danger error">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted mb-1">{{ __('Adrese') }}</label>
                        <input type="text" class="form-control form-control-sm @error('selectedPartnerAddress') is-invalid @enderror"
                               placeholder="Piem., Brīvības iela 1, Rīga"
                               wire:model.defer="selectedPartnerAddress">
                        @error('selectedPartnerAddress') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted mb-1">{{ __('Banka') }}</label>
                            <input type="text" class="form-control form-control-sm @error('selectedPartnerBank') is-invalid @enderror"
                                   placeholder="Piem., Swedbank AS"
                                   wire:model.defer="selectedPartnerBank">
                            @error('selectedPartnerBank') <small class="text-danger error">{{ $message }}</small>@enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-semibold text-muted mb-1">{{ __('SWIFT / BIC') }}</label>
                            <input type="text" class="form-control form-control-sm font-monospace @error('selectedPartnerSwift') is-invalid @enderror"
                                   placeholder="HABALV22"
                                   wire:model.defer="selectedPartnerSwift">
                            @error('selectedPartnerSwift') <small class="text-danger error">{{ $message }}</small>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted mb-1">{{ __('Bankas konts (IBAN)') }}</label>
                            <input type="text" class="form-control form-control-sm font-monospace @error('selectedPartnerAccountNumber') is-invalid @enderror"
                                   placeholder="LV00UNLA0000000000000"
                                   wire:model.defer="selectedPartnerAccountNumber">
                            @error('selectedPartnerAccountNumber') <small class="text-danger error">{{ $message }}</small>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-modern btn-modern-secondary btn-sm" wire:click="cancel()">{{ __('Aizvērt') }}</button>
                    <button type="button" class="btn btn-modern btn-modern-primary btn-sm" wire:click.prevent="save()">
                        <i class="fa-solid fa-check me-1"></i> {{ __('Saglabāt') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        window.addEventListener('partner_modal_open', event => {
            $('#partnerEditModal').modal('show');
        })

        window.addEventListener('partner_modal_close', () => {
            $('#partnerEditModal').modal('hide');
        });
    </script>

</div>