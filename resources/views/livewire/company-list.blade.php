<div>
    <div class="card card-modern shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 bg-primary-50 text-primary-600 p-2 d-inline-flex">
                    <i class="fa-solid fa-building-user fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ __('Izvēlieties aktīvo uzņēmumu') }}</h5>
                    <span class="small text-muted">{{ __('Izvēlieties uzņēmumu, lai pārvaldītu rēķinus un atskaites') }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if(count($companies) > 6)
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Meklēt uzņēmumu..." 
                               oninput="filterCompanyGrid(this.value)"
                               autocomplete="off">
                    </div>
                @endif
                <span class="badge bg-slate-100 text-slate-700 px-3 py-2 rounded-pill fw-semibold font-monospace">
                    {{ count($companies) }} {{ __('Uzņēmumi') }}
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="companies-grid" id="companiesGridList">
                @foreach($companies as $company)
                    <?php
                    $cleanTitle = html_entity_decode((string)$company->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $compReg = $company->registration_number ?? $company->reg_number;
                    $isActive = ($company->id == \App\Services\AuthUser::instance()->selectedCompanyId());
                    $initials = mb_substr(trim($cleanTitle ?: 'CO'), 0, 2);
                    ?>
                    <div wire:click="setActiveCompanyId({{ $company->id }})"
                         role="button"
                         class="company-card company-grid-item @if($isActive) active @endif"
                         data-search-text="{{ mb_strtolower($cleanTitle . ' ' . $compReg) }}">
                        <div class="company-avatar flex-shrink-0">
                            {{ strtoupper($initials) }}
                        </div>
                        <div class="company-info flex-grow-1 min-w-0" style="min-width: 0;">
                            <h6 class="text-truncate mb-1 fw-bold text-slate-800" title="{{ $cleanTitle }}">{{ $cleanTitle }}</h6>
                            <div class="text-muted small text-truncate">
                                @if(!empty($compReg))
                                    <span class="font-monospace"><i class="fa-solid fa-hashtag text-slate-400 me-0.5"></i>{{ $compReg }}</span>
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
            <div id="companyGridNoMatch" class="text-center py-5 text-muted" style="display: none;">
                <i class="fa-regular fa-folder-open fs-2 d-block mb-2 text-slate-400"></i>
                <div class="fw-semibold">Nav atrasts neviens uzņēmums</div>
                <div class="small">Mēģiniet mainīt meklēšanas frāzi</div>
            </div>
        </div>
    </div>
    <script>
        function filterCompanyGrid(val) {
            val = (val || '').toLowerCase().trim();
            var items = document.querySelectorAll('#companiesGridList .company-grid-item');
            var visibleCount = 0;
            items.forEach(function(item) {
                var text = item.getAttribute('data-search-text') || '';
                if (!val || text.includes(val)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            var noMatch = document.getElementById('companyGridNoMatch');
            if (noMatch) {
                noMatch.style.display = (visibleCount === 0) ? 'block' : 'none';
            }
        }
    </script>
</div>
