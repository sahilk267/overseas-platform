@extends('layouts.app')

@section('title', 'Select Profile')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Select Your Profile</h2>
        <p class="text-muted">Choose which role you want to act as for this session.</p>
        <a href="{{ route('profiles.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm mt-3">
            <i class="bi bi-plus-lg me-2"></i> Create New Profile
        </a>
    </div>

    <div class="row justify-content-center g-4">
        @foreach($profiles as $profile)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 hover-lift">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <span class="badge bg-{{ $profile->profile_type == 'admin' ? 'danger' : ($profile->profile_type == 'advertiser' ? 'primary' : 'success') }} px-3 py-2">
                            {{ ucfirst($profile->profile_type) }}
                        </span>
                    </div>
                    <h4 class="card-title fw-bold">{{ $profile->display_name }}</h4>
                    <p class="text-muted small mb-4">{{ Str::limit($profile->bio, 100) }}</p>
                    
                    <form action="{{ route('profiles.switch') }}" method="POST">
                        @csrf
                        <input type="hidden" name="profile_id" value="{{ $profile->id }}">
                        <button type="submit" class="btn btn-outline-primary w-100 rounded-3">Select Role</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
}
</style>
@endsection
