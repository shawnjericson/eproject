<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Cloudinary\Cloudinary;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        
        return view('admin.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.show')
            ->with('success', __('admin.profile_updated_successfully'));
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            // Delete old avatar if exists and not default
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Upload to Cloudinary if configured
            if (config('cloudinary.cloud_name')) {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => config('cloudinary.cloud_name'),
                        'api_key' => config('cloudinary.api_key'),
                        'api_secret' => config('cloudinary.api_secret'),
                    ],
                ]);

                $uploadedFile = $cloudinary->uploadApi()->upload(
                    $request->file('avatar')->getRealPath(),
                    [
                        'folder' => 'global-heritage/avatars',
                        'transformation' => [
                            'width' => 400,
                            'height' => 400,
                            'crop' => 'fill',
                            'gravity' => 'face',
                        ],
                    ]
                );

                $avatarUrl = $uploadedFile['secure_url'];
            } else {
                // Store locally if Cloudinary not configured
                $path = $request->file('avatar')->store('avatars', 'public');
                $avatarUrl = $path;
            }

            $user->update(['avatar' => $avatarUrl]);

            return redirect()->back()
                ->with('success', __('admin.avatar_updated_successfully'));
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('admin.avatar_upload_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => __('admin.current_password_incorrect')]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()
            ->with('success', __('admin.password_updated_successfully'));
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();
        
        if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return redirect()->back()
            ->with('success', __('admin.avatar_deleted_successfully'));
    }
}

