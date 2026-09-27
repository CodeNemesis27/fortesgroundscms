<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $roleRedirects = [
            'Admin' => '/admin',
            'Employee' => '/employee',
            'Client' => '/',
        ];

        $userRole = Auth::user()->role;

        if (isset($roleRedirects[$userRole]) && $userRole !== $role) {
            return redirect($roleRedirects[$userRole]);
        }

        return $next($request);
    }
}
