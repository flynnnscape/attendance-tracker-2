<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
        'users' => User::with('roles:id,name')
            ->select('id', 'name', 'email', 'status')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status,
                    'roles' => $user->getRoleNames()->first() ?? 'No role'
                ];
            }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create', [
            'roles' => Role::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name'     => 'required',
        'email'    => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
        'roles' => 'required|array|min:1',
    ]);

    $validated['status'] = 'active';    

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'status' => 'active',
        'role' => $request->role,
    ]);

    $user->syncRoles([$validated['roles']]);
    return redirect()->route('users.index');
}

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->map(function($r) {
            return ['id' => $r->id, 'name' => $r->name];
        }),
    ],
    'roles' => \Spatie\Permission\Models\Role::select('id', 'name')->get(),
]);
    }

    public function update(Request $request, User $user)
{
    $request->validate([
        'name'  => ['required'],
        'email' => ['required', 'email'],
        'role'  => ['required'],
    ]);

    // Update basic fields
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    // Sync role using Spatie Permission
    $user->syncRoles([$request->role]);
    $user->assignRole($request->role);
    $user->update([
        'role' => $request->role
    ]);

    return redirect()->route('users.index')->with('success', 'User updated successfully');
}

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
