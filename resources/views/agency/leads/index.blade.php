@extends('layouts.app')

@section('title', 'Leads Management')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Service Leads</h2>
            <p class="text-muted">Review and manage your incoming service requests (Campaigns).</p>
        </div>
    </div>

    <div class="row">
        @forelse($leads as $lead)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span
                                class="badge bg-{{ $lead->status === 'pending' ? 'warning' : ($lead->status === 'accepted' ? 'success' : 'secondary') }} rounded-pill px-3">
                                {{ ucfirst($lead->status) }}
                            </span>
                            <small class="text-muted">Notified: {{ $lead->notified_at->diffForHumans() }}</small>
                        </div>

                        <h5 class="fw-bold mb-1">{{ $lead->campaign->name }}</h5>
                        <p class="text-muted small mb-3">
                            Category: <strong>{{ $lead->campaign->category->name }}</strong>
                        </p>

                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-person-circle me-2 text-primary"></i>
                                <span>Client: {{ $lead->campaign->advertiser->display_name }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-wallet2 me-2 text-success"></i>
                                <span>Budget: {{ number_format($lead->campaign->budget, 2) }}
                                    {{ $lead->campaign->currency }}</span>
                            </div>
                        </div>

                        @if($lead->status === 'pending')
                            <div class="d-flex gap-2 mt-auto">
                                <form action="{{ route('agency.leads.accept', $lead) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Accept Lead</button>
                                </form>
                                <form action="{{ route('agency.leads.pass', $lead) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">Pass</button>
                                </form>
                            </div>
                        @else
                            <div class="d-flex justify-content-between align-items-center py-2 bg-light rounded-3 mt-auto px-3">
                                <span class="text-muted small">Lead was {{ $lead->status }} on
                                    {{ $lead->responded_at ? $lead->responded_at->format('M d, Y') : 'N/A' }}</span>
                                <a href="{{ route('messages.show', $lead->campaign->advertiser->id) }}"
                                    class="btn btn-sm btn-primary rounded-pill">
                                    <i class="bi bi-chat-fill"></i> Chat
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="display-1 text-muted mb-4"><i class="bi bi-inbox"></i></div>
                <h4>No new leads available</h4>
                <p class="text-muted">When clients near you request services in your category, they will appear here.</p>
            </div>
        @endforelse
    </div>
@endsection