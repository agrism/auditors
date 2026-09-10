<div>
    <div wire:loading style="position: absolute">
        <x-loading loading="true"></x-loading>
    </div>

    <div class="input-group input-group-sm">
        <select wire:model="selectedBudgetId" class="form-select form-select-sm" name="budget_id">
            @foreach($budgets ?? [] as $budget)
                <option value="{{$budget['id']}}">{{$budget['code']}}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center px-2"
                type="button"
                data-bs-toggle="modal"
                wire:click="edit({{ $selectedBudgetId }})"
                title="{{ __('Labot budžetu') }}">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="budgetEditModal" tabindex="-1" aria-labelledby="budgetEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="budgetEditModalLabel">
                        <i class="fa-solid fa-pen-to-square me-2"></i> {{ $selectedBudgetId > 0 ? __('Labot budžetu') : __('Izveidot budžetu') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close" wire:click="cancel()"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold mb-1">{{ __('Kods') }} *</label>
                        <input type="text" class="form-control font-monospace @error('selectedBudgetCode') is-invalid @enderror"
                               placeholder="Piem., 100"
                               wire:model.defer="selectedBudgetCode">
                        @error('selectedBudgetCode') <small class="text-danger error">{{ $message }}</small>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1">{{ __('Nosaukums') }} *</label>
                        <input type="text" class="form-control @error('selectedBudgetName') is-invalid @enderror"
                               placeholder="Piem., Administratīvie izdevumi"
                               wire:model.defer="selectedBudgetName">
                        @error('selectedBudgetName') <small class="text-danger error">{{ $message }}</small>@enderror
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
        window.addEventListener('budget_modal_open', event => {
            $('#budgetEditModal').modal('show');
        });

        window.addEventListener('budget_modal_close', () => {
            $('#budgetEditModal').modal('hide');
        });
    </script>
</div>