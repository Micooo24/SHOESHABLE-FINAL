<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     public function handle($request, Closure $next, ...$roles)
     {
         if (!Auth::check()) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }

         $userRole = Auth::user()->role;

         if (!in_array($userRole, $roles)) {
             return response()->json(['error' => 'Forbidden'], 403);
         }

         return $next($request);
     }
}
