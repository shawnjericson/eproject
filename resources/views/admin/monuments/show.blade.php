@extends('layouts.admin')

@section('title', __('admin.monument_details'))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 mb-1">{{ __('admin.monument_details') }}</h1>
            <p class="text-muted mb-0">{{ Str::limit($monument->title, 80) }}</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('admin.monuments.edit', $monument) }}" class="btn btn-modern-primary">
                    <i class="bi bi-pencil me-1"></i>{{ __('admin.edit') }}
                </a>
                <a href="{{ route('admin.monuments.index') }}" class="btn btn-modern-secondary">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('admin.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Monument Header -->
            <div class="modern-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="mb-2">{{ $monument->title }}</h2>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span class="badge bg-{{ $monument->status === 'approved' ? 'success' : ($monument->status === 'pending' ? 'warning' : ($monument->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                    {{ __('admin.' . $monument->status) }}
                                </span>
                                @if($monument->status === 'rejected' && $monument->rejection_reason)
                                    <span class="badge bg-danger" title="{{ __('admin.rejection_reason') }}: {{ $monument->rejection_reason }}">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ Str::limit($monument->rejection_reason, 20) }}
                                    </span>
                                @endif
                                @if($monument->is_world_wonder)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill me-1"></i>{{ __('admin.world_wonder') }}
                                    </span>
                                @endif
                                <span class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>{{ __('admin.zones.' . strtolower($monument->zone)) }}
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>{{ $monument->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            @if($monument->image)
            <div class="modern-card mb-4">
                <div class="card-body p-0">
                    <img src="{{ $monument->image_url }}" 
                         alt="{{ $monument->title }}" 
                         class="img-fluid rounded w-100" 
                         style="max-height: 400px; object-fit: cover;">
                </div>
            </div>
            @endif

            <!-- Description -->
            @if($monument->description)
            <div class="modern-card mb-4">
                <div class="card-body">
                    <h4 class="mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>{{ __('admin.overview') }}
                    </h4>
                    <p class="lead text-muted">{{ $monument->description }}</p>
                </div>
            </div>
            @endif

            <!-- Content -->
            <div class="modern-card mb-4">
                <div class="card-body">
                    <h4 class="mb-3">
                        <i class="bi bi-book text-primary me-2"></i>{{ __('admin.full_content') }}
                    </h4>
                    <div class="content-body">
                        {!! $monument->content !!}
                    </div>
                </div>
            </div>

            <!-- Location & Map -->
            @if($monument->location || $monument->map_embed)
            <div class="modern-card mb-4">
                <div class="card-body">
                    <h4 class="mb-3">
                        <i class="bi bi-geo-alt text-primary me-2"></i>{{ __('admin.location') }} & {{ __('admin.map') }}
                    </h4>
                    
                    @if($monument->location)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>{{ __('admin.address') }}:</strong></p>
                            <p class="text-muted">{{ $monument->location }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>{{ __('admin.zone') }}:</strong></p>
                            <span class="badge bg-primary">{{ $monument->zone }}</span>
                        </div>
                    </div>
                    @endif

                    @if($monument->map_embed)
                        <div class="mb-3">
                            <div class="ratio ratio-16x9" style="border-radius: 10px; overflow: hidden;">
                                {!! $monument->map_embed !!}
                            </div>
                        </div>
                    @elseif($monument->location)
                        <div class="mt-3">
                            <div id="map" style="height: 300px; border-radius: 10px;" class="border"></div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Gallery Images -->
            @if($monument->gallery->count() > 0)
            <div class="modern-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">
                            <i class="bi bi-images text-primary me-2"></i>{{ __('admin.gallery') }}
                            <span class="badge bg-primary ms-2">{{ $monument->gallery->count() }}</span>
                        </h4>
                        <a href="{{ route('admin.gallery.create') }}?monument_id={{ $monument->id }}" class="btn btn-modern-primary">
                            <i class="bi bi-plus-circle me-1"></i>{{ __('admin.add_images') }}
                        </a>
                    </div>
                    <div class="row g-3">
                        @foreach($monument->gallery as $gallery)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <img src="{{ $gallery->image_url }}" 
                                         alt="{{ $gallery->title }}" 
                                         class="card-img-top" 
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $gallery->title }}</h6>
                                        @if($gallery->description)
                                            <p class="card-text text-muted small">{{ Str::limit($gallery->description, 50) }}</p>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                                {{ __('admin.edit') }}
                                            </a>
                                            <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="flex-fill">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" 
                                                        onclick="return confirm('{{ __('admin.confirm_delete_image') }}')">
                                                    {{ __('admin.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="modern-card mb-4">
                <div class="card-body text-center py-5">
                    <i class="bi bi-images display-1 text-muted"></i>
                    <h5 class="mt-3">{{ __('admin.no_gallery_images') }}</h5>
                    <p class="text-muted mb-4">{{ __('admin.add_some_images_to_showcase') }}</p>
                    <a href="{{ route('admin.gallery.create') }}?monument_id={{ $monument->id }}" class="btn btn-modern-primary">
                        <i class="bi bi-plus me-1"></i>{{ __('admin.add_images') }}
                    </a>
                </div>
            </div>
            @endif

            <!-- Feedbacks -->
            @if($monument->feedbacks->count() > 0)
            <div class="modern-card">
                <div class="card-body">
                    <h4 class="mb-3">
                        <i class="bi bi-chat-dots text-primary me-2"></i>{{ __('admin.recent_feedbacks') }} ({{ $monument->feedbacks->count() }})
                    </h4>
                    @foreach($monument->feedbacks->take(5) as $feedback)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $feedback->name }}</h6>
                                    <small class="text-muted">{{ $feedback->email }}</small>
                                </div>
                                <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mt-2 mb-0">{{ $feedback->message }}</p>
                        </div>
                    @endforeach
                    @if($monument->feedbacks->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('admin.feedbacks.index') }}?monument_id={{ $monument->id }}" class="btn btn-modern-secondary">
                                {{ __('admin.view_all_feedbacks') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Monument Information -->
            <div class="modern-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle text-primary me-2"></i>{{ __('admin.monument_information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.zone') }}:</span>
                        <span class="badge bg-light text-dark">{{ __('admin.zones.' . strtolower($monument->zone)) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.status') }}:</span>
                        <span class="badge bg-{{ $monument->status === 'approved' ? 'success' : ($monument->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ __('admin.' . $monument->status) }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.created_by') }}:</span>
                        <span class="info-value">{{ $monument->creator->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.created') }}:</span>
                        <span class="info-value">{{ $monument->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.last_updated') }}:</span>
                        <span class="info-value">{{ $monument->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.gallery_images') }}:</span>
                        <span class="info-value">{{ $monument->gallery->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('admin.feedbacks') }}:</span>
                        <span class="info-value">{{ $monument->feedbacks->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="modern-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning text-warning me-2"></i>{{ __('admin.quick_actions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.monuments.edit', $monument) }}" class="btn btn-modern-primary">
                            <i class="bi bi-pencil me-2"></i>{{ __('admin.edit') }} {{ __('admin.monument') }}
                        </a>

                        @if($monument->status !== 'approved' && auth()->user()->isAdmin())
                            <form action="{{ route('admin.monuments.approve', $monument) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-modern-success">
                                    <i class="bi bi-check me-2"></i>{{ __('admin.approve') }} {{ __('admin.monument') }}
                                </button>
                            </form>
                        @endif

                        @if($monument->status === 'pending' && auth()->user()->isAdmin())
                            <button type="button" class="btn btn-modern-warning" onclick="openRejectModal('{{ route('admin.monuments.reject', $monument) }}')">
                                <i class="bi bi-x-circle me-2"></i>{{ __('admin.reject') }} {{ __('admin.monument') }}
                            </button>
                        @endif

                        <a href="{{ route('admin.gallery.create') }}?monument_id={{ $monument->id }}" class="btn btn-modern-secondary">
                            <i class="bi bi-images me-2"></i>{{ __('admin.add_gallery_images') }}
                        </a>

                        <a href="{{ route('admin.monuments.index') }}" class="btn btn-modern-secondary">
                            <i class="bi bi-arrow-left me-2"></i>{{ __('admin.back_to_monuments') }}
                        </a>

                        @if(auth()->user()->isAdmin() || ($monument->status !== 'approved' && auth()->user()->isModerator()))
                            <button type="button" class="btn btn-modern-danger" onclick="openDeleteModal('{{ route('admin.monuments.destroy', $monument) }}')">
                                <i class="bi bi-trash me-2"></i>{{ __('admin.delete') }} {{ __('admin.monument') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.components.reject-modal')
@include('admin.components.delete-modal')
@endsection

@push('styles')
<style>
.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.info-value {
    color: #495057;
    font-size: 0.875rem;
}

.content-body {
    line-height: 1.8;
    font-size: 1.1rem;
    color: #495057;
}

.content-body h1, .content-body h2, .content-body h3, 
.content-body h4, .content-body h5, .content-body h6 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.content-body p {
    margin-bottom: 1.5rem;
}

.content-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.content-body blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6c757d;
}

.content-body ul, .content-body ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.content-body li {
    margin-bottom: 0.5rem;
}
</style>
@endpush

@if($monument->location && !$monument->map_embed)
@push('scripts')
<!-- Google Maps API -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dOWTgaN2KKlqZg&callback=initMap">
</script>

<script>
function initMap() {
    const geocoder = new google.maps.Geocoder();
    const location = "{{ $monument->location }}, {{ $monument->zone }}, Cambodia";

    geocoder.geocode({ address: location }, function(results, status) {
        if (status === 'OK') {
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: results[0].geometry.location,
            });

            const marker = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map,
                title: "{{ $monument->title }}",
                animation: google.maps.Animation.DROP
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px;">
                        <h6><strong>{{ $monument->title }}</strong></h6>
                        <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $monument->location }}</p>
                        <p class="mb-0"><i class="bi bi-tag"></i> {{ $monument->zone }}</p>
                    </div>
                `
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });

            infoWindow.open(map, marker);
        } else {
            const defaultLocation = { lat: 13.4125, lng: 103.8667 };
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: defaultLocation
            });

            document.getElementById('map').innerHTML = `
                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                    <div class="text-center">
                        <i class="bi bi-geo-alt-fill fs-1"></i>
                        <p class="mt-2">Unable to locate exact position</p>
                        <small>Showing general area of {{ $monument->zone }}</small>
                    </div>
                </div>
            `;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof google !== 'undefined') {
        initMap();
    }
});
</script>
@endpush
@endif
