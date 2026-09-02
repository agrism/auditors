@extends('client.layout.master')

@section('navigation')
@stop

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo-badge">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <h2>Welcome Back</h2>
            <p>Sign in to your accounting workspace</p>
        </div>

        @if(session('error') || (isset($errors) && $errors->any()))
            <div class="alert alert-danger d-flex align-items-center mb-4 py-2 px-3 rounded-3" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <div class="small">
                    @if(session('error'))
                        {{ session('error') }}
                    @elseif(isset($errors) && $errors->any())
                        {{ $errors->first() }}
                    @endif
                </div>
            </div>
        @endif

        <form method="POST" action="/sign-in" class="mt-2">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label small fw-semibold text-slate-700">Email Address</label>
                <div class="input-icon-group">
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control form-control-modern w-100 @if(isset($errors) && $errors->has('email')) is-invalid @endif"
                           placeholder="name@company.com"
                           required
                           autofocus>
                    <i class="fa-regular fa-envelope"></i>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label small fw-semibold text-slate-700 mb-0">Password</label>
                </div>
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

            <button type="submit" class="btn btn-modern btn-modern-primary w-100 py-2 fs-6">
                <span>Sign In to Dashboard</span>
                <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="text-center mt-4 pt-2 border-top">
            <span class="small text-muted">&copy; {{ date('Y') }} Auditors.lv &bull; Financial & Accounting System</span>
        </div>
    </div>
</div>
@stop
