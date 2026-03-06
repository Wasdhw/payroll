<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class CheckVerifiedUnlessSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // 1. If no user is logged in, let the 'auth' middleware handle it
        if (!$user) {
            return $next($request);
        }

        // 2. EXCEPT: If the user is a super_admin, let them pass regardless of verification
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // 3. For everyone else (HR Admins), check if they are verified
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::guest(route('verification.notice'));
        }

        return $next($request);
    }
}