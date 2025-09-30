@extends('layouts.admin')

@section('title', 'Feedback Details')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Feedback Details</h1>
        <p class="text-muted mb-0">From {{ $feedback->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="mailto:{{ $feedback->email }}?subject=Re: Your feedback about {{ $feedback->monument ? $feedback->monument->title : 'our website' }}"
           class="btn-minimal btn-primary">
            <i class="bi bi-reply"></i> Reply
        </a>
        <a href="{{ route('admin.feedbacks.index') }}" class="btn-minimal">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-minimal">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Feedback Message</h5>
                    <small class="text-muted">{{ $feedback->created_at->format('M d, Y H:i') }}</small>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $feedback->name }}</h6>
                            <a href="mailto:{{ $feedback->email }}" class="text-muted text-decoration-none">
                                {{ $feedback->email }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="border-start border-primary border-3 ps-3 mb-4">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $feedback->message }}</p>
                </div>

                @if($feedback->monument)
                    <div class="mt-4">
                        <h6>Related Monument</h6>
                        <div class="card-minimal bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        @if($feedback->monument->image)
                                            <img src="{{ asset('storage/' . $feedback->monument->image) }}"
                                                 alt="{{ $feedback->monument->title }}"
                                                 class="img-fluid rounded">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center rounded"
                                                 style="height: 100px;">
                                                <i class="bi bi-building text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h6>{{ $feedback->monument->title }}</h6>
                                        <p class="text-muted mb-2">{{ Str::limit($feedback->monument->description, 150) }}</p>
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge bg-info">{{ $feedback->monument->zone }}</span>
                                            @if($feedback->monument->status == 'approved')
                                                <span class="badge bg-success">{{ __('admin.approved') }}</span>
                                            @elseif($feedback->monument->status == 'pending')
                                                <span class="badge bg-warning">{{ __('admin.pending') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('admin.draft') }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('admin.monuments.show', $feedback->monument) }}"
                                           class="btn-minimal btn-primary">
                                            <i class="bi bi-building"></i> {{ __('admin.view') }} Monument
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-minimal">
            <div class="card-header">
                <h5>Feedback Details</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>{{ __('admin.name') }}:</strong> {{ $feedback->name }}
                    </li>
                    <li class="mb-2">
                        <strong>{{ __('admin.email') }}:</strong> 
                        <a href="mailto:{{ $feedback->email }}" class="text-decoration-none">
                            {{ $feedback->email }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <strong>Monument:</strong> 
                        @if($feedback->monument)
                            <a href="{{ route('admin.monuments.show', $feedback->monument) }}" class="text-decoration-none">
                                {{ $feedback->monument->title }}
                            </a>
                        @else
                            <span class="text-muted">General Feedback</span>
                        @endif
                    </li>
                    <li class="mb-2">
                        <strong>Submitted:</strong> {{ $feedback->created_at->format('M d, Y H:i') }}
                    </li>
                    <li class="mb-2">
                        <strong>Time ago:</strong> {{ $feedback->created_at->diffForHumans() }}
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
                    <a href="mailto:{{ $feedback->email }}?subject=Re: Your feedback about {{ $feedback->monument ? $feedback->monument->title : 'our website' }}&body=Dear {{ $feedback->name }},%0D%0A%0D%0AThank you for your feedback.%0D%0A%0D%0ABest regards,%0D%0ATravel History Blog Team"
                       class="btn-minimal btn-primary">
                        <i class="bi bi-reply"></i> Reply via {{ __('admin.email') }}
                    </a>

                    @if($feedback->monument)
                        <a href="{{ route('admin.monuments.show', $feedback->monument) }}"
                           class="btn-minimal">
                            <i class="bi bi-building"></i> {{ __('admin.view') }} Related Monument
                        </a>
                    @endif

                    <a href="{{ route('admin.feedbacks.index') }}?search={{ urlencode($feedback->email) }}"
                       class="btn-minimal">
                        <i class="bi bi-search"></i> More from this User
                    </a>

                    <hr>

                    <form action="{{ route('admin.feedbacks.destroy', $feedback) }}"
                          method="POST"
                          onsubmit="return confirm('{{ __('admin.confirm_delete_feedback') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-minimal btn-danger w-100">
                            <i class="bi bi-trash"></i> {{ __('admin.delete') }} Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Feedbacks from same user -->
        @php
            $userFeedbacks = \App\Models\Feedback::where('email', $feedback->email)
                                                ->where('id', '!=', $feedback->id)
                                                ->orderBy('created_at', 'desc')
                                                ->limit(3)
                                                ->get();
        @endphp
        
        @if($userFeedbacks->count() > 0)
            <div class="card-minimal mt-3">
                <div class="card-header">
                    <h5>Other Feedbacks from {{ $feedback->name }}</h5>
                </div>
                <div class="card-body">
                    @foreach($userFeedbacks as $userFeedback)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <small class="text-muted">
                                        {{ $userFeedback->created_at->format('M d, Y') }}
                                        @if($userFeedback->monument)
                                            • {{ Str::limit($userFeedback->monument->title, 20) }}
                                        @endif
                                    </small>
                                    <p class="mb-1 small">{{ Str::limit($userFeedback->message, 60) }}</p>
                                </div>
                                <a href="{{ route('admin.feedbacks.show', $userFeedback) }}" 
                                   class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
