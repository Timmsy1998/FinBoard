<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->direction === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('name');
        }

        return Inertia::render('Admin/Users', [
            'users' => $query->paginate(10)->withQueryString(),
            'search' => $request->search,
            'role' => $request->role,
            'sort' => $request->sort,
            'direction' => $request->direction,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:user,admin',
        ]);

        if ($request->user()->role === 'admin' && $request->role !== 'user') {
            abort(403, 'Admins may only create users.');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');

    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|confirmed|min:8',
            'role' => 'required|in:user,admin',
        ]);

        if ($request->user()->role === 'admin' && $user->role !== 'user') {
            abort(403, 'Admins may only update users.');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if (
            ($request->user()->role === 'admin' && $user->role !== 'user') ||
            ($request->user()->role === 'superuser' && $user->role === 'superuser')
        ) {
            abort(403, 'You are not authorized to delete this user.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user) {
            if (
                ($request->user()->role === 'admin' && $user->role !== 'user') ||
                ($request->user()->role === 'superuser' && $user->role === 'superuser')
            ) {
                continue; // skip unauthorized deletes
            }
            $user->delete();
        }

        return back()->with('success', 'Selected users deleted.');
    }

}
