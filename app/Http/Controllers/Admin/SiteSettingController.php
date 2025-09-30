<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:site_settings',
            'value' => 'required|string',
        ]);

        SiteSetting::create($request->only(['key', 'value']));

        return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully!');
    }

    public function edit(SiteSetting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, SiteSetting $setting)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:site_settings,key,' . $setting->id,
            'value' => 'required|string',
        ]);

        $setting->update($request->only(['key', 'value']));

        return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully!');
    }

    public function destroy(SiteSetting $setting)
    {
        $setting->delete();

        return redirect()->route('admin.settings.index')->with('success', 'Setting deleted successfully!');
    }
}
