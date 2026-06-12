<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            auth()->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Anda tidak memiliki akses admin.');
        }

        return $next($request);
    }
}
