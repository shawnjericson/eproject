@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Site Settings</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.settings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> {{ __('admin.add_new') }} Setting
        </a>
    </div>
</div>

<!-- Settings Categories -->
<div class="row">
    <!-- General Settings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear"></i> General Settings
                </h5>
            </div>
            <div class="card-body">
                @php
                    $generalSettings = $settings->filter(function($setting) {
                        return in_array($setting->key, ['site_name', 'site_description', 'contact_email', 'contact_phone']);
                    });
                @endphp
                
                @if($generalSettings->count() > 0)
                    @foreach($generalSettings as $setting)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($setting->value, 50) }}</small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('admin.settings.edit', $setting) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No general settings configured.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- SEO Settings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-search"></i> SEO Settings
                </h5>
            </div>
            <div class="card-body">
                @php
                    $seoSettings = $settings->filter(function($setting) {
                        return in_array($setting->key, ['meta_title', 'meta_description', 'meta_keywords', 'analytics_code']);
                    });
                @endphp
                
                @if($seoSettings->count() > 0)
                    @foreach($seoSettings as $setting)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($setting->value, 50) }}</small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('admin.settings.edit', $setting) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No SEO settings configured.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Social Media Settings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-share"></i> Social Media
                </h5>
            </div>
            <div class="card-body">
                @php
                    $socialSettings = $settings->filter(function($setting) {
                        return in_array($setting->key, ['facebook_url', 'twitter_url', 'instagram_url', 'youtube_url']);
                    });
                @endphp
                
                @if($socialSettings->count() > 0)
                    @foreach($socialSettings as $setting)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($setting->value, 50) }}</small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('admin.settings.edit', $setting) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No social media settings configured.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- System Settings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-cpu"></i> System Settings
                </h5>
            </div>
            <div class="card-body">
                @php
                    $systemSettings = $settings->filter(function($setting) {
                        return in_array($setting->key, ['maintenance_mode', 'visitor_count', 'ticker_config']);
                    });
                @endphp
                
                @if($systemSettings->count() > 0)
                    @foreach($systemSettings as $setting)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                                <br>
                                <small class="text-muted">
                                    @if($setting->key == 'ticker_config')
                                        Ticker configuration (JSON)
                                    @elseif($setting->key == 'maintenance_mode')
                                        {{ $setting->value == 'true' ? 'Enabled' : 'Disabled' }}
                                    @else
                                        {{ Str::limit($setting->value, 50) }}
                                    @endif
                                </small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('admin.settings.edit', $setting) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No system settings configured.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- All Settings Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Settings</h5>
    </div>
    <div class="card-body">
        @if($settings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                            <th>Updated</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings as $setting)
                            <tr>
                                <td>
                                    <code>{{ $setting->key }}</code>
                                </td>
                                <td>
                                    <div style="max-width: 300px; word-wrap: break-word;">
                                        @if(strlen($setting->value) > 100)
                                            {{ Str::limit($setting->value, 100) }}
                                        @else
                                            {{ $setting->value }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span title="{{ $setting->updated_at->format('M d, Y H:i:s') }}">
                                        {{ $setting->updated_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.settings.edit', $setting) }}" 
                                           class="btn btn-sm btn-outline-primary" title="{{  __('admin.edit')  }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.settings.destroy', $setting) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{  __('admin.delete')  }}">
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
                <a href="{{ route('admin.settings.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> {{ __('admin.add_new') }} Setting
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.card-header {
    background-color: #f8f9fa;
}
code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
}
</style>
@endpush
