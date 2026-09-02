@extends('layouts.auth')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo-badge">
                <i class="fa-solid fa-lock-open"></i>
            </div>
            <h2>Jauna parole</h2>
            <p>Ievadiet jauno paroli savam kontam</p>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger d-flex align-items-center mb-4 py-2 px-3 rounded-3" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <div class="small">{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ url('/password/reset') }}" class="mt-2">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
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

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-slate-700">Jaunā parole</label>
                <div class="input-icon-group">
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control form-control-modern w-100 @if(isset($errors) && $errors->has('password')) is-invalid @endif"
                           placeholder="••••••••"
                           required>
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label small fw-semibold text-slate-700">Apstiprināt paroli</label>
                <div class="input-icon-group">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="form-control form-control-modern w-100 @if(isset($errors) && $errors->has('password_confirmation')) is-invalid @endif"
                           placeholder="••••••••"
                           required>
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-modern btn-modern-primary w-100 py-2 fs-6 mb-3">
                <span>Atjaunot paroli</span>
                <i class="fa-solid fa-arrow-right ms-1"></i>
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
