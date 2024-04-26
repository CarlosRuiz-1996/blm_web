<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class ClearMenuAndPermissionCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (!Auth::check()) { // Eliminar la caché de los menús y los permisos
            cache()->forget('spatie.permission.cache');
            cache()->forget('cached_menus');
        }
        return $response;
    }
}
