<div>
    <div class="input-group input-group-sm">
        <select wire:model="selectedEmployeeId" class="form-select form-select-sm" name="employee_id">
            @foreach($employees ?? [] as $employee)
                <option value="{{$employee['id']}}">{{$employee['name']}}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center px-2.5"
                type="button"
                wire:click="edit({{ $selectedEmployeeId }})"
                title="{{ __('Labot / Izveidot darbinieku') }}">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="employeeEditModal" tabindex="-1" aria-labelledby="employeeEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="employeeEditModalLabel">
                        <i class="fa-solid fa-user-pen me-2"></i> {{ $selectedEmployeeId > 0 ? __('Labot darbinieku') : __('Izveidot darbinieku') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close" wire:click="cancel()"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold mb-1">{{ __('Vārds Uzvārds') }} *</label>
                        <input type="text"
                               class="form-control @error('selectedEmployeeName') is-invalid @enderror"
                               placeholder="Piem., Bērziņš Dainis"
                               wire:model.defer="selectedEmployeeName">
                        @error('selectedEmployeeName') <small class="text-danger error">{{ $message }}</small> @enderror
                        <div class="form-text small text-muted">
                            <span class="text-success fw-medium">{{ __('Ieteicamais formāts:') }}</span> Bērziņš Dainis
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold mb-1">{{ __('Personas kods / Reģistrācijas Nr.') }} *</label>
                        <input type="text"
                               class="form-control font-monospace @error('selectedEmployeeRegNo') is-invalid @enderror"
                               placeholder="123456-12345"
                               wire:model.defer="selectedEmployeeRegNo">
                        @error('selectedEmployeeRegNo') <small class="text-danger error">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1">{{ __('Amats / Loma') }}</label>
                        <input type="text"
                               class="form-control @error('selectedEmployeeRole') is-invalid @enderror"
                               placeholder="Piem., Grāmatvedis, Vadītājs..."
                               wire:model.defer="selectedEmployeeRole">
                        @error('selectedEmployeeRole') <small class="text-danger error">{{ $message }}</small> @enderror
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
        window.addEventListener('employee_modal_open', event => {
            const modalEl = document.getElementById('employeeEditModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });

        window.addEventListener('employee_modal_close', () => {
            const modalEl = document.getElementById('employeeEditModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
        });
    </script>
</div>