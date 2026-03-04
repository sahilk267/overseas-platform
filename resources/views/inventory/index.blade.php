@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Ad Assets & Inventory</h2>
            <p class="text-muted">List your billboards, digital screens, and other advertising spaces.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('inventory.create') }}" class="btn btn-info text-white btn-lg rounded-pill shadow-sm px-4">
                <i class="bi bi-building-add me-2"></i>Add New Asset
            </a>
        </div>
    </div>

    <div class="row g-4">
        @forelse($inventory as $item)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span
                                class="badge bg-primary-subtle text-primary border border-primary-subtle px-3">{{ ucfirst(str_replace('_', ' ', $item->inventory_type)) }}</span>
                            <span class="fw-bold">₹{{ number_format($item->price_per_day) }}/day</span>
                        </div>
                        <h5 class="fw-bold">{{ $item->title }}</h5>
                        <p class="text-muted small text-truncate"><i
                                class="bi bi-geo-alt me-1"></i>{{ $item->location->city ?? 'Unknown' }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <span class="text-muted small"><i class="bi bi-calendar-check me-1"></i> Available Now</span>
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">Edit Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-building display-4 text-light"></i>
                <h5 class="text-muted mt-3">Your inventory is empty.</h5>
                <p class="small">Start earning by listing your advertising spots.</p>
            </div>
        @endforelse
    </div>
@endsection