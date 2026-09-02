<div>
    <div class="input-group input-group-sm">
        <select wire:model="selectedPartnerId" class="form-select form-select-sm" name="partner_id">
            @foreach($partners ?? [] as $partner)
                <option value="{{$partner['id']}}">{{$partner['name']}}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center px-2"
                type="button"
                data-bs-toggle="modal"
                wire:click="edit({{ $selectedPartnerId }})"
                title="{{ __('Labot partneri') }}">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="partnerEditModal" tabindex="-1" aria-labelledby="partnerEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="partnerEditModalLabel">
                        <i class="fa-solid fa-user-pen me-2"></i> {{ $selectedPartnerId > 0 ? __('Labot partneri') : __('Izveidot partneri') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close" wire:click="cancel()"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-semibold mb-0">{{ __('Nosaukums / Vārds Uzvārds') }} *</label>
                            @if($selectedPartnerName)
                                <a class="small text-primary text-decoration-none" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$selectedPartnerName}}&search%5Bwhere%5D=name" target="_blank">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Pārbaudīt firmas.lv
                                </a>
                            @endif
                        </div>
                        <input type="text"
                               class="form-control @error('selectedPartnerName') is-invalid @enderror"
                               placeholder="Piem., Mans Uzņēmums SIA vai Bērziņš Jānis"
                               wire:model="selectedPartnerName">
                        @error('selectedPartnerName') <small class="text-danger error">{{ $message }}</small> @enderror
                        <div class="form-text small text-muted">
                            <span class="text-success fw-medium">Pareizi:</span> SIA Zeme vai Bērziņš Dainis
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-semibold mb-0">{{ __('Reģistrācijas Nr. / Personas kods') }}</label>
                                @if($selectedPartnerRegNo)
                                    <a class="small text-primary text-decoration-none" href="https://www.firmas.lv/lv/uznemumi/meklet?q={{$selectedPartnerRegNo}}&search%5Bwhere%5D=code" target="_blank">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> firmas.lv
                                    </a>
                                @endif
                            </div>
                            <input type="text"
                                   class="form-control @error('selectedPartnerRegNo') is-invalid @enderror"
                                   placeholder="40000000000"
                                   wire:model="selectedPartnerRegNo">
                            @error('selectedPartnerRegNo') <small class="text-danger error">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-semibold mb-0">{{ __('PVN reģistrācijas Nr.') }}</label>
                                @if($selectedPartnerVatNo)
                                    @php
                                        $countryCode = preg_replace('/[^A-Z]/', '', substr(trim($selectedPartnerVatNo), 0, 2));
                                        $number = strlen($countryCode) === 2 ? substr(trim($selectedPartnerVatNo), 2) : '';
                                    @endphp
                                    @if($number)
                                        <a class="small text-primary text-decoration-none" href="https://ec.europa.eu/taxation_customs/vies/viesquer.do?ms={{$countryCode}}&iso={{$countryCode}}&vat={{$number}}" target="_blank">
                                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ec.europa.eu
                                        </a>
                                    @endif
                                @endif
                            </div>
                            <input type="text"
                                   class="form-control @error('selectedPartnerVatNo') is-invalid @enderror"
                                   placeholder="LV40000000000"
                                   wire:model="selectedPartnerVatNo">
                            @error('selectedPartnerVatNo') <small class="text-danger error">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">{{ __('Juridiskā adrese') }}</label>
                        <input type="text"
                               class="form-control @error('selectedPartnerAddress') is-invalid @enderror"
                               placeholder="Brīvības iela 1, Rīga, LV-1010"
                               wire:model.defer="selectedPartnerAddress">
                        @error('selectedPartnerAddress') <small class="text-danger error">{{ $message }}</small> @enderror
                    </div>

                    <hr class="my-3 text-muted opacity-25">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">{{ __('Bankas nosaukums') }}</label>
                            <input type="text"
                                   class="form-control @error('selectedPartnerBank') is-invalid @enderror"
                                   placeholder="Swedbank AS"
                                   wire:model.defer="selectedPartnerBank">
                            @error('selectedPartnerBank') <small class="text-danger error">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">{{ __('SWIFT / BIC kods') }}</label>
                            <input type="text"
                                   class="form-control @error('selectedPartnerSwift') is-invalid @enderror"
                                   placeholder="HABALV22"
                                   wire:model.defer="selectedPartnerSwift">
                            @error('selectedPartnerSwift') <small class="text-danger error">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">{{ __('Bankas konts (IBAN)') }}</label>
                            <input type="text"
                                   class="form-control @error('selectedPartnerAccountNumber') is-invalid @enderror"
                                   placeholder="LV00UNLA0000000000000"
                                   wire:model.defer="selectedPartnerAccountNumber">
                            @error('selectedPartnerAccountNumber') <small class="text-danger error">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-modern btn-modern-secondary btn-sm" wire:click="cancel()">{{ __('Aizvērt') }}</button>
                    <button type="button" class="btn btn-modern btn-modern-primary btn-sm" wire:click.prevent="save()">{{ __('Saglabāt izmaiņas') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        window.addEventListener('partner_modal_open', event => {
            const modalEl = document.getElementById('partnerEditModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });

        window.addEventListener('partner_modal_close', () => {
            const modalEl = document.getElementById('partnerEditModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
        });
    </script>
</div>