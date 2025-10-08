@extends('layouts.admin')

@section('title', 'Create Setting')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('admin.create_new') }} Setting</h1>
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
                <form action="{{ route('admin.settings.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="key" class="form-label">Setting Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('key') is-invalid @enderror" 
                               id="key" name="key" value="{{ old('key') }}">
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Use lowercase letters, numbers, and underscores only (e.g., site_name, contact_email)</div>
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">Setting Value <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('value') is-invalid @enderror" 
                                  id="value" name="value" rows="5">{{ old('value') }}</textarea>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Enter the value for this setting. For JSON data, ensure proper formatting.</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Create Setting</button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Setting Guidelines</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success"></i> Use descriptive keys</li>
                    <li><i class="bi bi-check-circle text-success"></i> Follow naming conventions</li>
                    <li><i class="bi bi-check-circle text-success"></i> Validate JSON format</li>
                    <li><i class="bi bi-check-circle text-success"></i> Test values before saving</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Common Settings</h5>
            </div>
            <div class="card-body">
                <h6>General:</h6>
                <ul class="list-unstyled small">
                    <li><code>site_name</code> - Website name</li>
                    <li><code>site_description</code> - Site description</li>
                    <li><code>contact_email</code> - Contact email</li>
                    <li><code>contact_phone</code> - Contact phone</li>
                </ul>
                
                <h6>SEO:</h6>
                <ul class="list-unstyled small">
                    <li><code>meta_title</code> - Default page title</li>
                    <li><code>meta_description</code> - Meta description</li>
                    <li><code>meta_keywords</code> - Meta keywords</li>
                    <li><code>analytics_code</code> - Google Analytics</li>
                </ul>
                
                <h6>Social:</h6>
                <ul class="list-unstyled small">
                    <li><code>facebook_url</code> - Facebook page</li>
                    <li><code>twitter_url</code> - Twitter profile</li>
                    <li><code>instagram_url</code> - Instagram profile</li>
                    <li><code>youtube_url</code> - YouTube channel</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>JSON Example</h5>
            </div>
            <div class="card-body">
                <p class="small">For complex settings like ticker configuration:</p>
                <pre class="small bg-light p-2 rounded"><code>{
  "enabled": true,
  "speed": 50,
  "messages": [
    "Welcome message",
    "Another message"
  ]
}</code></pre>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const keyInput = document.getElementById('key');
    const valueInput = document.getElementById('value');
    
    // Auto-format key input
    keyInput.addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
    });
    
    // JSON validation for specific keys
    valueInput.addEventListener('blur', function() {
        const key = keyInput.value;
        const value = this.value.trim();
        
        // Check if this looks like JSON
        if (key.includes('config') || key.includes('json') || (value.startsWith('{') && value.endsWith('}'))) {
            try {
                JSON.parse(value);
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } catch (e) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                // You might want to show the JSON error
            }
        }
    });
});
</script>
@endpush
@endsection
