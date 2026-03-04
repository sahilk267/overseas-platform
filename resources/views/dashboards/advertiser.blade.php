@extends('layouts.app')

@section('title', 'Advertiser Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Welcome back, Advertiser!</h2>
        <p class="text-muted">Here's what's happening with your campaigns today.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Stats Cards -->
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase">Active Campaigns</div>
                        <div class="display-6 fw-bold">{{ $stats['active_campaigns'] ?? 0 }}</div>
                    </div>
                    <i class="bi bi-megaphone fs-1 text-white-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-3">
                <a href="{{ route('campaigns.index') }}" class="text-white text-decoration-none small">View all <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase">Total Spend (MTD)</div>
                        <div class="display-6 fw-bold">₹{{ number_format(($stats['total_spend_mtd'] ?? 0) / 1000, 1) }}k</div>
                    </div>
                    <i class="bi bi-currency-rupee fs-1 text-white-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-3">
                <a href="#" class="text-white text-decoration-none small">View report <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase">Pending Approvals</div>
                        <div class="display-6 fw-bold">{{ $stats['pending_approvals'] ?? 0 }}</div>
                    </div>
                    <i class="bi bi-clock-history fs-1 text-white-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-3">
                <a href="#" class="text-white text-decoration-none small">Review now <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase">Running Executions</div>
                        <div class="display-6 fw-bold">{{ $stats['running_executions'] ?? 0 }}</div>
                    </div>
                    <i class="bi bi-play-circle fs-1 text-white-50"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 py-3">
                <a href="#" class="text-white text-decoration-none small">Track live <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">Recent Campaigns</h6>
                <a href="{{ route('campaigns.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">Create New</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Campaign Name</th>
                                <th>Status</th>
                                <th>Budget</th>
                                <th>Progress</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities['recent_campaigns'] ?? [] as $campaign)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $campaign->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $campaign->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $campaign->status === 'active' ? 'success' : 'warning' }} border border-{{ $campaign->status === 'active' ? 'success' : 'warning' }}-subtle">
                                            {{ ucfirst($campaign->status) }}
                                        </span>
                                    </td>
                                    <td>₹{{ number_format($campaign->budget) }}</td>
                                    <td style="width: 200px;">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">Manage</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No campaigns found. <a href="#">Create your first campaign</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">Budget Distribution</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <i class="bi bi-pie-chart display-1 text-light"></i>
                    <p class="text-muted mt-2 small">Chart will be integrated here</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
