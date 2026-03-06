<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
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

public function sendVerificationCode(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        $code = rand(100000, 999999);

        session()->put('pending_hr_account', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'code' => $code,
        ]);

        Mail::raw("Your verification code is: {$code}", function ($message) use ($validated) {
            $message->to($validated['email'])
                    ->subject('HR Account Verification Code');
        });

        return redirect()->route('settings.verify.form')->with('success', 'Verification code sent to the email!');
    }

    public function showVerificationForm()
    {
        if (!session()->has('pending_hr_account')) {
            return redirect()->route('settings.index')->withErrors('No pending registration found.');
        }

        return view('settings.verify-code');
    }

    public function verifyAndCreate(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        $pendingUser = session('pending_hr_account');

        if (!$pendingUser || $request->code != $pendingUser['code']) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $temporaryPassword = Str::random(12);

        $user = User::create([
            'name' => $pendingUser['name'],
            'email' => $pendingUser['email'],
            'password' => Hash::make($temporaryPassword),
            'role' => 'hr_admin',
            'email_verified_at' => now(), 
        ]);

        Mail::to($user->email)->send(new \App\Mail\NewHrAccountMail($user->email, $temporaryPassword));

        session()->forget('pending_hr_account');

        return redirect()->route('settings.index')->with('success', 'HR Account verified and created successfully. Login details have been emailed.');
    }
}