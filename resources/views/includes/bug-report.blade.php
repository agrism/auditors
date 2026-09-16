@auth
<style>
    /* Sticky Bug Report Button Styles */
    .eds-bug-report-wrapper {
        position: fixed !important;
        bottom: 24px !important;
        right: 24px !important;
        z-index: 999999 !important;
        display: block !important;
    }
    .eds-bug-report-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.55rem !important;
        height: 40px !important;
        padding: 0 14px !important;
        background: #002855 !important;
        color: #ffffff !important;
        border: 1px solid #001a38 !important;
        border-radius: 2px !important;
        box-shadow: 0 2px 8px rgba(0, 40, 85, 0.25) !important;
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        letter-spacing: 0.01em !important;
        cursor: pointer !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
        outline: none !important;
        user-select: none !important;
    }
    .eds-bug-report-btn:hover {
        background: #001a38 !important;
        box-shadow: 0 4px 12px rgba(0, 40, 85, 0.35) !important;
        color: #ffffff !important;
    }
    .eds-bug-report-btn:active {
        background: #001124 !important;
    }
    .eds-bug-report-icon-wrapper {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1rem !important;
    }
    .eds-bug-report-label {
        white-space: nowrap !important;
        line-height: 1 !important;
        color: #ffffff !important;
    }
    @media (max-width: 576px) {
        .eds-bug-report-wrapper {
            bottom: 16px !important;
            right: 16px !important;
        }
        .eds-bug-report-btn {
            width: 40px !important;
            height: 40px !important;
            padding: 0 !important;
            justify-content: center !important;
        }
        .eds-bug-report-label {
            display: none !important;
        }
    }
    .eds-bug-modal .modal-content {
        border-radius: 2px !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
    }

    /* File Dropzone & Previews */
    .eds-dropzone {
        border: 1px dashed #cbd5e1;
        border-radius: 2px;
        background-color: #f8fafc;
        padding: 0.85rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .eds-dropzone:hover, .eds-dropzone.dragover {
        border-color: #002855;
        background-color: #f1f5f9;
    }
    .eds-file-preview-list {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 0.5rem;
    }
    .eds-file-preview-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.35rem 0.6rem;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 2px;
        font-size: 0.8125rem;
    }
    .eds-file-thumb {
        width: 28px;
        height: 28px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid #cbd5e1;
    }
</style>

<!-- Floating Sticky Bug Report Button -->
<div class="eds-bug-report-wrapper" id="edsBugReportWrapper">
    <button type="button" 
            class="eds-bug-report-btn" 
            id="edsBugReportBtn"
            onclick="openBugReportModal()" 
            title="Ziņot par kļūdu vai ieteikumi" 
            aria-label="Ziņot par kļūdu vai ieteikumi">
        <span class="eds-bug-report-icon-wrapper">
            <i class="fa-solid fa-headset text-white"></i>
        </span>
        <span class="eds-bug-report-label">Ziņot par kļūdu / ieteikumi</span>
    </button>
</div>

<!-- Bug Report Modal -->
<div class="modal fade eds-bug-modal" id="bugReportModal" tabindex="-1" aria-labelledby="bugReportModalLabel" aria-hidden="true" style="z-index: 1000000;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border shadow-lg" style="border-radius: 2px; overflow: hidden; border-color: #cbd5e1;">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white border-0 py-2.5 px-3 px-md-4 align-items-center" style="border-radius: 2px 2px 0 0;">
                <h5 class="modal-title fw-bold text-white mb-0 d-flex align-items-center gap-2" id="bugReportModalLabel" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-headset text-white"></i>
                    <span>{{ __('Ziņot par kļūdu vai ieteikumi') }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Aizvērt" onclick="closeBugReportModal()"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-3 p-md-4 bg-light">
                <form id="bugReportForm" onsubmit="submitBugReport(event)" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="url" id="bugReportUrl" value="">
                    <input type="hidden" name="section" id="bugReportSection" value="">

                    <!-- User and Page Context Details Bar -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom font-monospace" style="font-size: 0.75rem; color: #64748b; border-color: #cbd5e1 !important;">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span><i class="fa-regular fa-user text-primary me-1"></i><strong style="color: #0f172a;">{{ Auth::user()->name }}</strong></span>
                            <span id="bugReportCompanyDisplay" class="fw-semibold text-truncate ms-2" style="max-width: 240px; color: #475569;"></span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <i class="fa-solid fa-folder text-primary me-1"></i>
                            <span id="bugReportSectionBadge" class="fw-semibold" style="color: #0f172a;">-</span>
                        </div>
                    </div>

                    <!-- Alerts Container -->
                    <div id="bugReportAlertSuccess" class="alert alert-success d-none d-flex align-items-center gap-2 mb-3 border" role="alert" style="border-radius: 2px; border-color: #86efac; background-color: #f0fdf4;">
                        <i class="fa-solid fa-circle-check text-success fs-5"></i>
                        <div class="text-dark small">
                            <strong>Paldies!</strong> Jūsu ziņojums ir veiksmīgi nosūtīts un reģistrēts.
                        </div>
                    </div>

                    <div id="bugReportAlertError" class="alert alert-danger d-none d-flex align-items-center gap-2 mb-3 border" role="alert" style="border-radius: 2px; border-color: #fca5a5; background-color: #fef2f2;">
                        <i class="fa-solid fa-circle-exclamation text-danger fs-5"></i>
                        <div id="bugReportErrorMessage" class="text-dark small">
                            Kļūda nosūtot ziņojumu. Lūdzu, mēģiniet vēlreiz.
                        </div>
                    </div>

                    <!-- Optional Email Field -->
                    <div class="mb-3">
                        <label for="bugReportEmail" class="form-label fw-bold text-dark small mb-1">
                            Cits e-pasts paziņojumam <span class="text-muted fw-normal">(neobligāti)</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white text-muted border-end-0" style="border-radius: 2px 0 0 2px; border-color: #c4cdd5;">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <input type="email" 
                                   class="form-control border-start-0" 
                                   id="bugReportEmail" 
                                   name="email" 
                                   placeholder="Ievadiet e-pastu, ja vēlaties atbildi uz citu adresi" 
                                   style="border-radius: 0 2px 2px 0; font-size: 0.8125rem; border-color: #c4cdd5;">
                        </div>
                        <div class="form-text small text-muted mt-1" style="font-size: 0.75rem;">
                            Pēc noklusējuma atbilde un paziņojums tiks sūtīts uz jūsu profila e-pastu ({{ Auth::user()->email }}).
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="bugReportDescription" class="form-label fw-bold text-dark small mb-1">
                            Kļūdas apraksts vai ieteikums <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                  id="bugReportDescription" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Aprakstiet situāciju, pamanīto kļūdu vai jūsu ieteikumu sistēmas uzlabošanai... (varat arī ielīmēt ekrānuzņēmumu ar Ctrl+V)" 
                                  required 
                                  style="resize: vertical; font-size: 0.8125rem; border-radius: 2px; border-color: #c4cdd5;"></textarea>
                        <div class="form-text small text-muted mt-1" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-info-circle me-1 text-primary"></i>Jo detalizētāks apraksts, jo ātrāk mūsu komanda spēs to izskatīt un atrisināt.
                        </div>
                    </div>

                    <!-- File Attachments / Screenshots Section -->
                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark small mb-1">
                            Pievienot failus vai ekrānuzņēmumus <span class="text-muted fw-normal">(neobligāti)</span>
                        </label>
                        
                        <div class="eds-dropzone" id="edsBugDropzone" onclick="document.getElementById('bugReportFileInput').click()">
                            <input type="file" 
                                   id="bugReportFileInput" 
                                   multiple 
                                   accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip" 
                                   style="display: none;" 
                                   onchange="handleBugFilesSelect(this.files)">
                            
                            <div class="d-flex align-items-center justify-content-center gap-2 text-muted mb-1">
                                <i class="fa-solid fa-cloud-arrow-up fs-5 text-primary"></i>
                                <span class="fw-semibold text-dark small">Pievienot failu / ekrānuzņēmumu</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                Noklikšķiniet, ievelciet failu šeit vai ielīmējiet ekrānuzņēmumu ar <kbd class="bg-light text-dark border px-1" style="border-radius: 2px;">Ctrl+V</kbd> / <kbd class="bg-light text-dark border px-1" style="border-radius: 2px;">Cmd+V</kbd>
                            </div>
                        </div>

                        <!-- Selected Files Preview Container -->
                        <div class="eds-file-preview-list" id="edsBugFilesPreview"></div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-white border-top py-2.5 px-3 px-md-4 d-flex justify-content-between" style="border-color: #cbd5e1;">
                <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal" onclick="closeBugReportModal()" style="border-radius: 2px;">
                    Atcelt
                </button>
                <button type="button" class="btn btn-primary px-3.5 d-inline-flex align-items-center gap-1.5 fw-semibold" id="bugReportSubmitBtn" onclick="submitBugReport(event)" style="border-radius: 2px;">
                    <span id="bugReportBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <i class="fa-solid fa-paper-plane text-white" id="bugReportBtnIcon"></i>
                    <span id="bugReportBtnText" class="text-white">Nosūtīt ziņojumu</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var bugReportSelectedFiles = [];

    function detectCurrentPageContext() {
        // 1. Detect Main Section Title from Topbar or Document Title
        var topbarTitle = document.querySelector('.eds-topbar-title');
        var sectionTitle = '';
        if (topbarTitle && topbarTitle.innerText.trim()) {
            sectionTitle = topbarTitle.innerText.trim();
        } else if (document.title) {
            sectionTitle = document.title.replace(/^Auditors\.lv\s*::\s*/i, '').trim();
        }

        // 2. Detect Active Menu/Submenu
        var activeSubmenu = document.querySelector('.eds-submenu-link.active span');
        var activeMenu = document.querySelector('.eds-menu-link.active span');
        var menuName = activeSubmenu ? activeSubmenu.innerText.trim() : (activeMenu ? activeMenu.innerText.trim() : '');

        var primarySection = sectionTitle || menuName || 'Klientu portāls';

        // 3. Detect Specific Subview / Form / Modal State in SPA
        var subviewDetail = '';

        // Check if shortcut invoice modal is open
        var shortcutModal = document.getElementById('shortcut_invoice');
        if (shortcutModal && (shortcutModal.classList.contains('show') || shortcutModal.style.display === 'block')) {
            subviewDetail = 'Ātrais rēķins (forma)';
        }

        // Check if other modals are open
        var copyModal = document.getElementById('copy_invoice');
        if (copyModal && (copyModal.classList.contains('show') || copyModal.style.display === 'block')) {
            subviewDetail = 'Rēķina kopēšana';
        }

        var deleteModal = document.getElementById('delete_invoice');
        if (deleteModal && (deleteModal.classList.contains('show') || deleteModal.style.display === 'block')) {
            subviewDetail = 'Rēķina dzēšanas logs';
        }

        var lockModal = document.getElementById('lock_invoice');
        if (lockModal && (lockModal.classList.contains('show') || lockModal.style.display === 'block')) {
            subviewDetail = 'Rēķina slēgšanas logs';
        }

        // Check if full InvoiceForm is open
        var invoiceFormBtn = document.querySelector('button[wire\\:click*="saveInvoice"], button[wire\\:click*="closeInvoiceForm"]');
        var invoiceNumberInput = document.querySelector('input[wire\\:model*="invoice.number"], input[name="number"]');
        if (!subviewDetail && (invoiceFormBtn || invoiceNumberInput)) {
            var invNum = invoiceNumberInput && invoiceNumberInput.value ? 'Nr. ' + invoiceNumberInput.value : '';
            subviewDetail = invNum ? 'Rēķina forma (' + invNum + ')' : 'Jauna rēķina forma';
        }

        // Check if Cash Expense form is open
        var cashExpenseForm = document.querySelector('button[wire\\:click*="saveCashExpense"], button[wire\\:click*="closeCashExpense"]');
        if (!subviewDetail && cashExpenseForm) {
            subviewDetail = 'Avansa norēķina forma';
        }

        // Check if Vacation form is open
        var vacationForm = document.querySelector('[wire\\:id*="vacation"] form, button[wire\\:click*="saveVacation"]');
        if (!subviewDetail && vacationForm) {
            subviewDetail = 'Atvaļinājuma pieteikuma forma';
        }

        // Assemble Full Context String
        var fullLocation = primarySection;
        if (subviewDetail && !primarySection.toLowerCase().includes(subviewDetail.toLowerCase())) {
            fullLocation += ' › ' + subviewDetail;
        }

        // 4. Detect Active Company
        var companyName = '';
        var companyTopbar = document.querySelector('.eds-topbar-mainval');
        if (companyTopbar && companyTopbar.innerText.trim()) {
            companyName = companyTopbar.innerText.trim();
        } else {
            var companyHeader = document.querySelector('.dash-hero h3');
            if (companyHeader && companyHeader.innerText.trim()) {
                companyName = companyHeader.innerText.trim();
            }
        }

        return {
            section: fullLocation,
            company: companyName || 'Nav atlasīts',
            url: window.location.href,
            pathname: window.location.pathname + window.location.search
        };
    }

    function openBugReportModal() {
        var context = detectCurrentPageContext();

        var urlInput = document.getElementById('bugReportUrl');
        var sectionInput = document.getElementById('bugReportSection');
        var sectionBadge = document.getElementById('bugReportSectionBadge');
        var companyDisplay = document.getElementById('bugReportCompanyDisplay');

        if (urlInput) urlInput.value = context.url;
        if (sectionInput) sectionInput.value = context.section + (context.company !== 'Nav atlasīts' ? ' [' + context.company + ']' : '');
        if (sectionBadge) {
            sectionBadge.innerText = context.section;
            sectionBadge.title = context.section;
        }
        if (companyDisplay) {
            companyDisplay.innerText = context.company;
            companyDisplay.title = context.company;
        }

        // Hide alerts
        var successAlert = document.getElementById('bugReportAlertSuccess');
        var errorAlert = document.getElementById('bugReportAlertError');
        if (successAlert) successAlert.classList.add('d-none');
        if (errorAlert) errorAlert.classList.add('d-none');

        // Open modal via Bootstrap 5 or jQuery
        var modalEl = document.getElementById('bugReportModal');
        if (window.bootstrap && window.bootstrap.Modal) {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else if (window.jQuery && typeof jQuery.fn.modal === 'function') {
            jQuery('#bugReportModal').modal('show');
        } else {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            document.body.classList.add('modal-open');
        }
        
        setTimeout(function() {
            var desc = document.getElementById('bugReportDescription');
            if (desc) desc.focus();
        }, 300);
    }

    function closeBugReportModal() {
        var modalEl = document.getElementById('bugReportModal');
        if (window.bootstrap && window.bootstrap.Modal) {
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        } else if (window.jQuery && typeof jQuery.fn.modal === 'function') {
            jQuery('#bugReportModal').modal('hide');
        } else {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    }

    function handleBugFilesSelect(files) {
        if (!files || !files.length) return;
        for (var i = 0; i < files.length; i++) {
            bugReportSelectedFiles.push(files[i]);
        }
        renderBugFilesPreview();
    }

    function removeBugFile(index) {
        bugReportSelectedFiles.splice(index, 1);
        renderBugFilesPreview();
    }

    function renderBugFilesPreview() {
        var container = document.getElementById('edsBugFilesPreview');
        if (!container) return;
        container.innerHTML = '';

        bugReportSelectedFiles.forEach(function(file, idx) {
            var item = document.createElement('div');
            item.className = 'eds-file-preview-item';

            var left = document.createElement('div');
            left.className = 'd-flex align-items-center gap-2 min-w-0';

            var isImg = file.type.startsWith('image/');
            if (isImg) {
                var img = document.createElement('img');
                img.className = 'eds-file-thumb';
                img.src = URL.createObjectURL(file);
                left.appendChild(img);
            } else {
                var icon = document.createElement('i');
                icon.className = 'fa-solid fa-file text-primary fs-5';
                left.appendChild(icon);
            }

            var info = document.createElement('div');
            info.className = 'text-truncate';
            var sizeKb = (file.size / 1024).toFixed(1);
            info.innerHTML = '<span class="fw-semibold text-dark text-truncate d-block" style="max-width: 280px;">' + file.name + '</span><span class="text-muted" style="font-size: 0.725rem;">' + sizeKb + ' KB</span>';
            left.appendChild(info);

            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-link text-danger p-0 ms-2 text-decoration-none';
            removeBtn.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
            removeBtn.onclick = function() { removeBugFile(idx); };

            item.appendChild(left);
            item.appendChild(removeBtn);
            container.appendChild(item);
        });
    }

    // Drag & Drop event handlers on dropzone
    document.addEventListener('DOMContentLoaded', function() {
        var dropzone = document.getElementById('edsBugDropzone');
        if (dropzone) {
            ['dragenter', 'dragover'].forEach(function(eventName) {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(function(eventName) {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                }, false);
            });

            dropzone.addEventListener('drop', function(e) {
                var dt = e.dataTransfer;
                var files = dt.files;
                handleBugFilesSelect(files);
            }, false);
        }

        // Global Paste screenshot handler in modal
        var modalEl = document.getElementById('bugReportModal');
        if (modalEl) {
            modalEl.addEventListener('paste', function(e) {
                var items = (e.clipboardData || e.originalEvent.clipboardData).items;
                for (var i = 0; i < items.length; i++) {
                    if (items[i].kind === 'file') {
                        var blob = items[i].getAsFile();
                        if (blob) {
                            var filename = 'ekranuznemums_' + (new Date().toISOString().replace(/[:.-]/g, '_')) + '.png';
                            var file = new File([blob], filename, { type: blob.type });
                            bugReportSelectedFiles.push(file);
                            renderBugFilesPreview();
                        }
                    }
                }
            });
        }
    });

    function submitBugReport(e) {
        if (e) e.preventDefault();
        
        var form = document.getElementById('bugReportForm');
        var descInput = document.getElementById('bugReportDescription');
        var submitBtn = document.getElementById('bugReportSubmitBtn');
        var spinner = document.getElementById('bugReportBtnSpinner');
        var icon = document.getElementById('bugReportBtnIcon');
        var btnText = document.getElementById('bugReportBtnText');
        var successAlert = document.getElementById('bugReportAlertSuccess');
        var errorAlert = document.getElementById('bugReportAlertError');
        var errorMessage = document.getElementById('bugReportErrorMessage');

        var description = descInput ? descInput.value.trim() : '';
        if (!description) {
            if (descInput) descInput.focus();
            if (errorAlert && errorMessage) {
                errorMessage.innerText = 'Lūdzu, ievadiet aprakstu vai ieteikumu.';
                errorAlert.classList.remove('d-none');
            }
            return;
        }

        // Loading state
        if (submitBtn) submitBtn.disabled = true;
        if (spinner) spinner.classList.remove('d-none');
        if (icon) icon.classList.add('d-none');
        if (btnText) btnText.innerText = 'Sūta...';
        if (successAlert) successAlert.classList.add('d-none');
        if (errorAlert) errorAlert.classList.add('d-none');

        var context = detectCurrentPageContext();
        var formData = new FormData(form);
        formData.set('url', window.location.href);
        formData.set('section', context.section + (context.company !== 'Nav atlasīts' ? ' [' + context.company + ']' : ''));

        // Append files from bugReportSelectedFiles array
        formData.delete('attachments[]');
        bugReportSelectedFiles.forEach(function(file) {
            formData.append('attachments[]', file);
        });

        fetch('{{ route("bug-reports.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(response) {
            return response.json().then(function(data) {
                return { status: response.status, body: data };
            });
        })
        .then(function(res) {
            if (res.status === 200 && res.body.success) {
                if (successAlert) {
                    successAlert.classList.remove('d-none');
                }
                if (descInput) descInput.value = '';
                var emailInput = document.getElementById('bugReportEmail');
                if (emailInput) emailInput.value = '';
                
                // Clear attached files
                bugReportSelectedFiles = [];
                renderBugFilesPreview();

                // Immediately trigger Livewire components to refresh list and counters
                if (window.Livewire) {
                    window.Livewire.emit('refreshBugReports');
                    window.Livewire.emit('bugReportCreated');
                    window.Livewire.emit('unreadBugReportsUpdated');
                }
                
                setTimeout(function() {
                    closeBugReportModal();
                    if (window.Livewire) {
                        window.Livewire.emit('refreshBugReports');
                        window.Livewire.emit('unreadBugReportsUpdated');
                    }
                }, 1000);
            } else {
                var err = res.body.message || (res.body.errors ? Object.values(res.body.errors).flat().join('<br>') : 'Radās kļūda.');
                if (errorAlert && errorMessage) {
                    errorMessage.innerHTML = err;
                    errorAlert.classList.remove('d-none');
                }
            }
        })
        .catch(function(err) {
            if (errorAlert && errorMessage) {
                errorMessage.innerText = 'Savienojuma kļūda. Lūdzu, mēģiniet vēlreiz.';
                errorAlert.classList.remove('d-none');
            }
        })
        .finally(function() {
            if (submitBtn) submitBtn.disabled = false;
            if (spinner) spinner.classList.add('d-none');
            if (icon) icon.classList.remove('d-none');
            if (btnText) btnText.innerText = 'Nosūtīt ziņojumu';
        });
    }

    /* ==================== GLOBAL LIGHTBOX IMAGE CAROUSEL ==================== */
    window.currentLightboxGallery = [];
    window.currentLightboxIndex = 0;
    window.lightboxModalInstance = null;

    window.openImageLightbox = function(gallery, activeUrl) {
        if (!gallery || !gallery.length) return;
        window.currentLightboxGallery = gallery;
        
        var foundIdx = gallery.findIndex(function(img) { return img.url === activeUrl; });
        window.currentLightboxIndex = foundIdx >= 0 ? foundIdx : 0;
        
        window.renderLightboxActive();
        
        var modalEl = document.getElementById('imageLightboxModal');
        if (modalEl) {
            if (window.bootstrap && window.bootstrap.Modal) {
                window.lightboxModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                window.lightboxModalInstance.show();
            } else if (window.jQuery && typeof jQuery.fn.modal === 'function') {
                jQuery('#imageLightboxModal').modal('show');
            } else {
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                document.body.classList.add('modal-open');
            }
        }
    };

    window.renderLightboxActive = function() {
        if (!window.currentLightboxGallery || !window.currentLightboxGallery.length) return;
        var img = window.currentLightboxGallery[window.currentLightboxIndex];
        if (!img) return;

        var mainImg = document.getElementById('lightboxMainImage');
        var nameEl = document.getElementById('lightboxImageName');
        var counterEl = document.getElementById('lightboxCounter');
        var downloadBtn = document.getElementById('lightboxDownloadBtn');
        var metaEl = document.getElementById('lightboxMeta');
        var prevBtn = document.getElementById('lightboxPrevBtn');
        var nextBtn = document.getElementById('lightboxNextBtn');

        if (mainImg) {
            mainImg.src = img.url;
            mainImg.alt = img.name || 'Attēls';
        }
        if (nameEl) nameEl.textContent = img.name || 'Attēls';
        if (counterEl) {
            counterEl.textContent = (window.currentLightboxIndex + 1) + ' / ' + window.currentLightboxGallery.length;
            counterEl.style.display = window.currentLightboxGallery.length > 1 ? 'inline-block' : 'none';
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

        var hasMultiple = window.currentLightboxGallery.length > 1;
        if (prevBtn) prevBtn.style.display = hasMultiple ? 'flex' : 'none';
        if (nextBtn) nextBtn.style.display = hasMultiple ? 'flex' : 'none';

        // Render mini thumbnails preview
        var thumbsEl = document.getElementById('lightboxThumbnails');
        if (thumbsEl) {
            if (hasMultiple && window.currentLightboxGallery.length <= 15) {
                var html = '';
                window.currentLightboxGallery.forEach(function(item, idx) {
                    var isActive = idx === window.currentLightboxIndex;
                    html += '<button type="button" onclick="setLightboxIndex(' + idx + ')" class="btn p-0 rounded-1 overflow-hidden ' + (isActive ? 'border border-2 border-primary' : 'opacity-50') + '" style="width: 36px; height: 26px; transition: all 0.2s;">' +
                                '<img src="' + item.url + '" alt="" style="width: 100%; height: 100%; object-fit: cover;">' +
                            '</button>';
                });
                thumbsEl.innerHTML = html;
            } else {
                thumbsEl.innerHTML = '';
            }
        }
    };

    window.prevLightboxImage = function() {
        if (!window.currentLightboxGallery || window.currentLightboxGallery.length <= 1) return;
        window.currentLightboxIndex = (window.currentLightboxIndex - 1 + window.currentLightboxGallery.length) % window.currentLightboxGallery.length;
        window.renderLightboxActive();
    };

    window.nextLightboxImage = function() {
        if (!window.currentLightboxGallery || window.currentLightboxGallery.length <= 1) return;
        window.currentLightboxIndex = (window.currentLightboxIndex + 1) % window.currentLightboxGallery.length;
        window.renderLightboxActive();
    };

    window.setLightboxIndex = function(idx) {
        if (!window.currentLightboxGallery || idx < 0 || idx >= window.currentLightboxGallery.length) return;
        window.currentLightboxIndex = idx;
        window.renderLightboxActive();
    };

    // Global keyboard listener for Left/Right arrow navigation
    if (typeof window !== 'undefined' && !window.__lightboxKeyboardAttached) {
        window.__lightboxKeyboardAttached = true;
        document.addEventListener('keydown', function(e) {
            var modalEl = document.getElementById('imageLightboxModal');
            if (modalEl && modalEl.classList.contains('show')) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    window.prevLightboxImage();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    window.nextLightboxImage();
                }
            }
        });
    }
</script>

<!-- Global Lightbox Image Carousel Modal Structure -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-labelledby="imageLightboxModalLabel" aria-hidden="true" style="z-index: 1000005; background-color: rgba(15, 23, 42, 0.92); backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 94vw;">
        <div class="modal-content border shadow-2xl bg-dark text-white overflow-hidden" style="border-radius: 2px; border-color: #334155 !important;">
            <!-- Modal Header -->
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-2 px-3 px-md-4 bg-black bg-opacity-50">
                <div class="d-flex align-items-center gap-2 text-truncate me-3">
                    <i class="fa-regular fa-image text-primary fs-5"></i>
                    <span class="fw-semibold text-truncate small" id="lightboxImageName" style="max-width: 450px;"></span>
                    <span class="badge bg-secondary bg-opacity-75 text-white font-monospace small ms-2 px-2 py-1" id="lightboxCounter" style="border-radius: 2px;"></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="lightboxDownloadBtn" download class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-1.5 px-2.5 py-1" style="border-radius: 2px;" title="Lejupielādēt attēlu">
                        <i class="fa-solid fa-download"></i>
                        <span class="d-none d-sm-inline">Lejupielādēt</span>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-light px-2.5 py-1" style="border-radius: 2px;" data-bs-dismiss="modal" aria-label="Aizvērt" title="Aizvērt (Esc)">
                        <i class="fa-solid fa-xmark fs-6"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body with Carousel -->
            <div class="modal-body p-0 position-relative d-flex align-items-center justify-content-center bg-black bg-opacity-90" style="min-height: 480px; max-height: 82vh;">
                <!-- Left Nav Arrow -->
                <button type="button" 
                        id="lightboxPrevBtn" 
                        onclick="prevLightboxImage()" 
                        class="btn btn-dark bg-opacity-75 border border-secondary border-opacity-50 text-white position-absolute start-0 top-50 translate-middle-y ms-2 ms-md-4 d-flex align-items-center justify-content-center shadow-lg"
                        style="width: 42px; height: 42px; border-radius: 2px; z-index: 1055; font-size: 1.1rem; transition: all 0.2s;"
                        title="Iepriekšējā bilde (Kreisā bultiņa)">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <!-- Main Image Display -->
                <div class="p-2 p-md-3 w-100 h-100 d-flex align-items-center justify-content-center user-select-none">
                    <img id="lightboxMainImage" 
                         src="" 
                         alt="" 
                         class="img-fluid shadow-lg" 
                         style="max-height: 76vh; max-width: 100%; object-fit: contain; border-radius: 2px; transition: transform 0.2s ease;">
                </div>

                <!-- Right Nav Arrow -->
                <button type="button" 
                        id="lightboxNextBtn" 
                        onclick="nextLightboxImage()" 
                        class="btn btn-dark bg-opacity-75 border border-secondary border-opacity-50 text-white position-absolute end-0 top-50 translate-middle-y me-2 me-md-4 d-flex align-items-center justify-content-center shadow-lg"
                        style="width: 42px; height: 42px; border-radius: 2px; z-index: 1055; font-size: 1.1rem; transition: all 0.2s;"
                        title="Nākamā bilde (Labā bultiņa)">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <!-- Modal Footer / Meta & Thumbnails -->
            <div class="modal-footer border-top border-secondary border-opacity-25 py-2 px-3 px-md-4 bg-black bg-opacity-50 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="small text-white-50" id="lightboxMeta"></div>
                <div class="d-flex align-items-center gap-1.5 flex-wrap" id="lightboxThumbnails"></div>
            </div>
        </div>
    </div>
</div>
@endauth
