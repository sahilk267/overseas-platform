@extends('layouts.app')

@section('title', 'Agency Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="fw-bold">Agency Management</h2>
        <p class="text-muted">Manage service providers and assign them to ad categories.</p>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Agency Name</th>
                        <th>Location</th>
                        <th>Categories</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agencies as $agency)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $agency->display_name }}</div>
                            <small class="text-muted">{{ $agency->user->email ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $agency->city ?: 'Not Set' }}</td>
                        <td>
                            @forelse($agency->categories as $category)
                                <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $category->name }}</span>
                            @empty
                                <span class="text-muted small italic">None Assigned</span>
                            @endforelse
                        </td>
                        <td>
                            <span class="badge bg-{{ $agency->status === 'active' ? 'success' : 'warning' }} rounded-pill">
                                {{ ucfirst($agency->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                @if($agency->status !== 'active')
                                <form action="{{ route('admin.agencies.approve', $agency) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill">Approve</button>
                                </form>
                                @endif
                                <a href="{{ route('admin.agencies.edit', $agency) }}" class="btn btn-sm btn-outline-primary rounded-pill">Categories</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No agencies registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $agencies->links() }}
</div>
@endsection
