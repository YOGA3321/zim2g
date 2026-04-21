<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $users = \App\Models\User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'role' => 'required|in:admin,user',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
            'role' => $request->role,
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(\App\Models\User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, \App\Models\User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(\App\Models\User $user)
    {

        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
