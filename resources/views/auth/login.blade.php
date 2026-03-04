@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Welcome Back</h2>
                        <p class="text-muted">Please login to your account</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg rounded-3"
                                placeholder="name@example.com" required autofocus>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="password" class="form-label mb-0">Password</label>
                                <a href="{{ route('password.request') }}"
                                    class="text-primary small text-decoration-none">Forgot Password?</a>
                            </div>
                            <input type="password" name="password" id="password"
                                class="form-control form-control-lg rounded-3" placeholder="••••••••" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3">Sign In</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Don't have an account? <a href="{{ route('register') }}"
                                class="text-primary fw-bold text-decoration-none">Register Now</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection