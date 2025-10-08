<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // If already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Field-level validation (shows specific messages on the form)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember');

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();

                // Check if user is active
                if (Auth::user()->status !== 'active') {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => ['Your account has been deactivated.'],
                    ]);
                }

                return redirect()->intended(route('admin.dashboard'));
            }

            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            return redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors(['email' => 'Session expired. Please try again.']);
        } catch (ValidationException $e) {
            // Re-throw validation so the user sees field-specific errors
            throw $e;
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors(['system' => 'A system error occurred. Please try again later.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
