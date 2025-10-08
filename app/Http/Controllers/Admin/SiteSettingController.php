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

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(SettingStoreRequest $request)
    {
        SiteSetting::create($request->only(['key', 'value']));

        return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully!');
    }

    public function edit(SiteSetting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(SettingUpdateRequest $request, SiteSetting $setting)
    {
        $setting->update($request->only(['key', 'value']));

        return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully!');
    }

    public function destroy(SiteSetting $setting)
    {
        $setting->delete();

        return redirect()->route('admin.settings.index')->with('success', 'Setting deleted successfully!');
    }
}
