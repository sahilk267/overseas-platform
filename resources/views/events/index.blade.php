@extends('layouts.app')

@section('title', 'Manage Events')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="fw-bold">Events Hub</h2>
        <p class="text-muted">Plan, organize, and manage your upcoming events and talent bookings.</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="{{ route('events.create') }}" class="btn btn-warning text-dark btn-lg rounded-pill shadow-sm px-4">
            <i class="bi bi-calendar-plus me-2"></i>Host New Event
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Event Details</th>
                        <th>Status</th>
                        <th>Budget</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold">{{ $event->name }}</span>
                                <div class="text-muted small">{{ $event->location->name ?? 'Venue TBD' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $event->status === 'confirmed' ? 'success' : 'info' }} px-3 py-2">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>₹{{ number_format($event->budget) }}</td>
                            <td>{{ $event->start_datetime ? $event->start_datetime->format('M d, Y') : 'N/A' }}</td>
                            <td class="text-end pe-4">
                                <button class="btn btn-outline-primary btn-sm rounded-pill">Manage Event</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x display-4 text-light"></i>
                                <h5 class="mt-3">No events found.</h5>
                                <p class="small">Ready to organize your next big thing?</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
