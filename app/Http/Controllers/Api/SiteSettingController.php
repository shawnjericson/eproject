<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    public function show($key)
    {
        $setting = SiteSetting::where('key', $key)->first();
        
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        return response()->json($setting);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:site_settings',
            'value' => 'required|string',
        ]);

        $setting = SiteSetting::create($request->all());

        return response()->json($setting, 201);
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'value' => 'required|string',
        ]);

        $setting = SiteSetting::where('key', $key)->first();

        if (!$setting) {
            // Create if doesn't exist
            $setting = SiteSetting::create([
                'key' => $key,
                'value' => $request->value
            ]);
        } else {
            $setting->update(['value' => $request->value]);
        }

        return response()->json($setting);
    }

    public function destroy($key)
    {
        $setting = SiteSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        $setting->delete();

        return response()->json(['message' => 'Setting deleted successfully']);
    }
}
