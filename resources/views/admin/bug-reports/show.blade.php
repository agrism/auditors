@extends('admin.layout.admin')

@section('content')
<div class="container-fluid px-0">
    @php
        $allThreadImages = [];
        foreach ($report->items as $msgItem) {
            if (!empty($msgItem->attachments) && is_array($msgItem->attachments)) {
                foreach ($msgItem->attachments as $att) {
                    $isImg = !empty($att['mime']) && str_starts_with($att['mime'], 'image/');
                    if (!$isImg && !empty($att['name'])) {
                        $ext = strtolower(pathinfo($att['name'], PATHINFO_EXTENSION));
                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
                    }
                    if ($isImg) {
                        $imgUrl = $att['url'] ?? \Illuminate\Support\Facades\Storage::disk('public')->url($att['path'] ?? '');
                        $allThreadImages[] = [
                            'url' => $imgUrl,
                            'name' => $att['name'] ?? basename($att['path'] ?? 'attels.jpg'),
                            'size' => !empty($att['size']) ? number_format($att['size'] / 1024, 1) . ' KB' : '',
                            'time' => $msgItem->created_at->format('d.m.Y H:i'),
                            'author' => $msgItem->is_admin_reply ? 'Auditors.lv Administrācija' : ($msgItem->user->name ?? 'Klients'),
                        ];
                    }
                }
            }
        }
    @endphp

    <!-- Top Navigation Bar -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.bug-reports.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 fw-medium" style="border-radius: 2px;">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Atpakaļ uz sarakstu</span>
            </a>
            <nav aria-label="breadcrumb" class="d-none d-sm-inline-block ms-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.bug-reports.index') }}" class="text-decoration-none fw-semibold text-primary">
                            <i class="fa-solid fa-headset me-1"></i>Saziņas un ziņojumi
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-muted font-monospace fw-semibold" aria-current="page">#{{ $report->id }}</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Status Update Form Button Trigger -->
            <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1.5 fw-medium" style="border-radius: 2px;" data-bs-toggle="collapse" data-bs-target="#adminStatusFormCollapse">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Mainīt statusu</span>
            </button>
        </div>
    </div>

    <!-- Collapsible Status Update Form -->
    <div class="collapse mb-3" id="adminStatusFormCollapse">
        <div class="card border shadow-sm p-3 bg-white" style="border-radius: 2px; border-color: #cbd5e1;">
            <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-sliders text-primary me-1"></i> Manuāla statusa maiņa</h6>
            <form method="POST" action="{{ route('admin.bug-reports.updateStatus', $report->id) }}" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-4 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Jaunais statuss</label>
                    <select name="status" class="form-select form-select-sm" required>
                        @foreach(\App\Enums\BugReportStatus::cases() as $statusEnum)
                            <option value="{{ $statusEnum->value }}" {{ $report->status_value === $statusEnum->value ? 'selected' : '' }}>
                                {{ $statusEnum->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 col-sm-6">
                    <label class="form-label small fw-semibold text-slate-700 mb-1">Piezīme / Komentārs (vēsturei)</label>
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Iemesls vai piezīme...">
                </div>
                <div class="col-md-3 col-sm-12">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold" style="border-radius: 2px;">
                        <i class="fa-solid fa-check me-1"></i> Saglabāt statusu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Thread Container -->
    <div class="bug-thread-wrap mb-4">
        <!-- Header Info Bar -->
        <div class="bug-thread-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge font-monospace" style="background-color: #002855; color: #ffffff !important; border-radius: 2px; font-size: 0.8rem; letter-spacing: 0.5px; padding: 0.25rem 0.55rem;">
                        PIETEIKUMS #{{ $report->id }}
                    </span>
                    @if($report->statuses && $report->statuses->count() > 0)
                        <span class="{{ $report->status_enum->badgeClass() }} user-select-none" 
                              role="button"
                              tabindex="0"
                              data-bs-toggle="collapse" 
                              data-bs-target="#adminStatusHistoryCollapse" 
                              aria-expanded="false" 
                              aria-controls="adminStatusHistoryCollapse"
                              title="Klikšķiniet, lai atvērtu statusu vēsturi ({{ $report->statuses->count() }})"
                              style="cursor: pointer;">
                            <i class="{{ $report->status_enum->icon() }}"></i>
                            <span>{{ $report->status_enum->label() }}</span>
                            <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.6rem; opacity: 0.75;"></i>
                        </span>
                    @else
                        <span class="{{ $report->status_enum->badgeClass() }}">
                            <i class="{{ $report->status_enum->icon() }}"></i> {{ $report->status_enum->label() }}
                        </span>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap font-monospace" style="font-size: 0.725rem; color: #64748b;">
                    <span><i class="fa-regular fa-calendar me-1"></i>{{ $report->created_at->format('d.m.Y H:i') }}</span>
                    <span>
                        <i class="fa-regular fa-user me-1 text-primary"></i>
                        @if($report->user)
                            <strong style="color: #0f172a;">{{ $report->user->name }}</strong> ({{ $report->user->email }})
                        @elseif(!empty($report->email))
                            <strong style="color: #0f172a;">{{ $report->email }}</strong>
                        @else
                            Viesis
                        @endif
                    </span>
                    @if(!empty($report->section))
                        <span><i class="fa-solid fa-folder me-1 text-primary"></i>{{ $report->section }}</span>
                    @endif
                    @if(!empty($report->url))
                        <a href="{{ $report->url }}" target="_blank" class="text-primary text-decoration-none fw-semibold">
                            <i class="fa-solid fa-link me-1"></i>URL <i class="fa-solid fa-arrow-up-right-from-square small"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Status History Timeline (Top = Oldest, Bottom = Newest) -->
            @if($report->statuses && $report->statuses->count() > 0)
                <!-- Collapsible Vertical Timeline -->
                <div class="collapse mt-2" id="adminStatusHistoryCollapse">
                    <div class="p-2 bg-light border-top border-bottom" style="border-color: #e2e8f0 !important;">
                        <div class="d-flex flex-column gap-1.5">
                            @foreach($report->statuses as $index => $stRecord)
                                <div class="d-flex align-items-center gap-2 font-monospace" style="font-size: 0.7rem;">
                                    <span class="text-muted" style="min-width: 20px;">#{{ $index + 1 }}</span>
                                    <span class="{{ $stRecord->status_enum->badgeClass() }}" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">
                                        {{ $stRecord->status_enum->label() }}
                                    </span>
                                    <span class="fw-semibold" style="color: #0f172a;">
                                        {{ $stRecord->created_at->format('d.m.Y H:i:s') }}
                                    </span>
                                    <span style="color: #64748b;">
                                        &bull; {{ $stRecord->user ? $stRecord->user->name : 'Sistēma' }}
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

        <!-- Thread Messages List -->
        <div class="bug-thread-messages">
            @foreach($report->items as $index => $item)
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
                            $url = $att['url'] ?? \Illuminate\Support\Facades\Storage::disk('public')->url($att['path'] ?? '');
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
                <div class="bug-msg-card @if($isInitial) is-initial @elseif($isAdmin) is-admin @else is-client @endif">
                    <!-- Item Header -->
                    <div class="bug-msg-header">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold" style="font-size: 0.8125rem; @if($isInitial || $isAdmin) color: #002855; @else color: #0f172a; @endif">
                                @if($isAdmin)
                                    <span>Auditors.lv Administrācija</span>
                                    @if($item->user)
                                        <span class="fw-normal" style="color: #64748b;">({{ $item->user->name }})</span>
                                    @endif
                                @else
                                    <span>{{ $item->user->name ?? ($report->email ?? 'Klients') }}</span>
                                @endif
                            </span>
                            @if($isInitial)
                                <span class="badge font-monospace" style="font-size: 0.625rem; padding: 0.15rem 0.45rem; border-radius: 2px; background-color: #002855; color: #ffffff !important; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-flag me-1"></i>SĀKOTNĒJAIS PIETEIKUMS
                                </span>
                            @elseif($isAdmin)
                                <span class="badge font-monospace" style="font-size: 0.625rem; padding: 0.15rem 0.4rem; border-radius: 2px; background-color: #002855; color: #ffffff !important;">
                                    ADMINISTRĀCIJA
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

                    <!-- Item Content -->
                    <div class="bug-msg-body @if($isInitial) fw-medium @endif" @if($isInitial) style="font-size: 0.835rem;" @endif>{{ $item->message }}</div>

                    <!-- Attachments -->
                    @if(!empty($images) || !empty($files))
                        <div class="bug-thumb-gallery">
                            @foreach($images as $img)
                                <div class="position-relative overflow-hidden" 
                                     title="{{ $img['name'] }} @if($img['size']) ({{ $img['size'] }}) @endif">
                                    <img src="{{ $img['url'] }}" 
                                         alt="{{ $img['name'] }}" 
                                         class="bug-thumb-img"
                                         onclick='openImageLightboxAdmin(@json($allThreadImages), @json($img["url"]))'>
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

                @if($isInitial && $report->items->count() > 1)
                    <div class="d-flex align-items-center gap-2 my-1">
                        <span class="flex-grow-1 border-top" style="border-color: #e2e8f0;"></span>
                        <span class="font-monospace" style="font-size: 0.675rem; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase;">
                            <i class="fa-regular fa-comments me-1 text-primary"></i>Sarakste un atbildes ({{ $report->items->count() - 1 }})
                        </span>
                        <span class="flex-grow-1 border-top" style="border-color: #e2e8f0;"></span>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Admin Reply Form (Clean and Borderless Inside Container) -->
        <div class="bug-reply-box">
            <form method="POST" action="{{ route('admin.bug-reports.reply', $report->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <textarea class="form-control @error('message') is-invalid @enderror" 
                              name="message" 
                              rows="3" 
                              placeholder="Ievadiet oficiālu atbildi klientam... (nosūtot atbildi, statuss automātiski tiks iestatīts kā 'Atbildēts')" 
                              required 
                              style="font-size: 0.8125rem; border-radius: 2px; border-color: #c4cdd5; color: #0f172a; min-height: 60px;">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <label class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 cursor-pointer mb-0 py-1" style="border-radius: 2px; font-size: 0.75rem; height: 28px;">
                            <i class="fa-solid fa-paperclip text-primary"></i>
                            <span>Pievienot failus</span>
                            <input type="file" name="attachments[]" multiple class="d-none" onchange="updateAdminFilesList(this)">
                        </label>
                        <span id="adminFilesSelectedBadge" class="badge border font-monospace d-none" style="border-radius: 2px; border-color: #cbd5e1; background-color: #f8fafc; color: #0f172a; font-size: 0.725rem;"></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm px-3 py-1 d-inline-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 2px; font-size: 0.775rem; height: 28px;">
                        <i class="fa-solid fa-paper-plane text-white"></i>
                        <span class="text-white">Nosūtīt atbildi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Lightbox Modal for Admin -->
<div class="modal fade" id="adminLightboxModal" tabindex="-1" aria-hidden="true" style="z-index: 1000005; background-color: rgba(15, 23, 42, 0.92); backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 94vw;">
        <div class="modal-content border shadow-2xl bg-dark text-white overflow-hidden" style="border-radius: 2px; border-color: #334155 !important;">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2 px-3 px-md-4 bg-black bg-opacity-50">
                <div class="d-flex align-items-center gap-2 text-truncate me-3">
                    <i class="fa-regular fa-image text-primary fs-5"></i>
                    <span class="fw-semibold text-truncate small" id="adminLightboxImageName" style="max-width: 450px;"></span>
                    <span class="badge bg-secondary bg-opacity-75 text-white font-monospace small ms-2 px-2 py-1" id="adminLightboxCounter" style="border-radius: 2px;"></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="adminLightboxDownloadBtn" download class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-1.5 px-2.5 py-1" style="border-radius: 2px;" title="Lejupielādēt attēlu">
                        <i class="fa-solid fa-download"></i>
                        <span class="d-none d-sm-inline">Lejupielādēt</span>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-light px-2.5 py-1" style="border-radius: 2px;" data-bs-dismiss="modal" aria-label="Aizvērt">
                        <i class="fa-solid fa-xmark fs-6"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body p-0 position-relative d-flex align-items-center justify-content-center bg-black bg-opacity-90" style="min-height: 480px; max-height: 82vh;">
                <button type="button" 
                        id="adminLightboxPrevBtn" 
                        onclick="prevAdminLightboxImage()" 
                        class="btn btn-dark bg-opacity-75 border border-secondary border-opacity-50 text-white position-absolute start-0 top-50 translate-middle-y ms-2 ms-md-4 d-flex align-items-center justify-content-center shadow-lg"
                        style="width: 42px; height: 42px; border-radius: 2px; z-index: 1055; font-size: 1.1rem; transition: all 0.2s;"
                        title="Iepriekšējā bilde">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <div class="p-2 p-md-3 w-100 h-100 d-flex align-items-center justify-content-center user-select-none">
                    <img id="adminLightboxMainImage" 
                         src="" 
                         alt="" 
                         class="img-fluid shadow-lg" 
                         style="max-height: 76vh; max-width: 100%; object-fit: contain; border-radius: 2px;">
                </div>

                <button type="button" 
                        id="adminLightboxNextBtn" 
                        onclick="nextAdminLightboxImage()" 
                        class="btn btn-dark bg-opacity-75 border border-secondary border-opacity-50 text-white position-absolute end-0 top-50 translate-middle-y me-2 me-md-4 d-flex align-items-center justify-content-center shadow-lg"
                        style="width: 42px; height: 42px; border-radius: 2px; z-index: 1055; font-size: 1.1rem; transition: all 0.2s;"
                        title="Nākamā bilde">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 py-2 px-3 px-md-4 bg-black bg-opacity-50 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="small text-white-50" id="adminLightboxMeta"></div>
                <div class="d-flex align-items-center gap-1.5 flex-wrap" id="adminLightboxThumbnails"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function updateAdminFilesList(input) {
        var badge = document.getElementById('adminFilesSelectedBadge');
        if (input && input.files && input.files.length > 0) {
            badge.innerText = input.files.length + ' faili atlasīti';
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }

    var adminGallery = [];
    var adminGalleryIndex = 0;

    function openImageLightboxAdmin(gallery, activeUrl) {
        if (!gallery || !gallery.length) return;
        adminGallery = gallery;
        var foundIdx = gallery.findIndex(function(img) { return img.url === activeUrl; });
        adminGalleryIndex = foundIdx >= 0 ? foundIdx : 0;
        renderAdminLightboxActive();

        var modalEl = document.getElementById('adminLightboxModal');
        if (modalEl && window.bootstrap && window.bootstrap.Modal) {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    function renderAdminLightboxActive() {
        if (!adminGallery || !adminGallery.length) return;
        var img = adminGallery[adminGalleryIndex];
        if (!img) return;

        var mainImg = document.getElementById('adminLightboxMainImage');
        var nameEl = document.getElementById('adminLightboxImageName');
        var counterEl = document.getElementById('adminLightboxCounter');
        var downloadBtn = document.getElementById('adminLightboxDownloadBtn');
        var metaEl = document.getElementById('adminLightboxMeta');
        var prevBtn = document.getElementById('adminLightboxPrevBtn');
        var nextBtn = document.getElementById('adminLightboxNextBtn');

        if (mainImg) { mainImg.src = img.url; mainImg.alt = img.name || 'Attēls'; }
        if (nameEl) nameEl.textContent = img.name || 'Attēls';
        if (counterEl) {
            counterEl.textContent = (adminGalleryIndex + 1) + ' / ' + adminGallery.length;
            counterEl.style.display = adminGallery.length > 1 ? 'inline-block' : 'none';
        }
        if (downloadBtn) {
            downloadBtn.href = img.url;
            downloadBtn.setAttribute('download', img.name || 'attels');
        }
        if (metaEl) {
            var metaParts = [];
            if (img.author) metaParts.push(img.author);
            if (img.time) metaParts.push(img.time);
            if (img.size) metaParts.push(img.size);
            metaEl.textContent = metaParts.join(' • ');
        }

        var hasMultiple = adminGallery.length > 1;
        if (prevBtn) prevBtn.style.display = hasMultiple ? 'flex' : 'none';
        if (nextBtn) nextBtn.style.display = hasMultiple ? 'flex' : 'none';

        var thumbsEl = document.getElementById('adminLightboxThumbnails');
        if (thumbsEl) {
            if (hasMultiple && adminGallery.length <= 15) {
                var html = '';
                adminGallery.forEach(function(item, idx) {
                    var isActive = idx === adminGalleryIndex;
                    html += '<button type="button" onclick="setAdminLightboxIndex(' + idx + ')" class="btn p-0 rounded-1 overflow-hidden ' + (isActive ? 'border border-2 border-primary' : 'opacity-50') + '" style="width: 36px; height: 26px; transition: all 0.2s;">' +
                                '<img src="' + item.url + '" alt="" style="width: 100%; height: 100%; object-fit: cover;">' +
                            '</button>';
                });
                thumbsEl.innerHTML = html;
            } else {
                thumbsEl.innerHTML = '';
            }
        }
    }

    function prevAdminLightboxImage() {
        if (!adminGallery || adminGallery.length <= 1) return;
        adminGalleryIndex = (adminGalleryIndex - 1 + adminGallery.length) % adminGallery.length;
        renderAdminLightboxActive();
    }

    function nextAdminLightboxImage() {
        if (!adminGallery || adminGallery.length <= 1) return;
        adminGalleryIndex = (adminGalleryIndex + 1) % adminGallery.length;
        renderAdminLightboxActive();
    }

    function setAdminLightboxIndex(idx) {
        if (!adminGallery || idx < 0 || idx >= adminGallery.length) return;
        adminGalleryIndex = idx;
        renderAdminLightboxActive();
    }
</script>
@endsection
