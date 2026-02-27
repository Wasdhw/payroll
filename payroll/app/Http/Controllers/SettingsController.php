<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{

public function __construct()
{
    $this->middleware(function ($request, $next) {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only Super Admins can access this panel.');
        }
        return $next($request);
    });
}

public function index()
{
    $users = User::where('role', 'hr_admin')->get();
    
    return view('settings.index', compact('users'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'hr_admin', 
    ]);

    return back()->with('success', 'New HR Admin registered successfully!');
}

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('settings.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:8', 'confirmed'], 
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('settings.index')->with('success', 'User account updated!');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'User account deleted.');
    }
}