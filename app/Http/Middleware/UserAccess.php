<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    public function handle(Request $request, Closure $next, $userType): Response
    {
        $roles = [
            'staff' => 'applicant',
            'kasubag' => 'manager',
            'bendahara' => 'administrator',
            'supervisor' => 'supervisor',
        ];
        $role = $roles[Auth::user()->role];
        if ($role == $userType) {
            return $next($request);
        }

        return redirect()->route($role . '.dashboard')->withErrors(['warning' => 'Kamu tidak memiliki izin akses kesana!']);
    }
}
