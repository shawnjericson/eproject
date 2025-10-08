@extends('layouts.admin')

@section('title', 'Edit Setting')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-pencil text-primary me-2"></i>Edit Setting: {{ ucwords(str_replace('_', ' ', $setting->key)) }}
            </h1>
            <p class="text-muted mb-0">{{ $setting->description ?? 'Configure this setting' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-modern-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Settings
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">Setting Configuration</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update', $setting) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="key" class="form-label">Setting Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('key') is-invalid @enderror" 
                               id="key" name="key" value="{{ old('key', $setting->key) }}" readonly>
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Setting key cannot be changed after creation</div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="2">{{ old('description', $setting->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="value" class="form-label">Setting Value <span class="text-danger">*</span></label>
                        
                        @if($setting->type == 'boolean')
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="value" name="value" 
                                       value="true" {{ old('value', $setting->value) == 'true' ? 'checked' : '' }}>
                                <label class="form-check-label" for="value">
                                    {{ old('value', $setting->value) == 'true' ? 'Enabled' : 'Disabled' }}
                                </label>
                            </div>
                        @elseif($setting->type == 'number')
                            <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                   id="value" name="value" value="{{ old('value', $setting->value) }}"
                                   @if($setting->min) min="{{ $setting->min }}" @endif
                                   @if($setting->max) max="{{ $setting->max }}" @endif>
                        @elseif($setting->type == 'email')
                            <input type="email" class="form-control @error('value') is-invalid @enderror" 
                                   id="value" name="value" value="{{ old('value', $setting->value) }}">
                        @elseif($setting->type == 'textarea')
                            <textarea class="form-control @error('value') is-invalid @enderror" 
                                      id="value" name="value" rows="4">{{ old('value', $setting->value) }}</textarea>
                        @else
                            <input type="text" class="form-control @error('value') is-invalid @enderror" 
                                   id="value" name="value" value="{{ old('value', $setting->value) }}">
                        @endif
                        
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($setting->min || $setting->max)
                            <div class="form-text">
                                @if($setting->min && $setting->max)
                                    Range: {{ $setting->min }} - {{ $setting->max }}
                                @elseif($setting->min)
                                    Minimum: {{ $setting->min }}
                                @elseif($setting->max)
                                    Maximum: {{ $setting->max }}
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-modern-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Setting
                        </button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-modern-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">Setting Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Category:</strong> 
                        <span class="badge bg-light text-dark">{{ ucfirst($setting->category ?? 'general') }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>Type:</strong> 
                        <span class="badge bg-info">{{ ucfirst($setting->type ?? 'text') }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>Created:</strong> {{ $setting->created_at->format('M d, Y H:i') }}
                    </li>
                    <li class="mb-2">
                        <strong>Updated:</strong> {{ $setting->updated_at->format('M d, Y H:i') }}
                    </li>
                    <li class="mb-2">
                        <strong>Last modified:</strong> {{ $setting->updated_at->diffForHumans() }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="modern-card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($setting->key === 'maintenance_mode')
                        @if($setting->value === 'true')
                            <button type="button" class="btn btn-modern-success" onclick="toggleMaintenance('false')">
                                <i class="bi bi-play me-2"></i>Disable Maintenance
                            </button>
                        @else
                            <button type="button" class="btn btn-modern-warning" onclick="toggleMaintenance('true')">
                                <i class="bi bi-pause me-2"></i>Enable Maintenance
                            </button>
                        @endif
                    @endif
                    
                    @if($setting->type == 'boolean')
                        <button type="button" class="btn btn-modern-secondary" onclick="toggleBoolean()">
                            <i class="bi bi-toggle-on me-2"></i>Toggle Value
                        </button>
                    @endif
                    
                    <hr>
                    
                    <form action="{{ route('admin.settings.destroy', $setting) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this setting?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-modern-danger w-100">
                            <i class="bi bi-trash me-2"></i>Delete Setting
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="modern-card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Usage Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        Test changes on staging first
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        Backup before major changes
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        Use proper formatting for each type
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        Document custom settings
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const valueInput = document.getElementById('value');
    
    // Handle boolean toggle
    if (valueInput && valueInput.type === 'checkbox') {
        valueInput.addEventListener('change', function() {
            const label = document.querySelector('label[for="value"]');
            label.textContent = this.checked ? 'Enabled' : 'Disabled';
        });
    }
    
    // Global functions for buttons
    window.toggleMaintenance = function(value) {
        if (confirm('Are you sure you want to ' + (value === 'true' ? 'enable' : 'disable') + ' maintenance mode?')) {
            if (valueInput.type === 'checkbox') {
                valueInput.checked = value === 'true';
                valueInput.dispatchEvent(new Event('change'));
            } else {
                valueInput.value = value;
            }
            document.querySelector('form').submit();
        }
    };
    
    window.toggleBoolean = function() {
        if (valueInput && valueInput.type === 'checkbox') {
            valueInput.checked = !valueInput.checked;
            valueInput.dispatchEvent(new Event('change'));
        }
    };
});
</script>
@endpush