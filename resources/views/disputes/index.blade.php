@extends('layouts.app')

@section('title', 'Manage Disputes')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Resolution Center</h2>
        <p class="text-muted">Track and resolve platform disputes to ensure a fair marketplace.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Dispute Type</th>
                        <th>Participants</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disputes as $dispute)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-uppercase">{{ $dispute->dispute_type }}</span>
                                <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $dispute->description }}</div>
                            </td>
                            <td>
                                <div class="small fw-bold">{{ $dispute->complainantProfile->display_name ?? 'User' }}</div>
                                <div class="text-muted small">vs. {{ $dispute->respondentProfile->display_name ?? 'Provider' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $dispute->status === 'open' ? 'danger' : ($dispute->status === 'resolved' ? 'success' : 'warning') }} px-3 py-2">
                                    {{ ucfirst($dispute->status) }}
                                </span>
                            </td>
                            <td>{{ $dispute->created_at->format('M d, Y') }}</td>
                            <td class="text-end pe-4">
                                <a href="#" class="btn btn-sm btn-outline-dark rounded-pill">View Case</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No disputes currently active.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
