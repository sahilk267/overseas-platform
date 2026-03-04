@extends('layouts.app')

@section('title', 'Edit Agency Categories')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-2">
            <a href="{{ route('admin.agencies.index') }}" class="btn btn-link text-decoration-none p-0 me-3">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <h2 class="fw-bold mb-0">Manage Categories: {{ $agency->display_name }}</h2>
        </div>
        <p class="text-muted">Select the specific advertising services this agency provides.</p>
    </div>
</div>

<form action="{{ route('admin.agencies.update_categories', $agency) }}" method="POST">
    @csrf
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="row g-4">
                @foreach($categories as $parent)
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 border rounded-3 h-100 bg-light-subtle">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">{{ $parent->name }}</h6>
                        @foreach($parent->children as $child)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $child->id }}" id="cat_{{ $child->id }}" 
                                {{ $agency->categories->contains($child->id) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="cat_{{ $child->id }}">
                                {{ $child->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer bg-white border-0 p-4 pt-0">
            <button type="submit" class="btn btn-primary rounded-pill px-5">Save Category Assignments</button>
        </div>
    </div>
</form>
@endsection
