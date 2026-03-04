@extends('layouts.app')

@section('title', 'Talent Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Hello, Star Performer!</h2>
        <p class="text-muted">You have {{ $activities['upcoming_appointments']->count() ?? 0 }} upcoming appointments scheduled.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Talent Stats -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-primary-subtle d-inline-flex p-3 mb-3">
                    <i class="bi bi-star-fill fs-2 text-primary"></i>
                </div>
                <h5 class="fw-bold">{{ number_format($stats['rating'] ?? 0, 1) }} / 5.0</h5>
                <p class="text-muted small mb-0">Customer Rating</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-success-subtle d-inline-flex p-3 mb-3">
                    <i class="bi bi-wallet2 fs-2 text-success"></i>
                </div>
                <h5 class="fw-bold">₹{{ number_format($stats['pending_payouts'] ?? 0) }}</h5>
                <p class="text-muted small mb-0">Pending Payouts</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-info-subtle d-inline-flex p-3 mb-3">
                    <i class="bi bi-eye fs-2 text-info"></i>
                </div>
                <h5 class="fw-bold">{{ number_format($stats['appointments'] ?? 0) }}</h5>
                <p class="text-muted small mb-0">Profile Views</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-dark">Upcoming Appointments</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($activities['upcoming_appointments'] ?? [] as $appointment)
                        <li class="list-group-item p-0">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3 text-center" style="min-width: 60px;">
                                        <div class="display-6 fw-bold text-primary">{{ $appointment->scheduled_at?->format('d') ?? '??' }}</div>
                                        <div class="small text-uppercase">{{ $appointment->scheduled_at?->format('M') ?? '???' }}</div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $appointment->requesterProfile?->display_name ?? 'Client' }}</h6>
                                        <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $appointment->location?->name ?? 'TBD' }}</p>
                                        <div class="small text-primary mt-1 fw-bold">{{ $appointment->scheduled_at?->format('h:i A') ?? 'Time TBD' }}</div>
                                    </div>
                                    <div class="ms-auto text-end">
                                        <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : 'warning' }}-subtle text-{{ $appointment->status === 'confirmed' ? 'success' : 'warning' }} px-3 mb-2 d-block">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill">Check Details</button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item p-4 text-center text-muted">No upcoming appointments found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
