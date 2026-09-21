<div class="container-fluid px-0">
    @if(!empty($successFlash))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-none border" role="alert" style="border-radius: 2px; border-color: #86efac; background-color: #f0fdf4;">
            <i class="fa-solid fa-circle-check text-success fs-5"></i>
            <div class="text-dark small fw-medium">{{ $successFlash }}</div>
            <button type="button" class="btn-close" wire:click="$set('successFlash', '')" aria-label="Close" style="padding: 0.75rem;"></button>
        </div>
    @endif

    @if(!$selectedReportId)
        <!-- Action Toolbar -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 2px; font-size: 0.8125rem; height: 32px;" onclick="openBugReportModal()">
                    <i class="fa-solid fa-plus text-white"></i>
                    <span class="text-white">{{ __('Jauns paziņojums / ieteikums') }}</span>
                </button>
            </div>
        </div>

        <div class="card border mb-4 shadow-none" style="border-radius: 2px; border-color: #cbd5e1; background-color: #ffffff;">
            <!-- Filter & Search Bar -->
            <div class="card-header bg-white border-bottom p-2.5" style="border-radius: 2px 2px 0 0; border-color: #cbd5e1;">
                <div class="row g-2 align-items-center justify-content-between">
                    <!-- Status Filter Tabs -->
                    <div class="col-12 col-md-auto">
                        <div class="btn-group btn-group-sm flex-wrap" role="group" style="border-radius: 2px;">
                            <button type="button" 
                                    class="btn @if($statusFilter === 'all') btn-primary @else btn-outline-secondary @endif" 
                                    style="border-radius: 2px 0 0 2px;"
                                    wire:click="$set('statusFilter', 'all')">
                                Visi <span class="badge ms-1 font-monospace" style="border-radius: 2px; font-size: 0.7rem; @if($statusFilter === 'all') background-color: #ffffff; color: #002855; @else background-color: #64748b; color: #ffffff; @endif">{{ $counts['all'] }}</span>
                            </button>
                            <button type="button" 
                                    class="btn @if($statusFilter === 'new') btn-primary @else btn-outline-secondary @endif" 
                                    style="border-radius: 0;"
                                    wire:click="$set('statusFilter', 'new')">
                                Jauni <span class="badge ms-1 font-monospace" style="border-radius: 2px; font-size: 0.7rem; @if($statusFilter === 'new') background-color: #ffffff; color: #002855; @else background-color: #64748b; color: #ffffff; @endif">{{ $counts['new'] }}</span>
                            </button>
                            <button type="button" 
                                    class="btn @if($statusFilter === 'in_progress') btn-primary @else btn-outline-secondary @endif" 
                                    style="border-radius: 0;"
                                    wire:click="$set('statusFilter', 'in_progress')">
                                Izskatīšanā <span class="badge ms-1 font-monospace" style="border-radius: 2px; font-size: 0.7rem; @if($statusFilter === 'in_progress') background-color: #ffffff; color: #002855; @else background-color: #64748b; color: #ffffff; @endif">{{ $counts['in_progress'] }}</span>
                            </button>
                            <button type="button" 
                                    class="btn @if($statusFilter === 'answered') btn-primary @else btn-outline-secondary @endif" 
                                    style="border-radius: 0;"
                                    wire:click="$set('statusFilter', 'answered')">
                                Atbildēti <span class="badge ms-1 font-monospace" style="border-radius: 2px; font-size: 0.7rem; @if($statusFilter === 'answered') background-color: #ffffff; color: #002855; @else background-color: #15803d; color: #ffffff; @endif">{{ $counts['answered'] }}</span>
                            </button>
                            <button type="button" 
                                    class="btn @if($statusFilter === 'closed') btn-primary @else btn-outline-secondary @endif" 
                                    style="border-radius: 0 2px 2px 0;"
                                    wire:click="$set('statusFilter', 'closed')">
                                Slēgti <span class="badge ms-1 font-monospace" style="border-radius: 2px; font-size: 0.7rem; @if($statusFilter === 'closed') background-color: #ffffff; color: #002855; @else background-color: #64748b; color: #ffffff; @endif">{{ $counts['closed'] }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="input-group input-group-sm" style="border-radius: 2px;">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 2px 0 0 2px; border-color: #c4cdd5;">
                                <i class="fa-solid fa-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control border-start-0" 
                                   placeholder="Meklēt ziņojumos..." 
                                   wire:model.debounce.300ms="search"
                                   style="border-radius: 0 2px 2px 0; border-color: #c4cdd5; font-size: 0.8125rem;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.8125rem;">
                        <thead class="bg-light text-uppercase font-monospace border-bottom" style="border-color: #cbd5e1; color: #475569;">
                        <tr>
                            <th class="ps-3 py-2" style="width: 80px; color: #475569;">ID</th>
                            <th class="py-2" style="width: 130px; color: #475569;">{{ __('Datums') }}</th>
                            <th class="py-2" style="min-width: 170px; color: #475569;">{{ __('Sadaļa / Vieta') }}</th>
                            <th class="py-2" style="min-width: 260px; color: #475569;">{{ __('Pēdējā ziņa / Apraksts') }}</th>
                            <th class="text-center py-2" style="width: 85px; color: #475569;">{{ __('Pielikumi') }}</th>
                            <th class="text-center py-2" style="width: 120px; color: #475569;">{{ __('Statuss') }}</th>
                            <th class="text-end pe-3 py-2" style="width: 100px; color: #475569;">{{ __('Darbība') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reports as $report)
                            @php
                                $firstItem = $report->items->first();
                                $latestItem = $report->items->last();
                                $totalAttachments = $report->items->sum(function($i) { return is_array($i->attachments) ? count($i->attachments) : 0; });
                                $isUnread = $report->isUnreadForClient();
                            @endphp
                            <tr class="line @if($isUnread) table-warning bg-opacity-25 @elseif($report->status === 'answered') table-success bg-opacity-10 @endif" style="cursor: pointer; border-bottom: 1px solid #e2e8f0; @if($isUnread) border-left: 3px solid #ef4444 !important; @endif" wire:click="openReport({{ $report->id }})">
                                <td class="ps-3 font-monospace fw-bold" style="color: #0f172a;">
                                    <div class="d-flex align-items-center gap-1.5">
                                        @if($isUnread)
                                            <span class="badge bg-danger rounded-circle p-1" style="width: 7px; height: 7px;" title="Neizlasīta atbilde"></span>
                                        @endif
                                        <span>#{{ $report->id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-monospace fw-medium" style="color: #0f172a;">{{ $report->created_at->format('d.m.Y') }}</div>
                                    <div class="font-monospace" style="font-size: 0.725rem; color: #64748b;">{{ $report->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    @if(!empty($report->section))
                                        <span class="badge border px-2 py-1 fw-medium text-truncate d-inline-block" style="max-width: 220px; border-radius: 2px; font-size: 0.75rem; border-color: #cbd5e1; background-color: #f8fafc; color: #1e293b;" title="{{ $report->section }}">
                                            <i class="fa-solid fa-folder me-1 text-primary"></i>{{ $report->section }}
                                        </span>
                                    @else
                                        <span class="small" style="color: #64748b;">Klientu portāls</span>
                                    @endif
                                    @if(!empty($firstItem->message))
                                        <div class="text-truncate mt-1" style="max-width: 230px; font-size: 0.725rem; color: #475569;" title="{{ $firstItem->message }}">
                                            {{ Str::limit($firstItem->message, 55) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-truncate @if($isUnread) fw-bold @else fw-medium @endif" style="max-width: 320px; color: #0f172a;" title="{{ $latestItem->message ?? ($firstItem->message ?? '') }}">
                                        @if($isUnread)
                                            <span class="badge bg-danger text-white me-1 font-monospace" style="border-radius: 2px; font-size: 0.65rem;">
                                                <i class="fa-solid fa-bell me-1"></i>JAUNA ATBILDE
                                            </span>
                                        @elseif($latestItem && $latestItem->is_admin_reply)
                                            <span class="badge me-1 border" style="border-radius: 2px; font-size: 0.7rem; background-color: #eff6ff; color: #1d4ed8; border-color: #bfdbfe !important;">
                                                <i class="fa-solid fa-reply me-1"></i>Atbilde
                                            </span>
                                        @endif
                                        {{ $latestItem->message ?? ($firstItem->message ?? '-') }}
                                    </div>
                                    @if($report->items->count() > 1)
                                        <div class="mt-0.5" style="font-size: 0.725rem; color: #64748b;">
                                            <i class="fa-regular fa-comments me-1"></i>{{ $report->items->count() }} ziņas sarunā
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($totalAttachments > 0)
                                        <span class="badge border px-2 py-1 font-monospace" style="border-radius: 2px; border-color: #cbd5e1; font-size: 0.725rem; background-color: #f8fafc; color: #0f172a;" title="{{ $totalAttachments }} pievienoti faili">
                                            <i class="fa-solid fa-paperclip me-1 text-primary"></i>{{ $totalAttachments }}
                                        </span>
                                    @else
                                        <span class="small" style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="{{ $report->status_enum->badgeClass() }}">
                                        <i class="{{ $report->status_enum->icon() }}"></i> {{ $report->status_enum->label() }}
                                    </span>
                                </td>
                                <td class="text-end pe-3" onclick="event.stopPropagation()">
                                    <button class="btn btn-sm btn-outline-primary px-2.5 py-1" style="border-radius: 2px;" wire:click="openReport({{ $report->id }})">
                                        <i class="fa-solid fa-comments me-1"></i> {{ __('Skatīt') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color: #64748b;">
                                    <div class="d-inline-flex align-items-center justify-content-center p-3 mb-2 bg-light border" style="border-radius: 2px; border-color: #cbd5e1;">
                                        <i class="fa-regular fa-comment-dots fs-3 text-secondary"></i>
                                    </div>
                                    <h6 class="fw-bold mt-2 mb-1" style="color: #0f172a;">{{ __('Nav atrasts neviens ziņojums') }}</h6>
                                    <p class="small mb-3" style="color: #64748b;">{{ __('Ja pamanāt kļūdu vai vēlaties ieteikt uzlabojumu, izveidojiet jaunu ziņojumu.') }}</p>
                                    <button type="button" class="btn btn-sm btn-primary px-3 py-1.5 fw-semibold" style="border-radius: 2px;" onclick="openBugReportModal()">
                                        <i class="fa-solid fa-plus me-1 text-white"></i> <span class="text-white">{{ __('Ziņot par kļūdu vai ieteikt') }}</span>
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($reports->hasPages())
                <div class="card-footer bg-white border-top p-2.5" style="border-radius: 0 0 2px 2px; border-color: #cbd5e1;">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>

    @else
        <!-- ==================== 2. THREAD DETAIL VIEW ==================== -->
        @if($selectedReport)
            @php
                $allThreadImages = [];
                foreach ($selectedReport->items as $msgItem) {
                    if (!empty($msgItem->attachments) && is_array($msgItem->attachments)) {
                        foreach ($msgItem->attachments as $att) {
                            $isImg = !empty($att['mime']) && str_starts_with($att['mime'], 'image/');
                            if (!$isImg && !empty($att['name'])) {
                                $ext = strtolower(pathinfo($att['name'], PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
                            }
                            if ($isImg) {
                                $imgUrl = !empty($att['path']) ? asset('storage/' . ltrim($att['path'], '/')) : ($att['url'] ?? Storage::disk('public')->url($att['path'] ?? ''));
                                $allThreadImages[] = [
                                    'url' => $imgUrl,
                                    'name' => $att['name'] ?? basename($att['path'] ?? 'attels.jpg'),
                                    'size' => !empty($att['size']) ? number_format($att['size'] / 1024, 1) . ' KB' : '',
                                    'time' => $msgItem->created_at->format('d.m.Y H:i'),
                                    'author' => $msgItem->is_admin_reply ? 'Auditors.lv Atbalsts' : ($msgItem->user->name ?? 'Lietotājs'),
                                ];
                            }
                        }
                    }
                }
            @endphp

            <!-- Top Navigation Bar -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2.5">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 fw-medium" style="border-radius: 2px;" wire:click.prevent="closeReportDetail">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>{{ __('Atpakaļ uz sarakstu') }}</span>
                    </button>
                    <nav aria-label="breadcrumb" class="d-none d-sm-inline-block ms-2">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0)" wire:click.prevent="closeReportDetail" class="text-decoration-none fw-semibold text-primary">
                                    <i class="fa-solid fa-comments me-1"></i>{{ __('Manas saziņas') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active font-monospace fw-semibold" aria-current="page" style="color: #475569;">#{{ $selectedReport->id }}</li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @if($selectedReport->status_value !== 'closed')
                        <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 fw-medium" style="border-radius: 2px;" wire:click.prevent="closeTicket({{ $selectedReport->id }})">
                            <i class="fa-solid fa-check"></i>
                            <span>{{ __('Atzīmēt kā atrisinātu / slēgt') }}</span>
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1.5 fw-medium" style="border-radius: 2px;" wire:click.prevent="reopenTicket({{ $selectedReport->id }})">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span>{{ __('Atvērt atkārtoti') }}</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Main Thread Container -->
            <div class="bug-thread-wrap mb-4">
                <!-- Thread Summary Header -->
                <div class="bug-thread-header">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge font-monospace" style="background-color: #002855; color: #ffffff !important; border-radius: 2px; font-size: 0.8rem; letter-spacing: 0.5px; padding: 0.25rem 0.55rem;">
                                PIETEIKUMS #{{ $selectedReport->id }}
                            </span>
                            @if($selectedReport->statuses && $selectedReport->statuses->count() > 0)
                                <span class="{{ $selectedReport->status_enum->badgeClass() }} user-select-none" 
                                      role="button"
                                      tabindex="0"
                                      data-bs-toggle="collapse" 
                                      data-bs-target="#clientStatusHistoryCollapse" 
                                      aria-expanded="false" 
                                      aria-controls="clientStatusHistoryCollapse"
                                      title="{{ __('Klikšķiniet, lai atvērtu statusu vēsturi') }} ({{ $selectedReport->statuses->count() }})"
                                      style="cursor: pointer;">
                                    <i class="{{ $selectedReport->status_enum->icon() }}"></i>
                                    <span>{{ $selectedReport->status_enum->label() }}</span>
                                    <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.6rem; opacity: 0.75;"></i>
                                </span>
                            @else
                                <span class="{{ $selectedReport->status_enum->badgeClass() }}">
                                    <i class="{{ $selectedReport->status_enum->icon() }}"></i> {{ $selectedReport->status_enum->label() }}
                                </span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="font-monospace" style="font-size: 0.725rem; color: #64748b;">
                                <i class="fa-regular fa-calendar me-1"></i>{{ $selectedReport->created_at->format('d.m.Y H:i') }}
                            </span>
                            @if(!empty($selectedReport->section))
                                <span class="badge border font-monospace text-truncate d-none d-sm-inline-block" style="max-width: 200px; border-radius: 2px; border-color: #cbd5e1; background-color: #f8fafc; color: #1e293b; font-size: 0.7rem;">
                                    <i class="fa-solid fa-folder me-1 text-primary"></i>{{ $selectedReport->section }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($selectedReport->statuses && $selectedReport->statuses->count() > 0)
                        <!-- Collapsible Vertical Timeline -->
                        <div class="collapse mt-2" id="clientStatusHistoryCollapse">
                            <div class="p-2 bg-light border-top border-bottom" style="border-color: #e2e8f0 !important;">
                                <div class="d-flex flex-column gap-1.5">
                                    @foreach($selectedReport->statuses as $index => $stRecord)
                                        <div class="d-flex align-items-center gap-2 font-monospace" style="font-size: 0.7rem;">
                                            <span class="text-muted" style="min-width: 20px;">#{{ $index + 1 }}</span>
                                            <span class="{{ $stRecord->status_enum->badgeClass() }}" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">
                                                {{ $stRecord->status_enum->label() }}
                                            </span>
                                            <span class="fw-semibold" style="color: #0f172a;">
                                                {{ $stRecord->created_at->format('d.m.Y H:i:s') }}
                                            </span>
                                            <span style="color: #64748b;">
                                                @if($stRecord->user_id === Auth::id())
                                                    &bull; Jūs
                                                @elseif($stRecord->user)
                                                    &bull; Atbalsts
                                                @else
                                                    &bull; Sistēma
                                                @endif
                                            </span>
                                            @if(!empty($stRecord->comment))
                                                <span class="fst-italic text-truncate" style="color: #475569; max-width: 280px;" title="{{ $stRecord->comment }}">
                                                    - {{ $stRecord->comment }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Messages Timeline Container -->
                <div class="bug-thread-messages">
                    @foreach($selectedReport->items as $index => $item)
                        @php
                            $isInitial = $loop->first;
                            $isAdmin = $item->is_admin_reply;
                            $images = [];
                            $files = [];
                            if (!empty($item->attachments) && is_array($item->attachments)) {
                                foreach ($item->attachments as $att) {
                                    $isImg = !empty($att['mime']) && str_starts_with($att['mime'], 'image/');
                                    if (!$isImg && !empty($att['name'])) {
                                        $ext = strtolower(pathinfo($att['name'], PATHINFO_EXTENSION));
                                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
                                    }
                                    $url = !empty($att['path']) ? asset('storage/' . ltrim($att['path'], '/')) : ($att['url'] ?? Storage::disk('public')->url($att['path'] ?? ''));
                                    $name = $att['name'] ?? basename($att['path'] ?? 'fails');
                                    $sizeStr = !empty($att['size']) ? number_format($att['size'] / 1024, 1) . ' KB' : '';
                                    if ($isImg) {
                                        $images[] = ['url' => $url, 'name' => $name, 'size' => $sizeStr];
                                    } else {
                                        $files[] = ['url' => $url, 'name' => $name, 'size' => $sizeStr];
                                    }
                                }
                            }
                        @endphp
                        <!-- Message Card -->
                        <div class="bug-msg-card @if($isInitial) is-initial @elseif($isAdmin) is-admin @else is-client @endif">
                            <div class="bug-msg-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold" style="font-size: 0.8125rem; @if($isInitial || $isAdmin) color: #002855; @else color: #0f172a; @endif">
                                        {{ $isAdmin ? 'Auditors.lv Atbalsts' : ($item->user_id === Auth::id() ? 'Jūs' : ($item->user->name ?? 'Jūs')) }}
                                    </span>
                                    @if($isInitial)
                                        <span class="badge font-monospace" style="font-size: 0.625rem; padding: 0.15rem 0.45rem; border-radius: 2px; background-color: #002855; color: #ffffff !important; letter-spacing: 0.5px;">
                                            <i class="fa-solid fa-flag me-1"></i>SĀKOTNĒJAIS PIETEIKUMS
                                        </span>
                                    @elseif($isAdmin)
                                        <span class="badge font-monospace" style="font-size: 0.625rem; padding: 0.15rem 0.4rem; border-radius: 2px; background-color: #002855; color: #ffffff !important;">
                                            ATBALSTS
                                        </span>
                                    @else
                                        <span class="badge font-monospace" style="font-size: 0.625rem; padding: 0.15rem 0.4rem; border-radius: 2px; background-color: #e2e8f0; color: #334155 !important;">
                                            KLIENTS
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="font-monospace" style="font-size: 0.7rem; color: #64748b;">
                                        {{ $item->created_at->format('d.m.Y H:i') }}
                                    </span>
                                    <span class="text-muted font-monospace" style="font-size: 0.65rem;">#{{ $index + 1 }}</span>
                                </div>
                            </div>
                            
                            <div class="bug-msg-body @if($isInitial) fw-medium @endif" @if($isInitial) style="font-size: 0.835rem;" @endif>{{ $item->message }}</div>

                            @if(!empty($images) || !empty($files))
                                <div class="bug-thumb-gallery">
                                    @foreach($images as $img)
                                        <div class="position-relative overflow-hidden" 
                                             title="{{ $img['name'] }} @if($img['size']) ({{ $img['size'] }}) @endif">
                                            <img src="{{ $img['url'] }}" 
                                                 alt="{{ $img['name'] }}" 
                                                 class="bug-thumb-img"
                                                 onclick='window.openImageLightbox(@json($allThreadImages), @json($img["url"]))'>
                                        </div>
                                    @endforeach
                                    @foreach($files as $doc)
                                        <a href="{{ $doc['url'] }}" target="_blank" download class="bug-file-chip">
                                            <i class="fa-solid fa-file text-primary"></i>
                                            <span class="text-truncate" style="max-width: 180px;">{{ $doc['name'] }}</span>
                                            @if(!empty($doc['size']))
                                                <span style="color: #64748b;">({{ $doc['size'] }})</span>
                                            @endif
                                            <i class="fa-solid fa-download ms-1" style="color: #64748b;"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if($isInitial && $selectedReport->items->count() > 1)
                            <div class="d-flex align-items-center gap-2 my-1">
                                <span class="flex-grow-1 border-top" style="border-color: #e2e8f0;"></span>
                                <span class="font-monospace" style="font-size: 0.675rem; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase;">
                                    <i class="fa-regular fa-comments me-1 text-primary"></i>Sarakste un atbildes ({{ $selectedReport->items->count() - 1 }})
                                </span>
                                <span class="flex-grow-1 border-top" style="border-color: #e2e8f0;"></span>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Reply Form Area (Clean and Borderless Inside Container) -->
                <div class="bug-reply-box">
                    <form wire:submit.prevent="sendReply">
                        <div class="mb-2">
                            <textarea class="form-control @error('replyMessage') is-invalid @enderror" 
                                      rows="2" 
                                      placeholder="Ierakstiet savu ziņu vai atbildi šeit..." 
                                      wire:model.defer="replyMessage"
                                      style="font-size: 0.8125rem; border-radius: 2px; border-color: #c4cdd5; color: #0f172a; min-height: 52px;"></textarea>
                            @error('replyMessage')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <label class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 cursor-pointer mb-0 py-1" style="border-radius: 2px; font-size: 0.75rem; height: 28px;">
                                    <i class="fa-solid fa-paperclip text-primary"></i>
                                    <span>Pievienot failus</span>
                                    <input type="file" multiple wire:model="replyAttachments" class="d-none" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip">
                                </label>
                                <span wire:loading wire:target="replyAttachments" class="small font-monospace" style="color: #64748b; font-size: 0.725rem;">
                                    <span class="spinner-border spinner-border-sm text-primary me-1"></span>Ielādē...
                                </span>
                                @if(!empty($replyAttachments))
                                    <span class="badge border font-monospace" style="border-radius: 2px; border-color: #cbd5e1; background-color: #f8fafc; color: #0f172a; font-size: 0.725rem;">
                                        {{ count($replyAttachments) }} @choice('fails|faili', count($replyAttachments))
                                    </span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm px-3 py-1 d-inline-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 2px; font-size: 0.775rem; height: 28px;" wire:loading.attr="disabled">
                                <span wire:loading wire:target="sendReply" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <i class="fa-solid fa-paper-plane text-white" wire:loading.remove wire:target="sendReply"></i>
                                <span class="text-white">Nosūtīt</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
