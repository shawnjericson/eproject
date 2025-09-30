@extends('layouts.admin')

@section('title', 'Monument Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Monument Details</h1>
        <p class="text-muted mb-0">{{ Str::limit($monument->title, 80) }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.monuments.edit', $monument) }}" class="btn-minimal btn-primary">
            <i class="bi bi-pencil"></i> {{ __('admin.edit') }}
        </a>
        <a href="{{ route('admin.monuments.index') }}" class="btn-minimal">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }}
        </a>
    </div>
</div>

@push('styles')
<style>
.content-section {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.sidebar-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.sidebar-card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
    font-weight: 500;
    color: #495057;
    font-size: 0.95rem;
}

.sidebar-card-body {
    padding: 1.5rem;
}

.elegant-btn {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-weight: 400;
    font-size: 0.875rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    background: white;
    color: #495057;
}

.elegant-btn:hover {
    border-color: #adb5bd;
    background: #f8f9fa;
    color: #495057;
    text-decoration: none;
}

.elegant-btn-primary {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

.elegant-btn-primary:hover {
    background: #0056b3;
    border-color: #0056b3;
    color: white;
}

.elegant-btn-success {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.elegant-btn-success:hover {
    background: #1e7e34;
    border-color: #1e7e34;
    color: white;
}

.elegant-btn-danger {
    background: #dc3545;
    border-color: #dc3545;
    color: white;
}

.elegant-btn-danger:hover {
    background: #c82333;
    border-color: #c82333;
    color: white;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    font-weight: 400;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
    font-size: 0.875rem;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #6c757d;
    font-weight: 400;
}

.info-value {
    color: #495057;
    font-weight: 500;
}

.gallery-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.gallery-card:hover {
    border-color: #adb5bd;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.gallery-actions {
    padding: 0.75rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.gallery-btn {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 4px;
    border: 1px solid #dee2e6;
    background: white;
    color: #495057;
    text-decoration: none;
    transition: all 0.2s ease;
}

.gallery-btn:hover {
    background: #e9ecef;
    color: #495057;
    text-decoration: none;
}

.gallery-btn-danger {
    border-color: #dc3545;
    color: #dc3545;
}

.gallery-btn-danger:hover {
    background: #dc3545;
    color: white;
}
</style>
@endpush

<!-- Monument Header -->
<div class="card-minimal mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="mb-2">{{ $monument->title }}</h2>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-{{ $monument->status === 'approved' ? 'success' : ($monument->status === 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($monument->status) }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-geo-alt"></i> {{ $monument->zone }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-calendar"></i> {{ $monument->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Featured Image -->
        @if($monument->image)
        <div class="card-minimal mb-4">
            <div class="card-body p-0">
                <img src="{{ $monument->image_url }}"
                     alt="{{ $monument->title }}"
                     class="img-fluid rounded w-100"
                     style="max-height: 400px; object-fit: cover;">
            </div>
        </div>
        @endif

        <!-- Short Description -->
        @if($monument->description)
        <div class="card-minimal mb-4">
            <div class="card-body">
                <h3 class="mb-3">
                    <i class="bi bi-info-circle text-primary"></i> Overview
                </h3>
                <p class="lead text-muted">{{ $monument->description }}</p>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="card-minimal mb-4">
            <div class="card-body">
                <h3 class="mb-3">
                    <i class="bi bi-book text-primary"></i> Full Article
                </h3>
                <div class="content-display">
                    {!! $monument->content !!}
                </div>
            </div>
        </div>

        <!-- Location & Map -->
        @if($monument->location || $monument->map_embed)
        <div class="card-minimal mb-4">
            <div class="card-body">
                <h3 class="mb-3">
                    <i class="bi bi-geo-alt text-primary"></i> Location & Map
                </h3>

            @if($monument->location)
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Address:</strong></p>
                    <p class="text-muted">{{ $monument->location }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Zone:</strong></p>
                    <span class="badge bg-primary">{{ $monument->zone }}</span>
                </div>
            </div>
            @endif

            @if($monument->map_embed)
                <!-- Embedded Google Map -->
                <div class="mb-3">
                    <div class="ratio ratio-16x9" style="border-radius: 10px; overflow: hidden;">
                        {!! $monument->map_embed !!}
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Interactive Google Maps view of {{ $monument->title }}
                        </small>
                    </div>
                </div>
            @elseif($monument->location)
                <!-- Fallback Google Map -->
                <div class="mt-3">
                    <div id="map" style="height: 300px; border-radius: 10px;" class="border"></div>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        Click and drag to explore the area. The map shows the approximate location of {{ $monument->title }}.
                    </small>
                </div>
            @endif
        </div>
        @endif

        <!-- Gallery Images -->
        @if($monument->gallery->count() > 0)
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">
                    <i class="bi bi-images text-primary"></i>
                    Gallery <span class="badge bg-primary ms-2">{{ $monument->gallery->count() }}</span>
                </h3>
                <a href="{{ route('admin.gallery.create') }}?monument_id={{ $monument->id }}"
                   class="elegant-btn elegant-btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Images
                </a>
            </div>
            <div class="row g-3">
                @foreach($monument->gallery as $gallery)
                    <div class="col-md-4">
                        <div class="gallery-card h-100">
                            <img src="{{ $gallery->image_url }}"
                                 alt="{{ $gallery->title }}"
                                 class="w-100"
                                 style="height: 200px; object-fit: cover;">
                            <div class="p-3">
                                <h6 class="mb-2" style="font-size: 0.875rem; font-weight: 500;">{{ $gallery->title }}</h6>
                                @if($gallery->description)
                                    <p class="text-muted mb-0" style="font-size: 0.75rem;">{{ Str::limit($gallery->description, 50) }}</p>
                                @endif
                            </div>
                            <div class="gallery-actions">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.gallery.edit', $gallery) }}"
                                       class="gallery-btn flex-fill text-center">Edit</a>
                                    <form action="{{ route('admin.gallery.destroy', $gallery) }}"
                                          method="POST" class="flex-fill"
                                          onsubmit="return confirm('Delete this image?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="gallery-btn gallery-btn-danger w-100">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="content-section text-center py-5">
            <i class="bi bi-images display-1 text-muted"></i>
            <h5 class="mt-3">No gallery images</h5>
            <p class="text-muted mb-4">Add some images to showcase this monument.</p>
            <a href="{{ route('admin.gallery.create') }}?monument_id={{ $monument->id }}"
               class="elegant-btn elegant-btn-primary">
                <i class="bi bi-plus"></i> Add Images
            </a>
        </div>
        @endif

        <!-- Feedbacks -->
        @if($monument->feedbacks->count() > 0)
            <div class="content-section">
                <h3 class="mb-3">
                    <i class="bi bi-chat-dots text-primary"></i> Recent Feedbacks ({{ $monument->feedbacks->count() }})
                </h3>
                @foreach($monument->feedbacks->take(5) as $feedback)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1" style="font-size: 0.875rem;">{{ $feedback->name }}</h6>
                                <small class="text-muted">{{ $feedback->email }}</small>
                            </div>
                            <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mt-2 mb-0" style="font-size: 0.875rem;">{{ $feedback->message }}</p>
                    </div>
                @endforeach

                @if($monument->feedbacks->count() > 5)
                    <div class="text-center">
                        <a href="{{ route('admin.feedbacks.index') }}?monument_id={{ $monument->id }}"
                           class="elegant-btn">
                            View All Feedbacks
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div> <!-- End main content row -->

    <!-- Sidebar - Monument Information & Quick Actions -->
    <div class="col-lg-4">
        <!-- Monument Information -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                Monument Information
            </div>
            <div class="sidebar-card-body">
                <div class="info-item">
                    <span class="info-label">Zone:</span>
                    <span class="status-badge bg-light text-dark">{{ $monument->zone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Trạng thái:</span>
                    @if($monument->status == 'approved')
                        <span class="status-badge bg-success text-white">Đã duyệt</span>
                    @elseif($monument->status == 'pending')
                        <span class="status-badge bg-warning text-dark">Đang chờ</span>
                    @else
                        <span class="status-badge bg-secondary text-white">Bản nháp</span>
                    @endif
                </div>
                <div class="info-item">
                    <span class="info-label">Người tạo:</span>
                    <span class="info-value">{{ $monument->creator->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày tạo at:</span>
                    <span class="info-value">{{ $monument->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Updated at:</span>
                    <span class="info-value">{{ $monument->updated_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Gallery Hình ảnh:</span>
                    <span class="info-value">{{ $monument->gallery->count() }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Feedbacks:</span>
                    <span class="info-value">{{ $monument->feedbacks->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                Quick Thao tác
            </div>
            <div class="sidebar-card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.monuments.edit', $monument) }}" class="elegant-btn elegant-btn-primary">
                        <i class="bi bi-pencil"></i> Sửa Monument
                    </a>

                    @if($monument->status !== 'approved' && auth()->user()->isAdmin())
                        <form action="{{ route('admin.monuments.approve', $monument) }}" method="POST">
                            @csrf
                            <button type="submit" class="elegant-btn elegant-btn-success w-100">
                                <i class="bi bi-check"></i> Approve Monument
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.gallery.create') }}?monument_id={{ $monument->id }}"
                       class="elegant-btn">
                        <i class="bi bi-images"></i> Add Gallery Hình ảnh
                    </a>

                    <hr class="my-3">

                    @if(auth()->user()->isAdmin() || ($monument->status !== 'approved' && auth()->user()->isModerator()))
                        <form action="{{ route('admin.monuments.destroy', $monument) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this monument? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="elegant-btn elegant-btn-danger w-100">
                                <i class="bi bi-trash"></i> Xóa Monument
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> <!-- End row -->

@if($monument->location && !$monument->map_embed)
@push('scripts')
<!-- Google Maps API -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dOWTgaN2KKlqZg&callback=initMap">
</script>

<script>
function initMap() {
    // Try to geocode the location
    const geocoder = new google.maps.Geocoder();
    const location = "{{ $monument->location }}, {{ $monument->zone }}, Cambodia";

    geocoder.geocode({ address: location }, function(results, status) {
        if (status === 'OK') {
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: results[0].geometry.location,
                styles: [
                    {
                        "featureType": "all",
                        "elementType": "geometry.fill",
                        "stylers": [{"weight": "2.00"}]
                    },
                    {
                        "featureType": "all",
                        "elementType": "geometry.stroke",
                        "stylers": [{"color": "#9c9c9c"}]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text",
                        "stylers": [{"visibility": "on"}]
                    }
                ]
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

            // Open info window by default
            infoWindow.open(map, marker);
        } else {
            // Fallback to default Cambodia location
            const defaultLocation = { lat: 13.4125, lng: 103.8667 }; // Siem Reap
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

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    if (typeof google !== 'undefined') {
        initMap();
    }
});
</script>
@endpush
@endif

@endsection
