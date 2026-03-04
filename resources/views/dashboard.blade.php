@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-header mb-4">
    <h1 class="h2">Welcome to UMAEP</h1>
    <p class="text-muted">You are logged in as {{ $user->email }}.</p>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="h5 mb-3">Profile Status</h3>
                @if($profile)
                    <div class="alert alert-info">
                        <strong>Active Profile:</strong> {{ $profile->display_name }} ({{ ucfirst($profile->profile_type) }})
                    </div>
                    <p>Use the Profile Switcher in the sidebar to change roles.</p>
                @else
                    <div class="alert alert-warning">
                        Please select an active profile to access role-specific features.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
