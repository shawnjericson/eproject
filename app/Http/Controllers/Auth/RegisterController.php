<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        // Do NOT auto-login. Keep user inactive until admin approval
        // Notify user with a friendly message
        $userNotice = __('Your account has been created and is pending admin approval. You will be able to sign in once approved.');

        // Create an admin contact message to alert administrators
        try {
            Contact::create([
                'name' => $user->name,
                'email' => $user->email,
                'subject' => 'New registration pending approval',
                'message' => "A new user has registered and is awaiting approval.\n\nName: {$user->name}\nEmail: {$user->email}",
                'status' => 'new',
            ]);
        } catch (\Exception $e) {
            // Silently ignore contact creation failure to not block registration
        }

        return redirect()->route('login')->with('success', $userNotice);
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => __('admin.name_required'),
            'email.required' => __('admin.email_required'),
            'email.email' => __('admin.email_invalid'),
            'email.unique' => __('admin.email_taken'),
            'password.required' => __('admin.password_required'),
            'password.min' => __('admin.password_min_length'),
            'password.confirmed' => __('admin.password_confirmation_mismatch'),
            'terms.required' => __('admin.terms_required'),
            'terms.accepted' => __('admin.terms_must_be_accepted'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'moderator',
            'status' => 'inactive',
        ]);
    }
}
