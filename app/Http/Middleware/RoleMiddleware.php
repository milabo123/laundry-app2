<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userLevelId = Auth::user()->id_level;

        // Map level names to IDs for easier access
        $roleMap = [
            'admin'    => 1,
            'operator' => 2,
            'pimpinan' => 3,
        ];

        $allowedLevels = [];
        foreach ($roles as $role) {
            if (isset($roleMap[$role])) {
                $allowedLevels[] = $roleMap[$role];
            }
        }

        if (in_array($userLevelId, $allowedLevels)) {
            return $next($request);
        }

        return abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
    }
}
