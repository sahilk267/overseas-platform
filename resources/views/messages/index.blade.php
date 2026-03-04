@extends('layouts.app')

@section('title', 'Messages')

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold">Messages</h2>
                <p class="text-muted">Manage your conversations with clients, vendors, and support.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 fw-bold text-primary">Support & Admin</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Need help? Message our support team directly.</p>
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        @php
                            $adminProfile = \App\Models\Profile::whereIn('profile_type', ['admin', 'global_admin', 'developer'])->first();
                        @endphp
                        @if($adminProfile)
                            <input type="hidden" name="receiver_id" value="{{ $adminProfile->id }}">
                            <div class="mb-3">
                                <textarea name="body" class="form-control" rows="3"
                                    placeholder="Describe your issue..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">Message Admin</button>
                        @else
                            <div class="alert alert-warning small">Admin support currently unavailable.</div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 fw-bold text-primary">Conversations</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($conversations as $conversation)
                            @php
                                $otherProfile = $conversation->sender_profile_id == $currentProfile->id
                                    ? $conversation->receiverProfile
                                    : $conversation->senderProfile;
                            @endphp
                            <a href="{{ route('messages.show', $otherProfile) }}"
                                class="list-group-item list-group-item-action p-4 border-0 {{ !$conversation->is_read && $conversation->receiver_profile_id == $currentProfile->id ? 'bg-light' : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold me-3"
                                        style="width: 50px; height: 50px; min-width: 50px;">
                                        {{ strtoupper(substr($otherProfile->display_name, 0, 1)) }}
                                    </div>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between mb-1">
                                            <h6 class="mb-0 fw-bold">{{ $otherProfile->display_name }}</h6>
                                            <small class="text-muted">{{ $conversation->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-0 text-muted small text-truncate" style="max-width: 400px;">
                                                {{ $conversation->sender_profile_id == $currentProfile->id ? 'You: ' : '' }}{{ $conversation->body }}
                                            </p>
                                            @if(!$conversation->is_read && $conversation->receiver_profile_id == $currentProfile->id)
                                                <span class="badge bg-primary rounded-pill">New</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <div class="display-1 text-muted opacity-25 mb-4"><i class="bi bi-chat-left-dots"></i></div>
                                <h5 class="text-muted">No messages yet</h5>
                                <p class="small text-muted">Aap unse baat kar sakte hain jinhone aapka lead accept kiya hai.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection