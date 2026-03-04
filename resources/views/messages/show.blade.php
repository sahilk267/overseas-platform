@extends('layouts.app')

@section('title', 'Chat with ' . $profile->display_name)

@section('extra_css')
    <style>
        .chat-container {
            height: 60vh;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .message-bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .message-sent {
            background-color: var(--umaep-primary);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 2px;
        }

        .message-received {
            background-color: white;
            color: #333;
            margin-right: auto;
            border-bottom-left-radius: 2px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .message-time {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 5px;
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center">
            <a href="{{ route('messages.index') }}" class="btn btn-link text-dark me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-3"
                    style="width: 45px; height: 45px;">
                    {{ strtoupper(substr($profile->display_name, 0, 1)) }}
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">{{ $profile->display_name }}</h4>
                    <span class="badge bg-light text-dark border small">{{ ucfirst($profile->profile_type) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="chat-container mb-4" id="chatContainer">
                        @forelse($messages as $message)
                            <div
                                class="message-bubble {{ $message->sender_profile_id == $currentProfile->id ? 'message-sent' : 'message-received' }}">
                                <div class="message-body">{{ $message->body }}</div>
                                <span class="message-time text-end">
                                    {{ $message->created_at->format('h:i A') }}
                                    @if($message->sender_profile_id == $currentProfile->id)
                                        <i class="bi bi-check2{{ $message->is_read ? '-all text-info' : '' }} ms-1"></i>
                                    @endif
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-chat-dots fs-1 opacity-25 d-block mb-3"></i>
                                <p>No messages here yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>

                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $profile->id }}">
                        <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden bg-white border">
                            <input type="text" name="body" class="form-control border-0 px-4"
                                placeholder="Type your message here..." required autocomplete="off">
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('chatContainer');
            container.scrollTop = container.scrollHeight;
        });
    </script>
@endsection