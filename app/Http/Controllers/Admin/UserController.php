<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Check if moderators can manage users
        $this->middleware(function ($request, $next) {
            if (auth()->user()->isModerator() && !\App\Services\SettingsService::canModeratorManageUsers()) {
                abort(403, 'Moderators are not allowed to manage users.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = User::with(['posts', 'monuments']);

        // Filter by role - only apply if role is provided
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status - only apply if status is provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search - works independently of filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate stats
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'moderators' => User::where('role', 'moderator')->count(),
            'active' => User::where('status', 'active')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserStoreRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load(['posts', 'monuments']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->only(['name', 'email']);
        
        // Trim email to remove spaces
        if (isset($data['email'])) {
            $data['email'] = trim($data['email']);
        }
        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }

        // Chỉ thêm role nếu có trong request và user có quyền
        if ($request->has('role') && !auth()->user()->isModerator() && $user->id !== auth()->id()) {
            $data['role'] = $request->role;
        }

        // Chỉ cho phép đổi status nếu không phải đang edit chính mình
        if ($user->id !== auth()->id()) {
            $data['status'] = $request->status;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(Request $request, User $user)
    {
        // Prevent deleting the current user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account!');
        }

        // Get transfer user ID from request, default to first admin
        $transferUserId = $request->input('transfer_to');
        if (!$transferUserId) {
            $admin = User::where('role', 'admin')->where('id', '!=', $user->id)->first();
            $transferUserId = $admin ? $admin->id : null;
        }

        // Transfer posts and monuments to the specified user
        if ($transferUserId) {
            \App\Models\Post::where('created_by', $user->id)->update(['created_by' => $transferUserId]);
            \App\Models\Monument::where('created_by', $user->id)->update(['created_by' => $transferUserId]);
        } else {
            // If no admin found, set to null (orphaned content)
            \App\Models\Post::where('created_by', $user->id)->update(['created_by' => null]);
            \App\Models\Monument::where('created_by', $user->id)->update(['created_by' => null]);
        }

        $user->delete();

        $message = $transferUserId
            ? "User deleted successfully! All posts and monuments transferred to another admin."
            : "User deleted successfully! Posts and monuments are now orphaned.";

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
