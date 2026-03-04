@extends('layouts.app')

@section('title', 'Add New Asset')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="bg-primary p-4 text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">List a New Advertising Asset</h4>
                            <p class="mb-0 opacity-75">Fill in the details to make your asset available for booking.</p>
                        </div>
                        <i class="bi bi-plus-square fs-1"></i>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('inventory.store') }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                <!-- Basic Information Section -->
                                <div class="col-12 border-bottom pb-2 mb-2">
                                    <h6 class="fw-bold text-primary mb-0 uppercase small tracking-wider">Basic Information
                                    </h6>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Asset Title /
                                        Name</label>
                                    <input type="text" name="title" class="form-control form-control-lg rounded-3 shadow-sm"
                                        placeholder="e.g. Main Street Digital Billboard" required>
                                    <small class="text-muted">A clear name helps clients find your asset easily.</small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Inventory Type</label>
                                    <select name="inventory_type" class="form-select form-select-lg rounded-3 shadow-sm"
                                        required>
                                        <option value="">Select Type</option>
                                        <option value="billboard">Billboard</option>
                                        <option value="digital_screen">Digital Screen</option>
                                        <option value="poster">Poster</option>
                                        <option value="banner">Banner</option>
                                        <option value="vehicle">Vehicle / Transit</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Primary
                                        Category</label>
                                    <select name="category_id" class="form-select rounded-3 shadow-sm" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <optgroup label="{{ $category->name }}">
                                                <option value="{{ $category->id }}">{{ $category->name }} (Main)</option>
                                                @foreach($category->children as $child)
                                                    <option value="{{ $child->id }}">{{ $child->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Operating City /
                                        Location</label>
                                    <select name="location_id" class="form-select rounded-3 shadow-sm" required>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->city }}, {{ $location->country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Specification Section -->
                                <div class="col-12 border-bottom pb-2 mb-2 mt-5">
                                    <h6 class="fw-bold text-primary mb-0 uppercase small tracking-wider">Pricing &
                                        Specifications</h6>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Price Per Day
                                        (₹)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">₹</span>
                                        <input type="number" name="price_per_day"
                                            class="form-control border-start-0 rounded-end-3 shadow-sm" placeholder="0.00"
                                            step="0.01" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Min. Booking
                                        Days</label>
                                    <input type="number" name="min_booking_days" class="form-control rounded-3 shadow-sm"
                                        value="1" min="1" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Dimensions</label>
                                    <input type="text" name="dimensions" class="form-control rounded-3 shadow-sm"
                                        placeholder="e.g. 20ft x 10ft">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Detailed
                                        Description</label>
                                    <textarea name="description" rows="4" class="form-control rounded-3 shadow-sm"
                                        placeholder="Describe viewability, peak hours, audience demographics..."></textarea>
                                </div>

                                <div class="col-12 mt-5">
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                                            <i class="bi bi-cloud-arrow-up me-2"></i> Publish Asset
                                        </button>
                                        <a href="{{ route('inventory.index') }}"
                                            class="btn btn-outline-secondary btn-lg px-5 rounded-pill">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection