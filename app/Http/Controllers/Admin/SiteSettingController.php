<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingStoreRequest;
use App\Http\Requests\Admin\SettingUpdateRequest;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('key')->get();
        return view('admin.settings.index', compact('settings'));
    }

    // public function create()
    // {
    //     return view('admin.settings.create');
    // }

    // public function store(SettingStoreRequest $request)
    // {
    //     SiteSetting::create($request->only(['key', 'value']));

    //     return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully!');
    // }

    // public function edit(SiteSetting $setting)
    // {
    //     return view('admin.settings.edit', compact('setting'));
    // }

    // public function update(SettingUpdateRequest $request, SiteSetting $setting)
    // {
    //     $setting->update($request->only(['key', 'value']));

    //     return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully!');
    // }

    // public function destroy(SiteSetting $setting)
    // {
    //     $setting->delete();

    //     return redirect()->route('admin.settings.index')->with('success', 'Setting deleted successfully!');
    // }

    public function simple()
    {
        return view('admin.settings.simple');
    }

    public function updateSimple(Request $request)
    {
        // Only update settings that are present in the request
        $settingsToUpdate = [];
        
        // File upload settings
        if ($request->has('max_image_size_mb')) {
            $settingsToUpdate['max_image_size_mb'] = $request->input('max_image_size_mb', 5);
        }
        if ($request->has('max_files_per_post')) {
            $settingsToUpdate['max_files_per_post'] = $request->input('max_files_per_post', 10);
        }
        if ($request->has('max_files_per_monument')) {
            $settingsToUpdate['max_files_per_monument'] = $request->input('max_files_per_monument', 15);
        }
        
        // Moderator permissions
        if ($request->has('moderator_can_manage_users')) {
            $settingsToUpdate['moderator_can_manage_users'] = $request->boolean('moderator_can_manage_users');
        }
        if ($request->has('moderator_can_approve_posts')) {
            $settingsToUpdate['moderator_can_approve_posts'] = $request->boolean('moderator_can_approve_posts');
        }
        if ($request->has('moderator_can_approve_monuments')) {
            $settingsToUpdate['moderator_can_approve_monuments'] = $request->boolean('moderator_can_approve_monuments');
        }
        if ($request->has('moderator_can_view_analytics')) {
            $settingsToUpdate['moderator_can_view_analytics'] = $request->boolean('moderator_can_view_analytics');
        }
        
        // Content settings
        if ($request->has('post_approval_required')) {
            $settingsToUpdate['post_approval_required'] = $request->boolean('post_approval_required');
        }
        if ($request->has('monument_approval_required')) {
            $settingsToUpdate['monument_approval_required'] = $request->boolean('monument_approval_required');
        }
        if ($request->has('max_post_content_length')) {
            $settingsToUpdate['max_post_content_length'] = $request->input('max_post_content_length', 5000);
        }
        
        // System settings
        if ($request->has('maintenance_mode')) {
            $settingsToUpdate['maintenance_mode'] = $request->boolean('maintenance_mode');
        }
        if ($request->has('user_registration_enabled')) {
            $settingsToUpdate['user_registration_enabled'] = $request->boolean('user_registration_enabled');
        }
        if ($request->has('auto_approve_registrations')) {
            $settingsToUpdate['auto_approve_registrations'] = $request->boolean('auto_approve_registrations');
        }

        // Update only the settings that were submitted
        foreach ($settingsToUpdate as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // Clear cache
        \App\Services\SettingsService::clearCache();

        return redirect()->route('admin.settings.simple')->with('success', 'Settings updated successfully!');
    }
}
