@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Join UMAEP</h2>
                        <p class="text-muted">Choose your role on the platform</p>
                    </div>

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

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="user_type" id="client" value="advertiser"
                                    checked autocomplete="off">
                                <label class="btn btn-outline-primary w-100 py-3 rounded-3" for="client">
                                    <i class="bi bi-person-badge display-6 d-block mb-2"></i>
                                    <strong>I am a Client</strong>
                                    <small class="d-block text-muted">Seeking services</small>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="user_type" id="agency" value="vendor"
                                    autocomplete="off">
                                <label class="btn btn-outline-success w-100 py-3 rounded-3" for="agency">
                                    <i class="bi bi-building display-6 d-block mb-2"></i>
                                    <strong>I am an Agency</strong>
                                    <small class="d-block text-muted">Providing services</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name / Business Name</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg rounded-3"
                                placeholder="Enter your name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg rounded-3"
                                placeholder="name@example.com" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control form-control-lg rounded-3" placeholder="••••••••" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark btn-lg rounded-3">Create Account</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Already have an account? <a href="{{ route('login') }}"
                                class="text-decoration-none">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection