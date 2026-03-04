@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Forgot Password</h2>
                        <p class="text-muted">Enter your email and we'll send you a link to reset your password.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg rounded-3"
                                placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3">Send Reset Link</button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg rounded-3">Back to
                                Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection