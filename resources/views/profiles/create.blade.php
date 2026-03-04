@extends('layouts.app')

@section('title', 'Create Your Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 bg-primary d-none d-md-flex align-items-center justify-content-center p-5 text-white text-center">
                        <div>
                            <i class="bi bi-person-badge display-1 mb-4"></i>
                            <h3 class="fw-bold">Welcome to UMAEP</h3>
                            <p class="opacity-75">Start your journey by choosing the role that fits you best. You can always add more roles later!</p>
                        </div>
                    </div>
                    <div class="col-md-7 p-5">
                        <div class="mb-4">
                            <h2 class="fw-bold">Who are you?</h2>
                            <p class="text-muted">Select your primary role to get started.</p>
                        </div>

                        <form action="{{ route('profiles.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Display Name</label>
                                <input type="text" name="display_name" class="form-control form-control-lg rounded-3" placeholder="e.g. John Doe / Ace Advertising" required>
                                <div class="form-text">This is how you will be seen by others.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Select Your Role</label>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="profile_type" id="role_advertiser" value="advertiser" checked>
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-3" for="role_advertiser">
                                            <i class="bi bi-megaphone fs-3 d-block mb-2"></i>
                                            Advertiser
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="profile_type" id="role_vendor" value="vendor">
                                        <label class="btn btn-outline-success w-100 py-3 rounded-3" for="role_vendor">
                                            <i class="bi bi-shop fs-3 d-block mb-2"></i>
                                            Vendor
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="profile_type" id="role_talent" value="talent">
                                        <label class="btn btn-outline-info w-100 py-3 rounded-3" for="role_talent">
                                            <i class="bi bi-star fs-3 d-block mb-2"></i>
                                            Talent
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="profile_type" id="role_organizer" value="event_organizer">
                                        <label class="btn btn-outline-warning w-100 py-3 rounded-3" for="role_organizer">
                                            <i class="bi bi-calendar-event fs-3 d-block mb-2"></i>
                                            Organizer
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm py-3 fw-bold mt-2">
                                Complete my Profile <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
