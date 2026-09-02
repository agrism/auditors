<div>
    <div wire:loading.delay wire:target="setActiveEmployeeId" style="position: absolute; z-index: 10;">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="col-lg-12">
        <div class="card card-modern shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                        <i class="fa-solid fa-umbrella-beach fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ __('Atvaļinājumu kopsavilkums') }}</h5>
                        <span class="small text-muted">{{ __('Darbinieku uzkrātās un izmantotās atvaļinājuma dienas') }}</span>
                    </div>
                </div>

                @if($activeEmployeeId)
                    <div>
                        <button class="btn btn-modern btn-modern-secondary btn-sm" wire:click="setActiveEmployeeId('')">
                            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ uz sarakstu') }}
                        </button>
                    </div>
                @endif
            </div>

            @if(!$activeEmployeeId)
                <!-- Modern Business Search Bar -->
                <div class="bg-slate-50 border-bottom px-4 py-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6 col-lg-4">
                            <label class="form-label text-muted small fw-semibold mb-1 d-flex align-items-center gap-1">
                                <i class="fa-solid fa-magnifying-glass text-primary-500"></i> {{ __('Meklēt darbinieku') }}
                            </label>
                            <input type="text"
                                   id="vacationEmployeeSearch"
                                   class="form-control form-control-sm bg-white"
                                   placeholder="Ievadiet darbinieka vārdu..."
                                   onkeyup="var val = this.value.toLowerCase(); document.querySelectorAll('#vacationTable tbody tr').forEach(function(r){ r.style.display = r.innerText.toLowerCase().includes(val) ? '' : 'none'; });">
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-body p-0">
                @if($activeEmployeeId)
                    <div class="p-3">
                        <livewire:vacations.employee-details :employeeId="$activeEmployeeId" />
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0" id="vacationTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Nr.</th>
                                    <th style="width: 70px;">ID</th>
                                    <th>Darbinieks</th>
                                    <th>Atlikums (dienas)</th>
                                    <th>Statuss</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($this->employees() as $index => $employee)
                                <tr wire:click="setActiveEmployeeId({{$employee['employeeId'] ?? ''}})"
                                    role="button"
                                    style="cursor: pointer;">
                                    <td class="text-muted small">{{ $index + 1 }}</td>
                                    <td class="text-muted font-monospace small">#{{ $employee['employeeId'] ?? '-' }}</td>
                                    <td class="fw-semibold text-slate-800">{{ $employee['employeeName'] ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary-50 text-primary-700 px-3 py-1 font-monospace fs-6">
                                            {{ $employee['vacationBalance'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(($employee['active'] ?? '') == '1' || ($employee['active'] ?? '') === true || strtolower($employee['active'] ?? '') === 'active' || strtolower($employee['active'] ?? '') === 'jā')
                                            <span class="badge bg-success-50 text-success border border-success-subtle">Aktīvs</span>
                                        @else
                                            <span class="badge bg-slate-100 text-slate-600">{{ $employee['active'] ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <i class="fa-solid fa-chevron-right text-muted opacity-25"></i>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fa-regular fa-folder-open fs-3 d-block mb-2 text-slate-400"></i>
                                        Nav atrasti atvaļinājumu dati.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
