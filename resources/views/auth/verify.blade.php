@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Verify Your Email</h2>
                        <p class="text-muted">We've sent a 6-digit code to your email.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('verification.verify') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="code" class="form-label text-center d-block">Enter 6-Digit Code</label>
                            <input type="text" name="code" id="code"
                                class="form-control form-control-lg rounded-3 text-center fw-bold"
                                style="letter-spacing: 0.5rem; font-size: 2rem;" placeholder="000000" maxlength="6" required
                                autofocus>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3">Verify Code</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Didn't receive the code?</p>
                        <form action="{{ route('verification.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none p-0">Resend Code</button>
                        </form>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-muted small text-decoration-none">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection