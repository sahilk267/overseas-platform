@extends('layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Vendor Control Center</h2>
            <p class="text-muted">Monitor your assets and manage booking requests.</p>

            @php
                $currentProfile = request()->get('current_profile');
                $hasCategories = $currentProfile && $currentProfile->categories->count() > 0;
            @endphp

            @if(!$hasCategories)
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center rounded-4 p-4 mt-3">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-person-badge-fill fs-3 text-info"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Set Up Your Services</h6>
                        <p class="text-muted small mb-0">You haven't selected any service categories yet. <a
                                href="{{ route('profiles.edit', $currentProfile->id) }}" class="fw-bold text-info">Complete your
                                profile</a> to receive more relevant campaign leads.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Vendor Stats -->
        <div class="col-md-4">
            <div class="card h-100 border-start border-primary border-4">
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
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-success small fw-bold text-uppercase">Total Earnings</div>
                            <div class="h3 fw-bold mb-0">₹{{ number_format(($stats['total_earnings'] ?? 0) / 1000, 1) }}k
                            </div>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 text-light"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-info small fw-bold text-uppercase">Active Bookings</div>
                            <div class="h3 fw-bold mb-0">{{ $stats['active_bookings'] ?? 0 }}</div>
                        </div>
                        <i class="bi bi-calendar-check fs-1 text-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 fw-bold text-primary">New Campaign Leads</h6>
                </div>
                <div class="card-body">
                    @if($activities['pending_requests']->count() > 0)
                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center rounded-3">
                            <i class="bi bi-megaphone-fill fs-4 me-3"></i>
                            <div>
                                You have <strong>{{ $activities['pending_requests']->count() }} new campaigns</strong> nearby
                                searching for vendors.
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Campaign</th>
                                    <th>Category</th>
                                    <th>Budget</th>
                                    <th>Advertiser</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities['pending_requests'] ?? [] as $notification)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $notification->campaign?->name ?? 'N/A' }}</span>
                                            <div class="text-muted small">#CAM-{{ $notification->campaign_id }}</div>
                                        </td>
                                        <td>{{ $notification->campaign?->category?->name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($notification->campaign?->budget ?? 0) }}</td>
                                        <td>{{ $notification->campaign?->advertiserProfile?->display_name ?? 'N/A' }}</td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('agency.leads.accept', $notification->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-success rounded-pill px-3">Accept</button>
                                            </form>
                                            <form action="{{ route('agency.leads.pass', $notification->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3">Pass</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox display-4 d-block mb-3 opacity-25"></i>
                                            No new campaign leads available at the moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection