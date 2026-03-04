<div class="dropdown me-3" x-data="{ currentProfile: '{{ session('current_profile_type', 'none') }}' }">
    @php
        $profiles = Auth::user()->profiles()->where('status', 'active')->get();
        $currentProfileId = session('current_profile_id');
        $activeProfile = $profiles->firstWhere('id', $currentProfileId);
    @endphp

    <button class="btn btn-outline-dark dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
        @if($activeProfile)
            <span class="profile-badge bg-primary text-white">
                {{ $activeProfile->profile_type }}
            </span>
            <span class="d-none d-md-inline small fw-semibold text-truncate" style="max-width: 100px;">
                {{ $activeProfile->display_name ?? 'Active Profile' }}
            </span>
        @else
            <span class="small text-danger">Select Profile</span>
        @endif
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 250px;">
        <li class="px-3 py-2 border-bottom">
            <span class="smaller text-muted fw-bold text-uppercase">Switch Profile</span>
        </li>
        @forelse($profiles as $profile)
            <li>
                <form action="{{ route('profiles.switch') }}" method="POST">
                    @csrf
                    <input type="hidden" name="profile_id" value="{{ $profile->id }}">
                    <button type="submit" class="dropdown-item d-flex justify-content-between align-items-center py-2 {{ $profile->id == $currentProfileId ? 'bg-light' : '' }}">
                        <div>
                            <div class="fw-bold small">{{ $profile->display_name ?? ucwords($profile->profile_type) }}</div>
                            <div class="smaller text-muted" style="font-size: 0.75rem;">{{ ucwords($profile->profile_type) }}</div>
                        </div>
                        @if($profile->id == $currentProfileId)
                            <i class="bi bi-check-circle-fill text-success"></i>
                        @endif
                    </button>
                </form>
            </li>
        @empty
            <li class="px-3 py-2 text-center">
                <span class="small text-muted">No active profiles found</span>
            </li>
        @endforelse
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item small text-center text-primary" href="{{ route('profiles.create') }}"><i class="bi bi-plus-circle me-1"></i> Add New Profile</a></li>
    </ul>
</div>
