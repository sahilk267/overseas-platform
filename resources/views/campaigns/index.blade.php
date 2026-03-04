@extends('layouts.app')

@section('title', 'My Campaigns')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">My Ad Campaigns</h2>
            <p class="text-muted">Manage and track your active advertising campaigns across the UMAEP network.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('campaigns.create') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4">
                <i class="bi bi-plus-lg me-2"></i>Create New Campaign
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Campaign Name</th>
                            <th>Status</th>
                            <th>Budget</th>
                            <th>Start Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold">{{ $campaign->name }}</span>
                                    <div class="text-muted small">ID: #CAM-{{ $campaign->id }}</div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $campaign->status === 'active' ? 'success' : 'warning' }}-subtle text-{{ $campaign->status === 'active' ? 'success' : 'warning' }} border border-{{ $campaign->status === 'active' ? 'success' : 'warning' }}-subtle px-3 py-2">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($campaign->budget) }}</td>
                                <td>{{ $campaign->start_date ? $campaign->start_date->format('M d, Y') : 'N/A' }}</td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('campaigns.show', $campaign->id) }}"><i
                                                        class="bi bi-eye me-2"></i>View Details</a></li>
                                            <li><a class="dropdown-item" href="{{ route('campaigns.edit', $campaign->id) }}"><i
                                                        class="bi bi-pencil me-2"></i>Edit</a></li>
                                            <li>
                                                <form action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this campaign?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i
                                                            class="bi bi-trash me-2"></i>Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-megaphone display-4 text-light"></i>
                                    </div>
                                    <h5 class="text-muted">No campaigns found.</h5>
                                    <p class="text-light-emphasis small">Start growth by creating your first advertising
                                        campaign.</p>
                                    <a href="{{ route('campaigns.create') }}" class="btn btn-sm btn-primary">Get Started</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($campaigns->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>
@endsection