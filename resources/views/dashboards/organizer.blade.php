@extends('layouts.app')

@section('title', 'Organizer Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Event Organizer Dashboard</h2>
        <p class="text-muted">Manage your events, coordinate with talent, and track your bookings.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <a href="{{ route('inventory.index') }}" class="text-decoration-none">
            <div class="card h-100 border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-primary small fw-bold text-uppercase">Total Inventory</div>
                            <div class="h3 fw-bold mb-0">{{ $stats['total_inventory'] ?? 0 }} Assets</div>
                        </div>
                        <i class="bi bi-building fs-1 text-light"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('events.index') }}" class="text-decoration-none">
            <div class="card bg-warning text-dark border-0">
                <div class="card-body">
                    <div class="small fw-bold text-uppercase opacity-75">Active Events</div>
                    <div class="h2 fw-bold mb-0">{{ $stats['active_events'] ?? 0 }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white border-0">
            <div class="card-body">
                <div class="small fw-bold text-uppercase opacity-75">Pending Talent</div>
                <div class="h2 fw-bold mb-0">{{ $stats['pending_talent'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white border-0">
            <div class="card-body">
                <div class="small fw-bold text-uppercase opacity-75">Total Budget</div>
                <div class="h2 fw-bold mb-0">₹{{ number_format(($stats['total_budget'] ?? 0) / 1000, 1) }}k</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark text-white border-0">
            <div class="card-body">
                <div class="small fw-bold text-uppercase opacity-75">Upcoming Deadlines</div>
                <div class="h2 fw-bold mb-0">{{ $stats['upcoming_deadlines'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Live Events Feed</h6>
                <a href="{{ route('events.create') }}" class="btn btn-sm btn-warning text-dark rounded-pill fw-bold">Host New Event</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Event Name</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities['live_events'] ?? [] as $event)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 text-center" style="min-width: 50px;">
                                                <div class="fw-bold text-primary mb-0">{{ $event->start_datetime?->format('d') ?? '??' }}</div>
                                                <div class="small text-muted" style="font-size: 0.7rem;">{{ $event->start_datetime?->format('M') ?? '???' }}</div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $event->name }}</div>
                                                <div class="text-muted small">{{ $event->location?->name ?? 'Venue TBD' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $event->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $event->status === 'active' ? 'success' : 'secondary' }} border border-{{ $event->status === 'active' ? 'success' : 'secondary' }}-subtle px-3 py-2">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td>₹{{ number_format(($event->budget ?? 0) / 1000, 1) }}k</td>
                                    <td>{{ $event->start_datetime?->format('M d, Y') ?? 'TBD' }}</td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary rounded-3">Manage</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No live events found. <a href="#">Create your first event</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 fw-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <button class="btn btn-primary py-3 rounded-3 shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Create New Event
                    </button>
                    <button class="btn btn-outline-dark py-3 rounded-3">
                        <i class="bi bi-people me-2"></i> Browse Talent
                    </button>
                    <button class="btn btn-outline-dark py-3 rounded-3">
                        <i class="bi bi-calendar3 me-2"></i> My Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
