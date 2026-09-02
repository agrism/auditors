@extends('layouts.auth')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo-badge">
                <i class="fa-solid fa-key"></i>
            </div>
            <h2>Paroles atjaunošana</h2>
            <p>Ievadiet e-pastu, lai saņemtu paroles atiestatīšanas saiti</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center mb-4 py-2 px-3 rounded-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <div class="small">{{ session('status') }}</div>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger d-flex align-items-center mb-4 py-2 px-3 rounded-3" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <div class="small">{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ url('/password/email') }}" class="mt-2">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label small fw-semibold text-slate-700">E-pasta adrese</label>
                <div class="input-icon-group">
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control form-control-modern w-100 @if(isset($errors) && $errors->has('email')) is-invalid @endif"
                           placeholder="vards@uznemums.lv"
                           required
                           autofocus>
                    <i class="fa-regular fa-envelope"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-modern btn-modern-primary w-100 py-2 fs-6 mb-3">
                <span>Nosūtīt atiestatīšanas saiti</span>
                <i class="fa-solid fa-paper-plane ms-1"></i>
            </button>

            <div class="text-center">
                <a href="{{ url('/login') }}" class="small text-muted text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-1"></i> Atgriezties uz pieslēgšanos
                </a>
            </div>
        </form>

        <div class="text-center mt-4 pt-2 border-top">
            <span class="small text-muted">&copy; {{ date('Y') }} Auditors.lv &bull; Grāmatvedības sistēma</span>
        </div>
    </div>
</div>
@endsection
