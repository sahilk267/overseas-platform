@extends('layouts.app')

@section('title', 'Campaign Details')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campaigns</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $campaign->name }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold">{{ $campaign->name }}</h2>
            <div class="d-flex align-items-center gap-3">
                <span
                    class="badge bg-{{ $campaign->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $campaign->status === 'active' ? 'success' : 'warning' }} border border-{{ $campaign->status === 'active' ? 'success' : 'warning' }}-subtle px-3 py-2">
                    {{ strtoupper(str_replace('_', ' ', $campaign->status)) }}
                </span>
                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2">
                    {{ $campaign->category->name ?? 'Uncategorized' }}
                </span>
                <span class="text-muted small"><i class="bi bi-clock me-1"></i> Created
                    {{ $campaign->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <div class="col-md-4 text-md-end pt-4">
            @php
                $profile = request()->get('current_profile');
                $canApprove = $profile && in_array($profile->profile_type, ['admin', 'global_admin', 'developer']);
            @endphp

            @if($canApprove && $campaign->status === 'pending_approval')
                <form action="{{ route('admin.campaigns.approve', $campaign->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success rounded-pill px-4 me-2"><i
                            class="bi bi-check-lg me-2"></i>Approve Campaign</button>
                </form>
                <button class="btn btn-danger rounded-pill px-4 me-2" data-bs-toggle="modal" data-bs-target="#rejectModal"><i
                        class="bi bi-x-lg me-2"></i>Reject</button>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.campaigns.reject', $campaign->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Reject Campaign</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <p>Please provide a reason for rejecting this campaign:</p>
                                    <textarea name="reason" class="form-control" rows="3" required
                                        placeholder="Budget issues, incomplete details, etc."></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if($agencyProfile)
                <a href="{{ route('messages.show', $agencyProfile->id) }}" class="btn btn-primary rounded-pill px-4 me-2">
                    <i class="bi bi-chat-fill me-2"></i> Chat with {{ $agencyProfile->display_name }}
                </a>
            @endif
            <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-outline-primary rounded-pill me-2"><i class="bi bi-pencil me-2"></i>Edit</a>
            
            <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this campaign?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger rounded-pill"><i class="bi bi-trash me-2"></i>Delete</button>
            </form>
        </div>
    </div>

    <!-- Progress Tracking (Phase 11) -->
    @if($campaign->status === 'active')
        <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Execution Progress</h6>
                    <span class="badge bg-{{ $agencyProfile ? 'success' : 'warning' }} rounded-pill px-3">{{ $campaign->progress_percentage }}% Complete</span>
                </div>
                <div class="progress mb-2" style="height: 12px; border-radius: 6px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $agencyProfile ? 'success' : 'warning' }}" role="progressbar"
                        style="width: {{ $campaign->progress_percentage }}%"></div>
                </div>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle me-1"></i> 
                    @if($agencyProfile)
                        {{ $campaign->last_status_update ?? 'Agency is working on your service.' }}
                    @else
                        <strong>Acceptance Pending:</strong> Nearest agencies have been notified. Once an agency accepts your lead, you can start chatting.
                    @endif
                </p>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-4">Campaign Financials</h6>
                    <div class="mb-4">
                        <div class="small text-muted mb-1 text-uppercase fw-bold">Total Budget</div>
                        <div class="h3 fw-bold">₹{{ number_format($campaign->budget) }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="small text-muted mb-1 text-uppercase fw-bold">Start Date</div>
                        <div class="h5 fw-bold">
                            {{ $campaign->start_date ? $campaign->start_date->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="small text-muted mb-1 text-uppercase fw-bold">Currency</div>
                        <div class="h5 fw-bold">{{ $campaign->currency }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Executions & Performance</h6>
                </div>
                <div class="card-body py-5 text-center">
                    <div class="mb-3">
                        <i class="bi bi-graph-up display-4 text-light"></i>
                    </div>
                    <h5 class="text-muted">No executions found for this campaign.</h5>
                    <p class="small text-light-emphasis">Once bookings are made, you'll see real-time performance metrics
                        here.</p>
                    <button class="btn btn-sm btn-primary rounded-pill">Book Ad Spot</button>
                </div>
            </div>
        </div>
    </div>
@endsection