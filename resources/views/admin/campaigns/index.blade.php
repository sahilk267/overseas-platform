@extends('layouts.app')

@section('title', 'Campaign Management')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Campaign Management</h2>
            <p class="text-muted">Review, approve, or reject advertiser campaigns.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Campaign</th>
                            <th>Advertiser</th>
                            <th>Budget</th>
                            <th>Status</th>
                            <th>Dates</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold">{{ $campaign->name }}</div>
                                                <div class="text-muted small">ID: #CAM-{{ $campaign->id }}</div>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold">{{ $campaign->advertiserProfile?->display_name ?? 'N/A' }}
                                                </div>
                                                <div class="text-muted smaller">{{ $campaign->advertiserProfile?->user?->email ?? '' }}
                                                </div>
                                            </td>
                                            <td>₹{{ number_format($campaign->budget) }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                        $campaign->status === 'approved' ? 'success' :
                            ($campaign->status === 'pending_approval' ? 'warning' : 'secondary') 
                                                    }}-subtle text-{{ 
                                                        $campaign->status === 'approved' ? 'success' :
                            ($campaign->status === 'pending_approval' ? 'warning' : 'secondary') 
                                                    }} border border-{{ 
                                                        $campaign->status === 'approved' ? 'success' :
                            ($campaign->status === 'pending_approval' ? 'warning' : 'secondary') 
                                                    }}-subtle px-3 py-2">
                                                    {{ ucfirst(str_replace('_', ' ', $campaign->status)) }}
                                                </span>
                                            </td>
                                            <td class="small">
                                                {{ $campaign->start_date ? $campaign->start_date->format('M d') : '?' }} -
                                                {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : '?' }}
                                            </td>
                                            <td class="text-end pe-4">
                                                @if($campaign->status === 'pending_approval')
                                                    <form action="{{ route('admin.campaigns.approve', $campaign->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-success rounded-pill px-3 me-1">Approve</button>
                                                    </form>
                                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $campaign->id }}">Reject</button>

                                                    <!-- Reject Modal -->
                                                    <div class="modal fade" id="rejectModal{{ $campaign->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="{{ route('admin.campaigns.reject', $campaign->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Reject Campaign</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body text-start">
                                                                        <p>Reason for rejection:</p>
                                                                        <textarea name="reason" class="form-control" rows="3" required
                                                                            placeholder="e.g. Budget too low, Invalid metadata..."></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-modal="dismiss">Cancel</button>
                                                                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @php
                                                        $isAllocated = $campaign->leadNotifications->where('status', 'accepted')->count() > 0;
                                                    @endphp

                                                    @if(!$isAllocated)
                                                        <button class="btn btn-sm btn-primary rounded-pill px-3 me-1" data-bs-toggle="modal"
                                                            data-bs-target="#allocateModal{{ $campaign->id }}"><i
                                                                class="bi bi-person-plus me-1"></i>Allocate</button>

                                                        <!-- Allocate Modal -->
                                                        <div class="modal fade" id="allocateModal{{ $campaign->id }}" tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content text-start">
                                                                    <form action="{{ route('admin.campaigns.allocate', $campaign->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Allocate Agency Manually</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p class="text-muted small mb-4">Select an agency to handle
                                                                                <strong>{{ $campaign->name }}</strong>. This will skip the
                                                                                bidding/routing process.</p>

                                                                            <label class="form-label fw-bold small text-uppercase">Select
                                                                                Agency</label>
                                                                            <select name="vendor_profile_id" class="form-select" required>
                                                                                <option value="">Choose an active agency...</option>
                                                                                @foreach($vendors as $vendor)
                                                                                    <option value="{{ $vendor->id }}">{{ $vendor->display_name }}
                                                                                        ({{ $vendor->city ?? 'N/A' }})</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn btn-primary">Assign Agency</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <a href="{{ route('campaigns.show', $campaign->id) }}"
                                                        class="btn btn-sm btn-light rounded-pill px-3">View</a>
                                                @endif
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <h6 class="text-muted">No campaigns found for review.</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($campaigns->hasPages())
            <div class="card-footer bg-white">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>
@endsection