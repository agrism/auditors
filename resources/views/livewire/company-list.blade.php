<div>
    <div class="card card-modern shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                    <i class="fa-solid fa-building-user fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ __('Izvēlieties aktīvo uzņēmumu') }}</h5>
                    <span class="small text-muted">{{ __('Izvēlieties uzņēmumu, lai pārvaldītu rēķinus un atskaites') }}</span>
                </div>
            </div>
            <span class="badge bg-slate-100 text-slate-700 px-3 py-2 rounded-pill fw-semibold">
                {{ count($companies) }} {{ __('Uzņēmumi') }}
            </span>
        </div>

        <div class="card-body p-4">
            <div class="companies-grid">
                @foreach($companies as $company)
                    <?php
                    $isActive = ($company->id == \App\Services\AuthUser::instance()->selectedCompanyId());
                    $initials = mb_substr(trim($company->title ?? 'CO'), 0, 2);
                    ?>
                    <div wire:click="setActiveCompanyId({{ $company->id }})"
                         role="button"
                         class="company-card @if($isActive) active @endif">
                        <div class="company-avatar flex-shrink-0">
                            {{ strtoupper($initials) }}
                        </div>
                        <div class="company-info flex-grow-1 min-w-0" style="min-width: 0;">
                            <h6 class="text-truncate mb-1 fw-bold text-slate-800" title="{{ $company->title }}">{{ $company->title }}</h6>
                            <div class="text-muted small text-truncate">
                                @if(!empty($company->reg_number))
                                    <span class="font-monospace"><i class="fa-solid fa-hashtag text-slate-400 me-0.5"></i>{{ $company->reg_number }}</span>
                                @else
                                    <span>ID: #{{ $company->id }}</span>
                                @endif
                            </div>
                        </div>
                        @if($isActive)
                            <div class="company-card-check flex-shrink-0" title="Aktīvs">
                                <i class="fa-solid fa-check"></i>
                            </div>
                        @else
                            <div class="text-muted opacity-25 ms-auto flex-shrink-0">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
