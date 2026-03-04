@extends('layouts.app')

@section('title', 'System Cleanup')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 mt-5">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger display-1"></i>
                    </div>
                    <h2 class="fw-bold text-danger mb-3">Permanent Data Deletion</h2>
                    <p class="text-muted mb-4 fs-5">
                        This action will permanently delete all <strong>Campaigns, Bookings, Payments, Events, Disputes, and
                            User Profiles</strong> from the system.
                    </p>

                    <div class="alert alert-warning border-0 bg-warning-subtle text-dark p-4 rounded-3 text-start mb-4">
                        <h6 class="fw-bold"><i class="bi bi-info-circle-fill me-2"></i> Important Information:</h6>
                        <ul class="mb-0 small">
                            <li>Only <strong>Global Admin</strong> and <strong>Developer</strong> accounts will be
                                preserved.</li>
                            <li>All regular Advertiser and Vendor profiles will be deleted.</li>
                            <li>This action <strong>cannot be undone</strong>.</li>
                            <li>Use this only to clear dummy/test data before a fresh launch or testing phase.</li>
                        </ul>
                    </div>

                    <form action="{{ route('admin.cleanup.perform') }}" method="POST">
                        @csrf
                        <div class="form-check mb-4 d-inline-block text-start">
                            <input class="form-check-input" type="checkbox" name="confirm_cleanup" id="confirm_cleanup"
                                required>
                            <label class="form-check-label fw-bold" for="confirm_cleanup">
                                I understand that this action is permanent and irreversible.
                            </label>
                        </div>

                        <div class="d-grid gap-3 d-sm-flex justify-content-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-5 rounded-pill">Cancel</a>
                            <button type="submit" class="btn btn-danger btn-lg px-5 rounded-pill shadow-sm">
                                <i class="bi bi-trash3 me-2"></i> Execute Purge
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection