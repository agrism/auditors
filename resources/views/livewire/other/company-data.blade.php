<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-id-card fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Uzņēmuma dati') }}</h5>
                        <span class="small text-muted">{{ __('Pārvaldiet uzņēmuma pamatinformāciju un bankas rekvizītus') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 rounded-3" role="alert">
                        <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                        <div>{{ session('message') }}</div>
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <div class="row g-4">
                        <!-- Basic Info Column -->
                        <div class="col-lg-6">
                            <h6 class="fw-bold text-slate-800 mb-3 pb-2 border-bottom">
                                <i class="fa-solid fa-building me-1 text-primary-500"></i> Pamatinformācija
                            </h6>

                            <div class="mb-3">
                                <label for="title" class="form-label small fw-semibold text-slate-700">Uzņēmuma nosaukums</label>
                                <input type="text"
                                       id="title"
                                       wire:model.defer="details.title"
                                       class="form-control form-control-modern @error('details.title') is-invalid @enderror"
                                       placeholder="Piem., SIA Mans Uzņēmums">
                                @error('details.title') <small class="text-danger error">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label small fw-semibold text-slate-700">Juridiskā adrese</label>
                                <input type="text"
                                       id="address"
                                       wire:model.defer="details.address"
                                       class="form-control form-control-modern @error('details.address') is-invalid @enderror"
                                       placeholder="Piem., Brīvības iela 1, Rīga, LV-1010">
                                @error('details.address') <small class="text-danger error">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="registration_number" class="form-label small fw-semibold text-slate-700">Reģistrācijas Nr.</label>
                                <input type="text"
                                       id="registration_number"
                                       wire:model.defer="details.registration_number"
                                       class="form-control form-control-modern @error('details.registration_number') is-invalid @enderror"
                                       placeholder="40000000000">
                                @error('details.registration_number') <small class="text-danger error">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- Bank & Requisites Column -->
                        <div class="col-lg-6">
                            <h6 class="fw-bold text-slate-800 mb-3 pb-2 border-bottom">
                                <i class="fa-solid fa-building-columns me-1 text-primary-500"></i> Bankas rekvizīti
                            </h6>

                            <div class="mb-3">
                                <label for="bank" class="form-label small fw-semibold text-slate-700">Noklusējuma banka</label>
                                <input type="text"
                                       id="bank"
                                       wire:model.defer="details.bank"
                                       class="form-control form-control-modern @error('details.bank') is-invalid @enderror"
                                       placeholder="Piem., Swedbank AS">
                                @error('details.bank') <small class="text-danger error">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="swift" class="form-label small fw-semibold text-slate-700">SWIFT / BIC kods</label>
                                <input type="text"
                                       id="swift"
                                       wire:model.defer="details.swift"
                                       class="form-control form-control-modern @error('details.swift') is-invalid @enderror"
                                       placeholder="HABALV22">
                                @error('details.swift') <small class="text-danger error">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="account_number" class="form-label small fw-semibold text-slate-700">Bankas konts (IBAN)</label>
                                <input type="text"
                                       id="account_number"
                                       wire:model.defer="details.account_number"
                                       class="form-control form-control-modern @error('details.account_number') is-invalid @enderror"
                                       placeholder="LV00UNLA0000000000000">
                                @error('details.account_number') <small class="text-danger error">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- VAT Numbers Section -->
                        <div class="col-12">
                            <h6 class="fw-bold text-slate-800 mb-3 pb-2 border-bottom d-flex align-items-center justify-content-between">
                                <span><i class="fa-solid fa-receipt me-1 text-primary-500"></i> PVN reģistrācijas numuri</span>
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary py-1 px-3 rounded-pill"
                                        wire:click="addVatLine">
                                    <i class="fa-solid fa-plus me-1"></i> Pievienot PVN numuru
                                </button>
                            </h6>

                            <div class="row g-2">
                                @forelse($details['vat_numbers'] ?? [] as $id => $number)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-muted border-end-0">PVN</span>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder="LV40000000000"
                                                   wire:model.lazy="details.vat_numbers.{{$id}}">
                                            <button class="btn btn-outline-danger"
                                                    type="button"
                                                    title="Dzēst"
                                                    wire:click="removeVatLine('{{$id}}')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small py-2">
                                        Nav pievienots neviens papildu PVN numurs.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-modern btn-modern-primary px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Saglabāt izmaiņas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        window.addEventListener('alert_remove', event => {
            setTimeout(function () {
                const alertEl = document.querySelector('.alert');
                if (alertEl) alertEl.style.display = 'none';
            }, 3000);
        })
    </script>
</div>