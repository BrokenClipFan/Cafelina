<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $pendingUsers = User::where('status', User::STATUS_PENDING)
            ->orderBy('created_at', 'asc')
            ->get();

        $query = User::where('status', '!=', User::STATUS_PENDING);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->orderByRaw("CASE WHEN status = 'approved' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        $roles = User::ROLES;

        return view('admin.users.index', compact('pendingUsers', 'users', 'roles'));
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ]);

        $user->update([
            'role' => $validated['role'],
            'status' => User::STATUS_APPROVED,
        ]);

        return back()->with('success', "{$user->name} was approved as {$user->role_label}.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot reject your own account.');
        }

        $user->update([
            'status' => User::STATUS_REJECTED,
            'online_status' => false,
        ]);

        return back()->with('success', "{$user->name}'s account was rejected.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ]);

        if ($user->id === $request->user()->id && $validated['role'] !== User::ROLE_ADMIN) {
            return back()->with('error', 'You cannot remove your own admin role.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "{$user->name} is now {$user->role_label}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "{$name}'s account was deleted.");
    }
}
