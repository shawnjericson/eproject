@extends('layouts.admin')

@section('title', __('admin.admin_settings'))

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-gear text-primary me-2"></i>{{ __('admin.admin_settings') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.configure_system_settings') }}</p>
        </div>
        <div class="d-flex gap-2">
            {{-- <a href="{{ route('admin.settings.create') }}" class="btn btn-modern-primary">
                <i class="bi bi-plus me-2"></i>{{ __('admin.add_new') }} {{ __('admin.setting') }}
            </a> --}}
        </div>
    </div>
</div>

<!-- Settings Categories -->
<div class="row">
    <!-- File Upload Settings -->
    <div class="col-lg-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-cloud-upload text-primary me-2"></i>{{ __('admin.file_upload_settings') }}
                </h5>
            </div>
            <div class="card-body">
                @php
                    $fileSettings = $settings->where('category', 'file_upload');
                @endphp
                
                @if($fileSettings->count() > 0)
                    @foreach($fileSettings as $setting)
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</h6>
                                    <p class="text-muted small mb-2">{{ $setting->description }}</p>
                                    <div class="setting-value">
                                        @if($setting->type == 'boolean')
                                            <span class="badge {{ $setting->value == 'true' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $setting->value == 'true' ? __('admin.enabled') : __('admin.disabled') }}
                                            </span>
                                        @else
                                            <code class="text-primary">{{ $setting->value }}</code>
                                        @endif
                                    </div>
                                </div>
                                <div class="setting-actions">
                                    {{--                                         {{-- <a href="{{ route('admin.settings.edit', $setting) }}" 
                                           class="btn btn-sm btn-modern-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a> --}} --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">{{ __('admin.no_file_upload_settings_configured') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- User Management Settings -->
    <div class="col-lg-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people text-primary me-2"></i>{{ __('admin.user_management_settings') }}
                </h5>
            </div>
            <div class="card-body">
                @php
                    $userSettings = $settings->where('category', 'user_management');
                @endphp
                
                @if($userSettings->count() > 0)
                    @foreach($userSettings as $setting)
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</h6>
                                    <p class="text-muted small mb-2">{{ $setting->description }}</p>
                                    <div class="setting-value">
                                        @if($setting->type == 'boolean')
                                            <span class="badge {{ $setting->value == 'true' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $setting->value == 'true' ? __('admin.enabled') : __('admin.disabled') }}
                                            </span>
                                        @else
                                            <code class="text-primary">{{ $setting->value }}</code>
                                        @endif
                                    </div>
                                </div>
                                <div class="setting-actions">
                                    {{--                                         {{-- <a href="{{ route('admin.settings.edit', $setting) }}" 
                                           class="btn btn-sm btn-modern-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a> --}} --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">{{ __('admin.no_user_management_settings_configured') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Content Settings -->
    <div class="col-lg-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-text text-primary me-2"></i>{{ __('admin.content_settings') }}
                </h5>
            </div>
            <div class="card-body">
                @php
                    $contentSettings = $settings->where('category', 'content');
                @endphp
                
                @if($contentSettings->count() > 0)
                    @foreach($contentSettings as $setting)
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</h6>
                                    <p class="text-muted small mb-2">{{ $setting->description }}</p>
                                    <div class="setting-value">
                                        @if($setting->type == 'boolean')
                                            <span class="badge {{ $setting->value == 'true' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $setting->value == 'true' ? __('admin.enabled') : __('admin.disabled') }}
                                            </span>
                                        @else
                                            <code class="text-primary">{{ $setting->value }}</code>
                                        @endif
                                    </div>
                                </div>
                                <div class="setting-actions">
                                    {{--                                         {{-- <a href="{{ route('admin.settings.edit', $setting) }}" 
                                           class="btn btn-sm btn-modern-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a> --}} --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">{{ __('admin.no_content_settings_configured') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- System Settings -->
    <div class="col-lg-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-cpu text-primary me-2"></i>{{ __('admin.system_settings') }}
                </h5>
            </div>
            <div class="card-body">
                @php
                    $systemSettings = $settings->where('category', 'system');
                @endphp
                
                @if($systemSettings->count() > 0)
                    @foreach($systemSettings as $setting)
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</h6>
                                    <p class="text-muted small mb-2">{{ $setting->description }}</p>
                                    <div class="setting-value">
                                        @if($setting->type == 'boolean')
                                            <span class="badge {{ $setting->value == 'true' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $setting->value == 'true' ? __('admin.enabled') : __('admin.disabled') }}
                                            </span>
                                        @else
                                            <code class="text-primary">{{ $setting->value }}</code>
                                        @endif
                                    </div>
                                </div>
                                <div class="setting-actions">
                                    {{--                                         {{-- <a href="{{ route('admin.settings.edit', $setting) }}" 
                                           class="btn btn-sm btn-modern-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a> --}} --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">{{ __('admin.no_system_settings_configured') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="modern-card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-lightning text-primary me-2"></i>{{ __('admin.quick_actions') }}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <button class="btn btn-modern-secondary w-100 py-3" onclick="toggleMaintenanceMode()">
                    <i class="bi bi-tools fs-4 d-block mb-2"></i>
                    <small>{{ __('admin.toggle_maintenance_mode') }}</small>
                </button>
            </div>
            <div class="col-md-3 mb-3">
                <button class="btn btn-modern-secondary w-100 py-3" onclick="resetToDefaults()">
                    <i class="bi bi-arrow-clockwise fs-4 d-block mb-2"></i>
                    <small>{{ __('admin.reset_to_defaults') }}</small>
                </button>
            </div>
            <div class="col-md-3 mb-3">
                <button class="btn btn-modern-secondary w-100 py-3" onclick="exportSettings()">
                    <i class="bi bi-download fs-4 d-block mb-2"></i>
                    <small>{{ __('admin.export_settings') }}</small>
                </button>
            </div>
            <div class="col-md-3 mb-3">
                <button class="btn btn-modern-secondary w-100 py-3" onclick="importSettings()">
                    <i class="bi bi-upload fs-4 d-block mb-2"></i>
                    <small>{{ __('admin.import_settings') }}</small>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- All Settings Table -->
<div class="modern-card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('admin.all_settings') }}</h5>
    </div>
    <div class="card-body">
        @if($settings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Setting</th>
                            <th>Value</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings as $setting)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                                        @if($setting->description)
                                            <br><small class="text-muted">{{ $setting->description }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 200px; word-wrap: break-word;">
                                        @if($setting->type == 'boolean')
                                            <span class="badge {{ $setting->value == 'true' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $setting->value == 'true' ? __('admin.enabled') : __('admin.disabled') }}
                                            </span>
                                        @elseif(strlen($setting->value) > 50)
                                            <code class="text-primary">{{ Str::limit($setting->value, 50) }}</code>
                                        @else
                                            <code class="text-primary">{{ $setting->value }}</code>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ ucfirst($setting->category ?? 'general') }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($setting->type ?? 'text') }}</span>
                                </td>
                                <td>
                                    <span title="{{ $setting->updated_at->format('M d, Y H:i:s') }}">
                                        {{ $setting->updated_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.settings.edit', $setting) }}" 
                                           class="btn btn-sm btn-modern-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.settings.destroy', $setting) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-modern-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-gear display-1 text-muted"></i>
                <h4 class="mt-3">No settings found</h4>
                <p class="text-muted">Start by creating your first setting.</p>
                <a href="{{ route('admin.settings.create') }}" class="btn btn-modern-primary">
                    <i class="bi bi-plus"></i> Add New Setting
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.setting-item {
    padding: 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 1rem;
    background: #f8f9fa;
    transition: all 0.2s ease;
}

.setting-item:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.setting-value {
    margin-top: 0.5rem;
}

.setting-actions {
    margin-left: 1rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

code {
    background-color: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
function toggleMaintenanceMode() {
    // Implementation for toggling maintenance mode
    alert('Maintenance mode toggle functionality will be implemented');
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to defaults? This action cannot be undone.')) {
        // Implementation for resetting to defaults
        alert('Reset to defaults functionality will be implemented');
    }
}

function exportSettings() {
    // Implementation for exporting settings
    alert('Export settings functionality will be implemented');
}

function importSettings() {
    // Implementation for importing settings
    alert('Import settings functionality will be implemented');
}
</script>
@endpush