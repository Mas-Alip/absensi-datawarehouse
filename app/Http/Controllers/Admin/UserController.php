<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Enums\UserRole;

class UserController extends Controller
{
    public function create()
    {
        // Show the registration form but allow role selection
        return view('auth.register', ['showRole' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,manager'],
        ]);

        // Only admins can create admin users (middleware ensures caller is admin)
        $role = $request->role === 'admin' ? UserRole::ADMIN : UserRole::MANAGER;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'User berhasil dibuat.');
    }
}
