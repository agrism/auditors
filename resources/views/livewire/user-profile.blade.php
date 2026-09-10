<div>
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <!-- Profile Card -->
            <div class="card card-modern shadow-sm border-0 overflow-hidden mb-4">
                <!-- Header -->
                <div class="card-header bg-white border-bottom py-3.5 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary-50 text-primary-600 p-2.5 d-inline-flex align-items-center justify-content-center"
                             style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-user-gear fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-slate-900">{{ __('Lietotāja profila iestatījumi') }}</h5>
                            <span class="small text-muted">{{ __('Pārvaldiet sava konta pamatdatus, e-pastu un drošības paroli') }}</span>
                        </div>
                    </div>

                    <button type="button"
                            class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                            wire:click.prevent="$emitUp('activateComponent', 'companies')">
                        <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Atpakaļ uz Sākumu') }}
                    </button>
                </div>

                <div class="card-body p-4 p-lg-5">
                    @if(!empty($successMessage))
                        <div class="alert alert-success d-flex align-items-center justify-content-between p-3 mb-4 rounded-3 border-0 shadow-xs"
                             style="background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0 !important;"
                             role="alert">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-circle-check fs-5 text-emerald-500"></i>
                                <span class="fw-semibold">{{ $successMessage }}</span>
                            </div>
                            <button type="button" class="btn-close btn-close-sm" wire:click="$set('successMessage', '')" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- User Info Summary -->
                    <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-xs"
                                 style="width: 48px; height: 48px; font-size: 1.2rem; background: linear-gradient(135deg, #002855 0%, #0284c7 100%);">
                                {{ strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-slate-900 fs-6">{{ $user->name ?? '' }}</div>
                                <div class="small text-muted font-monospace">{{ $user->email ?? '' }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="dash-role-badge">
                                <i class="fa-solid fa-shield-halved text-primary"></i>
                                <span>{{ $user->isAdmin() ? __('Administrators') : __('Lietotājs') }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Profile Form -->
                    <form wire:submit.prevent="save">
                        <div class="row g-4 mb-4">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="userNameInput" class="form-label small fw-bold text-slate-700 mb-1">
                                    {{ __('Vārds, Uzvārds') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <input type="text"
                                           id="userNameInput"
                                           class="form-control form-control-modern @error('name') is-invalid @enderror"
                                           wire:model.defer="name"
                                           placeholder="{{ __('Ievadiet vārdu un uzvārdu') }}">
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="userEmailInput" class="form-label small fw-bold text-slate-700 mb-1">
                                    {{ __('E-pasta adrese') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    <input type="email"
                                           id="userEmailInput"
                                           class="form-control form-control-modern @error('email') is-invalid @enderror"
                                           wire:model.defer="email"
                                           placeholder="vards@uznemums.lv">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-6">
                                <label for="userPasswordInput" class="form-label small fw-bold text-slate-700 mb-1">
                                    {{ __('Jaunā parole') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fa-solid fa-key"></i>
                                    </span>
                                    <input type="password"
                                           id="userPasswordInput"
                                           class="form-control form-control-modern @error('password') is-invalid @enderror"
                                           wire:model.defer="password"
                                           placeholder="{{ __('Atstājiet tukšu, lai nemainītu') }}"
                                           autocomplete="new-password">
                                </div>
                                <div class="form-text small text-muted">{{ __('Vismaz 6 simboli (atstājiet tukšu, ja nemaināt)') }}</div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Confirmation -->
                            <div class="col-md-6">
                                <label for="userPasswordConfirmInput" class="form-label small fw-bold text-slate-700 mb-1">
                                    {{ __('Paroles atkārtojums') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <input type="password"
                                           id="userPasswordConfirmInput"
                                           class="form-control form-control-modern"
                                           wire:model.defer="password_confirmation"
                                           placeholder="{{ __('Atkārtojiet jauno paroli') }}"
                                           autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Bar -->
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top flex-wrap gap-2">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm px-3"
                                    wire:click.prevent="$emitUp('activateComponent', 'companies')">
                                <i class="fa-solid fa-xmark me-1"></i> {{ __('Atcelt') }}
                            </button>

                            <button type="submit"
                                    class="btn btn-modern btn-modern-primary btn-sm px-4 shadow-sm"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">
                                    <i class="fa-solid fa-check me-1"></i> {{ __('Saglabāt izmaiņas') }}
                                </span>
                                <span wire:loading wire:target="save">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    {{ __('Saglabā...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
