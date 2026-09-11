@php
    $successMsg = Session::get('success') ?: (Session::has('success') ? Session::get('form_message') : null);
    $errorMsg = Session::get('error') ?: (Session::has('error') ? Session::get('form_message') : null);
    $warningMsg = Session::get('warning') ?: (Session::has('warning') ? Session::get('form_message') : null);
@endphp

@if(!empty($successMsg))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-none border py-2 px-3" role="alert" style="border-radius: 2px; border-color: #86efac; background-color: #f0fdf4; color: #166534; font-size: 0.8125rem;">
        <i class="fa-solid fa-circle-check text-success fs-6"></i>
        <div class="fw-medium flex-grow-1">{!! $successMsg !!}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Aizvērt" style="padding: 0.75rem;"></button>
    </div>
@endif

@if(!empty($errorMsg))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-none border py-2 px-3" role="alert" style="border-radius: 2px; border-color: #fca5a5; background-color: #fef2f2; color: #991b1b; font-size: 0.8125rem;">
        <i class="fa-solid fa-triangle-exclamation text-danger fs-6"></i>
        <div class="fw-medium flex-grow-1">{!! $errorMsg !!}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Aizvērt" style="padding: 0.75rem;"></button>
    </div>
@endif

@if(!empty($warningMsg))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-none border py-2 px-3" role="alert" style="border-radius: 2px; border-color: #fde047; background-color: #fefce8; color: #854d0e; font-size: 0.8125rem;">
        <i class="fa-solid fa-circle-exclamation text-warning fs-6"></i>
        <div class="fw-medium flex-grow-1">{!! $warningMsg !!}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Aizvērt" style="padding: 0.75rem;"></button>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3 shadow-none border py-2 px-3" role="alert" style="border-radius: 2px; border-color: #fca5a5; background-color: #fef2f2; color: #991b1b; font-size: 0.8125rem;">
        <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
            <i class="fa-solid fa-circle-xmark text-danger"></i>
            <span>Lūdzu novērsiet šādas kļūdas:</span>
        </div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Aizvērt" style="padding: 0.75rem;"></button>
    </div>
@endif
