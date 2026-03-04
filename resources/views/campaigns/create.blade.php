@extends('layouts.app')

@section('title', isset($isEdit) ? 'Edit Campaign' : 'Create Campaign')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">{{ isset($isEdit) ? 'Edit Campaign' : 'Launch New Campaign' }}</h2>
        <p class="text-muted">{{ isset($isEdit) ? 'Update your campaign details below.' : 'Fill in the details to start your next advertising journey.' }}</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ isset($isEdit) ? route('campaigns.update', $campaign->id) : route('campaigns.store') }}" method="POST">
            @csrf
            @if(isset($isEdit))
                @method('PUT')
            @endif
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Campaign Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Summer Launch 2025" value="{{ $campaign->name ?? old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ad Medium (Parent Category)</label>
                    <select id="parent_category" class="form-select" required onchange="updateSubCategories()">
                        <option value="">Select Medium</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    data-children="{{ $category->children->toJson() }}"
                                    {{ (isset($campaign) && $campaign->category && $campaign->category->parent_id == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Specific Service (Sub-category)</label>
                    <select name="category_id" id="sub_category" class="form-select" required>
                        <option value="">Select Service</option>
                        @if(isset($campaign) && $campaign->category)
                            <option value="{{ $campaign->category_id }}" selected>{{ $campaign->category->name }}</option>
                        @endif
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Target City (Location)</label>
                    <input type="text" name="target_city" class="form-control" placeholder="e.g. Mumbai, Delhi, Lucknow" value="{{ $campaign->target_city ?? old('target_city') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Campaign Goal</label>
                    <select name="campaign_goal" class="form-select" required>
                        <option value="">Select Primary Goal</option>
                        <option value="awareness" {{ (isset($campaign) && $campaign->campaign_goal == 'awareness') ? 'selected' : '' }}>Brand Awareness</option>
                        <option value="leads" {{ (isset($campaign) && $campaign->campaign_goal == 'leads') ? 'selected' : '' }}>Lead Generation</option>
                        <option value="visits" {{ (isset($campaign) && $campaign->campaign_goal == 'visits') ? 'selected' : '' }}>Footfall/Store Visits</option>
                        <option value="sales" {{ (isset($campaign) && $campaign->campaign_goal == 'sales') ? 'selected' : '' }}>Sales/Conversions</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Specific Address/Area Details</label>
                    <input type="text" name="address_details" class="form-control" placeholder="e.g. Near HDFC Bank, Laxmi Nagar Or All over the city" value="{{ $campaign->address_details ?? old('address_details') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Campaign Brief / Requirements</label>
                    <textarea name="brief" class="form-control" rows="3" placeholder="Explain your design requirements, target audience, or any specific instructions for the agency...">{{ $campaign->brief ?? old('brief') }}</textarea>
                </div>

                <script>
                    function updateSubCategories() {
                        const parentSelect = document.getElementById('parent_category');
                        const subSelect = document.getElementById('sub_category');
                        const selectedOption = parentSelect.options[parentSelect.selectedIndex];
                        
                        subSelect.innerHTML = '<option value="">Select Service</option>';
                        
                        if (selectedOption.value) {
                            const childrenData = selectedOption.getAttribute('data-children');
                            if (childrenData) {
                                const children = JSON.parse(childrenData);
                                children.forEach(child => {
                                    const option = document.createElement('option');
                                    option.value = child.id;
                                    option.textContent = child.name;
                                    subSelect.appendChild(option);
                                });
                            }
                        }
                    }
                    
                    // Initial load for edit mode
                    document.addEventListener('DOMContentLoaded', function() {
                        if (document.getElementById('parent_category').value) {
                            // Only populate if empty (meaning we're not in edit mode with a pre-selected child)
                            if (document.getElementById('sub_category').options.length <= 1) {
                                updateSubCategories();
                            }
                        }
                    });
                </script>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Budget (INR)</label>
                    <input type="number" name="budget" class="form-control" placeholder="50000" min="1" value="{{ isset($campaign->budget) ? intval($campaign->budget) : old('budget') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ isset($campaign->start_date) ? $campaign->start_date->format('Y-m-d') : old('start_date') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ isset($campaign->end_date) ? $campaign->end_date->format('Y-m-d') : old('end_date') }}" required>
                </div>
                <div class="col-12 mt-4 text-end">
                    <a href="{{ route('campaigns.index') }}" class="btn btn-light rounded-pill me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">{{ isset($isEdit) ? 'Update Campaign' : 'Launch Campaign' }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
