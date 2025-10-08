<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request - Step 1: Find user by email
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user has security questions set up
        if (!$user->security_question_1 || !$user->security_answer_1) {
            return redirect()->back()
                ->withErrors(['email' => 'Tài khoản này chưa thiết lập câu hỏi bảo mật. Vui lòng liên hệ admin để được hỗ trợ.']);
        }

        // Store user ID in session for next step
        session(['reset_user_id' => $user->id]);

        return redirect()->route('password.security-questions');
    }

    /**
     * Show security questions form - Step 2
     */
    public function showSecurityQuestions()
    {
        $userId = session('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Phiên làm việc đã hết hạn. Vui lòng thử lại.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Không tìm thấy người dùng.']);
        }

        return view('auth.security-questions', compact('user'));
    }

    /**
     * Verify security questions - Step 3
     */
    public function verifySecurityQuestions(Request $request)
    {
        $userId = session('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Phiên làm việc đã hết hạn. Vui lòng thử lại.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Không tìm thấy người dùng.']);
        }

        $request->validate([
            'answer_1' => 'required|string',
            'answer_2' => 'nullable|string',
            'answer_3' => 'nullable|string',
        ], [
            'answer_1.required' => 'Câu trả lời đầu tiên là bắt buộc.',
        ]);

        // Check answers (case-insensitive)
        $answer1Match = strtolower(trim($request->answer_1)) === strtolower(trim($user->security_answer_1));
        $answer2Match = !$user->security_answer_2 || strtolower(trim($request->answer_2 ?? '')) === strtolower(trim($user->security_answer_2));
        $answer3Match = !$user->security_answer_3 || strtolower(trim($request->answer_3 ?? '')) === strtolower(trim($user->security_answer_3));

        if (!$answer1Match || !$answer2Match || !$answer3Match) {
            return redirect()->back()
                ->withErrors(['answers' => 'Một hoặc nhiều câu trả lời không chính xác. Vui lòng thử lại.'])
                ->withInput();
        }

        // Answers are correct, proceed to reset password
        session(['reset_verified' => true]);
        return redirect()->route('password.reset');
    }

    /**
     * Show reset password form - Step 4
     */
    public function showResetPasswordForm()
    {
        $userId = session('reset_user_id');
        $verified = session('reset_verified');

        if (!$userId || !$verified) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Phiên làm việc đã hết hạn hoặc chưa được xác thực. Vui lòng thử lại.']);
        }

        return view('auth.reset-password');
    }

    /**
     * Reset password - Final step
     */
    public function resetPassword(Request $request)
    {
        $userId = session('reset_user_id');
        $verified = session('reset_verified');

        if (!$userId || !$verified) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Phiên làm việc đã hết hạn hoặc chưa được xác thực. Vui lòng thử lại.']);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Không tìm thấy người dùng.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear session
        session()->forget(['reset_user_id', 'reset_verified']);

        return redirect()->route('login')
            ->with('success', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập với mật khẩu mới.');
    }
}
