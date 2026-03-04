@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">System Administration</h2>
            <p class="text-muted">Global overview of UMAEP platform performance and pending actions.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Admin Statistics -->
        <div class="col-md-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="small fw-bold text-uppercase text-white-50">Total Users</div>
                    <div class="h2 fw-bold mb-0">{{ number_format($stats['total_users'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="small fw-bold text-uppercase text-white-50">Registered Clients</div>
                    <div class="h2 fw-bold mb-0">{{ number_format($stats['total_clients'] ?? 0) }}</div>
                    <div class="small mt-2">Companies seeking services</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="small fw-bold text-uppercase text-white-50">Registered Vendors</div>
                    <div class="h2 fw-bold mb-0">{{ number_format($stats['total_vendors'] ?? 0) }}</div>
                    <div class="small mt-2">Service providers</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm border-0">
                <div class="card-body">
                    <div class="small fw-bold text-uppercase text-black-50">Pending Campaigns</div>
                    <div class="h2 fw-bold mb-0">{{ number_format($stats['pending_campaigns'] ?? 0) }}</div>
                    <a href="{{ route('admin.campaigns.index') }}"
                        class="text-dark text-decoration-none small mt-2 d-block">Manage Approvals <i
                            class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="small fw-bold text-uppercase text-white-50">System Maintenance</div>
                    <div class="h2 fw-bold mb-0">Cleanup</div>
                    <a href="{{ route('admin.cleanup.index') }}"
                        class="text-white text-decoration-none small mt-2 d-block">Purge System Data <i
                            class="bi bi-trash3 ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Recent Users</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($activities['recent_users'] ?? [] as $recentUser)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $recentUser->email }}</h6>
                                    <small class="text-muted">Joined {{ $recentUser->created_at->diffForHumans() }}</small>
                                </div>
                                <span
                                    class="badge {{ $recentUser->status === 'active' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($recentUser->status) }}</span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">No recent users found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Recent Disputes</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($activities['recent_disputes'] ?? [] as $dispute)
                            <a href="{{ route('disputes.show', $dispute->id) }}"
                                class="list-group-item list-group-item-action py-3 px-4">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold">{{ $dispute->dispute_type }}</h6>
                                    <small class="text-danger">{{ ucfirst($dispute->status) }}</small>
                                </div>
                                <p class="mb-1 small text-truncate">{{ $dispute->description }}</p>
                                <small class="text-muted">{{ $dispute->created_at->diffForHumans() }}</small>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted">No recent disputes found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection