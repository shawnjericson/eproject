@extends('layouts.admin')

@section('title', __('admin.gallery_image_details'))

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.gallery_image_details') }}</h1>
        <p class="text-muted mb-0">{{ Str::limit($gallery->title, 80) }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn-minimal btn-primary">
            <i class="bi bi-pencil"></i> {{ __('admin.edit') }}
        </a>
        <a href="{{ route('admin.gallery.index') }}" class="btn-minimal">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-minimal">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ $gallery->image_url }}"
                         alt="{{ $gallery->title }}"
                         class="img-fluid rounded shadow">
                </div>

                <h5>{{ $gallery->title }}</h5>
                
                @if($gallery->description)
                    <div class="mt-3">
                        <h6>{{ __('admin.description') }}</h6>
                        <p class="text-muted">{{ $gallery->description }}</p>
                    </div>
                @endif

                <div class="mt-4">
                    <h6>{{ __('admin.monument_information') }}</h6>
                    <div class="card-minimal bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    @if($gallery->monument->image)
                                        <img src="{{ $gallery->monument->image_url }}"
                                             alt="{{ $gallery->monument->title }}"
                                             class="img-fluid rounded">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center rounded"
                                             style="height: 100px;">
                                            <i class="bi bi-building text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h6>{{ $gallery->monument->title }}</h6>
                                    <p class="text-muted mb-2">{{ Str::limit($gallery->monument->description, 150) }}</p>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-info">{{ $gallery->monument->zone }}</span>
                                        @if($gallery->monument->status == 'approved')
                                            <span class="badge bg-success">{{ __('admin.approved') }}</span>
                                        @elseif($gallery->monument->status == 'pending')
                                            <span class="badge bg-warning">{{ __('admin.pending') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('admin.draft') }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.monuments.show', $gallery->monument) }}"
                                           class="btn-minimal btn-primary">
                                            <i class="bi bi-building"></i> {{ __('admin.view') }} {{ __('admin.monument') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-minimal">
            <div class="card-header">
                <h5>{{ __('admin.image') }} Details</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>{{ __('admin.title') }}:</strong> {{ $gallery->title }}
                    </li>
                    <li class="mb-2">
                        <strong>Monument:</strong> 
                        <a href="{{ route('admin.monuments.show', $gallery->monument) }}" class="text-decoration-none">
                            {{ $gallery->monument->title }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <strong>Zone:</strong> 
                        <span class="badge bg-info">{{ $gallery->monument->zone }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.created_at') }}:</strong> {{ $gallery->created_at->format('M d, Y H:i') }}
                    </li>
                    <li class="mb-2">
                        <strong>Updated:</strong> {{ $gallery->updated_at->format('M d, Y H:i') }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="card-minimal mt-3">
            <div class="card-header">
                <h5>{{ __('admin.actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn-minimal btn-primary">
                        <i class="bi bi-pencil"></i> {{ __('admin.edit') }} {{ __('admin.image') }}
                    </a>

                    <a href="{{ route('admin.monuments.show', $gallery->monument) }}" class="btn-minimal">
                        <i class="bi bi-building"></i> {{ __('admin.view') }} {{ __('admin.monument') }}
                    </a>

                    <a href="{{ $gallery->image_url }}"
                       target="_blank" class="btn-minimal">
                        <i class="bi bi-download"></i> {{ __('admin.view') }} {{ __('admin.full_size') }}
                    </a>
                    
                    <hr>
                    
                    <form action="{{ route('admin.gallery.destroy', $gallery) }}" 
                          method="POST" 
                          onsubmit="return confirm('{{ __('admin.confirm_delete_image') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> {{ __('admin.delete') }} {{ __('admin.image') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Related Images -->
        @php
            $relatedImages = $gallery->monument->gallery()->where('id', '!=', $gallery->id)->limit(3)->get();
        @endphp

        @if($relatedImages->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Other Images from this Monument</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($relatedImages as $relatedImage)
                            <div class="col-4 mb-2">
                                <a href="{{ route('admin.gallery.show', $relatedImage) }}">
                                    <img src="{{ $relatedImage->image_url }}"
                                         alt="{{ $relatedImage->title }}"
                                         class="img-fluid rounded"
                                         style="height: 60px; width: 100%; object-fit: cover;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if($gallery->monument->gallery->count() > 4)
                        <div class="text-center mt-2">
                            <a href="{{ route('admin.gallery.index') }}?monument_id="{{  $gallery->monument->id  }}"" 
                               class="btn btn-sm btn-outline-primary">
                                {{ __('admin.view') }} All ({{ $gallery->monument->gallery->count() }} images)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.card img {
    transition: transform 0.3s ease;
}
.card img:hover {
    transform: scale(1.02);
}
</style>
@endpush
