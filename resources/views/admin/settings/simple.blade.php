@extends('layouts.admin')

@section('title', __('admin.simple_settings'))

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-gear text-primary me-2"></i>{{ __('admin.simple_settings') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.easy_settings_management') }}</p>
        </div>
    </div>
</div>

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
                <form action="{{ route('admin.settings.update-simple') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.maximum_image_size_mb') }}</label>
                        <input type="number" name="max_image_size_mb" class="form-control" 
                               value="{{ \App\Models\SiteSetting::get('max_image_size_mb', 5) }}" 
                               min="1" max="50">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.maximum_files_per_post') }}</label>
                        <input type="number" name="max_files_per_post" class="form-control" 
                               value="{{ \App\Models\SiteSetting::get('max_files_per_post', 10) }}" 
                               min="1" max="50">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.maximum_files_per_monument') }}</label>
                        <input type="number" name="max_files_per_monument" class="form-control" 
                               value="{{ \App\Models\SiteSetting::get('max_files_per_monument', 15) }}" 
                               min="1" max="100">
                    </div>
                    
                    <button type="submit" class="btn btn-modern-primary">{{ __('admin.save_file_settings') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Moderator Permissions -->
    <div class="col-lg-6 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people text-primary me-2"></i>{{ __('admin.moderator_permissions') }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update-simple') }}" method="POST">
                    @csrf
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="moderator_can_manage_users" 
                               value="true" {{ \App\Models\SiteSetting::get('moderator_can_manage_users', 'false') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.moderators_can_manage_users') }}</label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="moderator_can_approve_posts" 
                               value="true" {{ \App\Models\SiteSetting::get('moderator_can_approve_posts', 'true') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.moderators_can_approve_posts') }}</label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="moderator_can_approve_monuments" 
                               value="true" {{ \App\Models\SiteSetting::get('moderator_can_approve_monuments', 'true') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.moderators_can_approve_monuments') }}</label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="moderator_can_view_analytics" 
                               value="true" {{ \App\Models\SiteSetting::get('moderator_can_view_analytics', 'false') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.moderators_can_view_analytics') }}</label>
                    </div>
                    
                    <button type="submit" class="btn btn-modern-primary">{{ __('admin.save_permission_settings') }}</button>
                </form>
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
                <form action="{{ route('admin.settings.update-simple') }}" method="POST">
                    @csrf
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="post_approval_required" 
                               value="true" {{ \App\Models\SiteSetting::get('post_approval_required', 'true') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.posts_require_approval') }}</label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="monument_approval_required" 
                               value="true" {{ \App\Models\SiteSetting::get('monument_approval_required', 'true') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.monuments_require_approval') }}</label>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.maximum_post_content_length') }}</label>
                        <input type="number" name="max_post_content_length" class="form-control" 
                               value="{{ \App\Models\SiteSetting::get('max_post_content_length', 5000) }}" 
                               min="100" max="50000">
                    </div>
                    
                    <button type="submit" class="btn btn-modern-primary">{{ __('admin.save_content_settings') }}</button>
                </form>
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
                <form action="{{ route('admin.settings.update-simple') }}" method="POST">
                    @csrf
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="maintenance_mode" 
                               value="true" {{ \App\Models\SiteSetting::get('maintenance_mode', 'false') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.maintenance_mode') }}</label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="user_registration_enabled" 
                               value="1" {{ \App\Models\SiteSetting::get('user_registration_enabled', 'true') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.user_registration_enabled') }}</label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="auto_approve_registrations" 
                               value="true" {{ \App\Models\SiteSetting::get('auto_approve_registrations', 'false') == 'true' ? 'checked' : '' }}>
                        <label class="form-check-label">{{ __('admin.auto_approve_registrations') }}</label>
                    </div>
                    
                    <button type="submit" class="btn btn-modern-primary">{{ __('admin.save_system_settings') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Current Settings Display -->
<div class="modern-card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('admin.current_settings_values') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>{{ __('admin.file_upload') }}:</h6>
                <ul class="list-unstyled">
                    <li>{{ __('admin.max_image_size') }}: <strong>{{ \App\Models\SiteSetting::get('max_image_size_mb', 5) }}MB</strong></li>
                    <li>{{ __('admin.max_files_per_post') }}: <strong>{{ \App\Models\SiteSetting::get('max_files_per_post', 10) }}</strong></li>
                    <li>{{ __('admin.max_files_per_monument') }}: <strong>{{ \App\Models\SiteSetting::get('max_files_per_monument', 15) }}</strong></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>{{ __('admin.permissions') }}:</h6>
                <ul class="list-unstyled">
                    <li>{{ __('admin.can_manage_users') }}: <strong>{{ \App\Models\SiteSetting::get('moderator_can_manage_users', 'false') == 'true' ? __('admin.yes') : __('admin.no') }}</strong></li>
                    <li>{{ __('admin.can_approve_posts') }}: <strong>{{ \App\Models\SiteSetting::get('moderator_can_approve_posts', 'true') == 'true' ? __('admin.yes') : __('admin.no') }}</strong></li>
                    <li>{{ __('admin.can_approve_monuments') }}: <strong>{{ \App\Models\SiteSetting::get('moderator_can_approve_monuments', 'true') == 'true' ? __('admin.yes') : __('admin.no') }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
