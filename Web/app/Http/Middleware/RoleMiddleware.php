<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // ถ้า user ยังไม่ได้ login
        if (!Auth::guard('user')->check()) {
            return redirect('/login');
        }

        $user = Auth::guard('user')->user();

        // ถ้า role ของ user ไม่อยู่ใน roles ที่กำหนด
        if (!in_array($user->role, $roles)) {
            return redirect('/');
        }

        return $next($request);
    }
}
