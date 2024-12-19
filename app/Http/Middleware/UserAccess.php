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
        $role = Auth::user()->role;
        if ($role == $userType) {
            return $next($request);
        }

        return redirect()->route($role . '.dashboard')->with(['error' => 'Kamu tidak memiliki izin untuk mengakses halaman ini.']);
    }
}
