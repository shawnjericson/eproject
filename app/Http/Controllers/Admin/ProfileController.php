<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\ProfilePasswordUpdateRequest;
use App\Http\Requests\Admin\ProfileSecurityQuestionsUpdateRequest;
use App\Http\Requests\Admin\ProfileAvatarUpdateRequest;
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
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        $user->update($request->validated());

        return redirect()->route('admin.profile.show')
            ->with('success', __('admin.profile_updated_successfully'));
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(ProfileAvatarUpdateRequest $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        

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
    public function updatePassword(ProfilePasswordUpdateRequest $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        

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
     * Show security questions setup form.
     */
    public function showSecurityQuestions()
    {
        $user = Auth::user();
        return view('admin.profile.security-questions', compact('user'));
    }

    /**
     * Update security questions.
     */
    public function updateSecurityQuestions(ProfileSecurityQuestionsUpdateRequest $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        

        // Validate that if question is provided, answer must be provided too
        if ($request->security_question_2 && !$request->security_answer_2) {
            return redirect()->back()
                ->withErrors(['security_answer_2' => 'Vui lòng cung cấp câu trả lời cho câu hỏi thứ 2.'])
                ->withInput();
        }

        if ($request->security_question_3 && !$request->security_answer_3) {
            return redirect()->back()
                ->withErrors(['security_answer_3' => 'Vui lòng cung cấp câu trả lời cho câu hỏi thứ 3.'])
                ->withInput();
        }

        $user->update([
            'security_question_1' => $request->security_question_1,
            'security_answer_1' => strtolower(trim($request->security_answer_1)),
            'security_question_2' => $request->security_question_2,
            'security_answer_2' => $request->security_answer_2 ? strtolower(trim($request->security_answer_2)) : null,
            'security_question_3' => $request->security_question_3,
            'security_answer_3' => $request->security_answer_3 ? strtolower(trim($request->security_answer_3)) : null,
        ]);

        return redirect()->back()
            ->with('success', 'Câu hỏi bảo mật đã được cập nhật thành công.');
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return redirect()->back()
            ->with('success', __('admin.avatar_deleted_successfully'));
    }
}

