@extends('layouts.admin')

@section('title', 'Edit Setting')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('admin.edit') }} Setting: {{ $setting->key }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }} to Settings
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.settings.update', $setting) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="key" class="form-label">Setting Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('key') is-invalid @enderror" 
                               id="key" name="key" value="{{ old('key', $setting->key) }}" required>
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Use lowercase letters, numbers, and underscores only</div>
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">Setting Value <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('value') is-invalid @enderror" 
                                  id="value" name="value" rows="8" required>{{ old('value', $setting->value) }}</textarea>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Enter the value for this setting. For JSON data, ensure proper formatting.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Update Setting</button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Setting Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>Key:</strong> <code>{{ $setting->key }}</code></li>
                    <li><strong>{{ __('admin.created_at') }}:</strong> {{ $setting->created_at->format('M d, Y H:i') }}</li>
                    <li><strong>Updated:</strong> {{ $setting->updated_at->format('M d, Y H:i') }}</li>
                    <li><strong>Last modified:</strong> {{ $setting->updated_at->diffForHumans() }}</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Quick {{ __('admin.actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($setting->key === 'maintenance_mode')
                        @if($setting->value === 'true')
                            <button type="button" class="btn btn-success" onclick="toggleMaintenance('false')">
                                <i class="bi bi-play"></i> Disable Maintenance
                            </button>
                        @else
                            <button type="button" class="btn btn-warning" onclick="toggleMaintenance('true')">
                                <i class="bi bi-pause"></i> Enable Maintenance
                            </button>
                        @endif
                    @endif
                    
                    @if(str_contains($setting->value, '{') && str_contains($setting->value, '}'))
                        <button type="button" class="btn btn-outline-info" onclick="formatJSON()">
                            <i class="bi bi-code"></i> Format JSON
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="validateJSON()">
                            <i class="bi bi-check-circle"></i> Validate JSON
                        </button>
                    @endif
                    
                    <hr>
                    
                    <form action="{{ route('admin.settings.destroy', $setting) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this setting?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> {{ __('admin.delete') }} Setting
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(str_contains($setting->value, '{') && str_contains($setting->value, '}'))
            <div class="card mt-3">
                <div class="card-header">
                    <h5>JSON Preview</h5>
                </div>
                <div class="card-body">
                    <pre id="jsonPreview" class="small bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;"></pre>
                </div>
            </div>
        @endif

        <div class="card mt-3">
            <div class="card-header">
                <h5>Usage Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li><i class="bi bi-lightbulb text-warning"></i> Test changes on staging first</li>
                    <li><i class="bi bi-lightbulb text-warning"></i> {{ __('admin.back') }}up before major changes</li>
                    <li><i class="bi bi-lightbulb text-warning"></i> Use proper JSON formatting</li>
                    <li><i class="bi bi-lightbulb text-warning"></i> Document custom settings</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const keyInput = document.getElementById('key');
    const valueInput = document.getElementById('value');
    const jsonPreview = document.getElementById('jsonPreview');
    
    // Auto-format key input
    keyInput.addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
    });
    
    // Update JSON preview
    function updateJSONPreview() {
        if (jsonPreview) {
            try {
                const parsed = JSON.parse(valueInput.value);
                jsonPreview.textContent = JSON.stringify(parsed, null, 2);
                valueInput.classList.remove('is-invalid');
            } catch (e) {
                jsonPreview.textContent = 'Invalid JSON: ' + e.message;
                valueInput.classList.add('is-invalid');
            }
        }
    }
    
    // Initial preview update
    if (jsonPreview) {
        updateJSONPreview();
        valueInput.addEventListener('input', updateJSONPreview);
    }
    
    // Global functions for buttons
    window.formatJSON = function() {
        try {
            const parsed = JSON.parse(valueInput.value);
            valueInput.value = JSON.stringify(parsed, null, 2);
            updateJSONPreview();
        } catch (e) {
            alert('Invalid JSON format: ' + e.message);
        }
    };
    
    window.validateJSON = function() {
        try {
            JSON.parse(valueInput.value);
            alert('JSON is valid!');
        } catch (e) {
            alert('Invalid JSON: ' + e.message);
        }
    };
    
    window.toggleMaintenance = function(value) {
        if (confirm('Are you sure you want to ' + (value === 'true' ? 'enable' : 'disable') + ' maintenance mode?')) {
            valueInput.value = value;
            document.querySelector('form').submit();
        }
    };
});
</script>
@endpush
@endsection
