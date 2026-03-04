@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="bg-primary p-4 text-white">
                        <h4 class="mb-1 fw-bold">Complete Your {{ ucfirst($profile->profile_type) }} Profile</h4>
                        <p class="mb-0 opacity-75">Provide details about your services to start receiving relevant leads.
                        </p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('profiles.update', $profile->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Basic Info -->
                                <div class="col-12">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Display Name</label>
                                    <input type="text" name="display_name"
                                        value="{{ old('display_name', $profile->display_name) }}"
                                        class="form-control form-control-lg rounded-3 shadow-sm" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Bio /
                                        Description</label>
                                    <textarea name="bio" rows="4" class="form-control rounded-3 shadow-sm"
                                        placeholder="Tell clients about your expertise...">{{ old('bio', $profile->bio) }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Service City</label>
                                    <input type="text" name="city" value="{{ old('city', $profile->city) }}"
                                        class="form-control rounded-3 shadow-sm" placeholder="e.g. Mumbai, Berlin...">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Base Location</label>
                                    <select name="location_id" class="form-select rounded-3 shadow-sm">
                                        <option value="">Select a location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location_id', $profile->location_id) == $location->id ? 'selected' : '' }}>
                                                {{ $location->city }}, {{ $location->country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category Selection for Vendors -->
                                @if($profile->profile_type === 'vendor')
                                    <div class="col-12 mt-4">
                                        <div class="p-4 bg-light rounded-4 border border-info-subtle">
                                            <h6 class="fw-bold mb-3"><i class="bi bi-tag-fill text-info me-2"></i>Service
                                                Categories</h6>
                                            <p class="text-muted small mb-4">Select the services you provide. We'll use these to
                                                route relevant campaigns to your dashboard.</p>

                                            <div class="row g-3">
                                                @foreach($categories as $category)
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-check custom-option border rounded-3 p-3 bg-white h-100 shadow-sm">
                                                            <input class="form-check-input mt-1" type="checkbox" name="categories[]"
                                                                value="{{ $category->id }}" id="cat{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                                            <label class="form-check-label d-block ms-2"
                                                                for="cat{{ $category->id }}">
                                                                <span
                                                                    class="d-block fw-bold text-primary">{{ $category->name }}</span>
                                                                @if($category->children->count() > 0)
                                                                    <small
                                                                        class="text-muted">{{ $category->children->pluck('name')->implode(', ') }}</small>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                                        <i class="bi bi-check2-circle me-2"></i> Save Profile Details
                                    </button>
                                    <a href="{{ route('dashboard') }}"
                                        class="btn btn-link w-100 mt-2 text-decoration-none text-muted">Skip for now</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-option:hover {
            border-color: #0d6efd !important;
            background-color: #f8f9ff !important;
            cursor: pointer;
        }

        .form-check-input:checked+.form-check-label .text-primary {
            color: #0d6efd !important;
        }
    </style>

    <script>
        document.querySelectorAll('.custom-option').forEach(option => {
            option.addEventListener('click', function (e) {
                if (e.target.tagName !== 'INPUT') {
                    const input = this.querySelector('input');
                    input.checked = !input.checked;
                }
            });
        });
    </script>
@endsection