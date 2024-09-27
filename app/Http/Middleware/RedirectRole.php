<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectRole
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $auth = Auth::user();
            $user = User::find($auth->id);

            // dd($user->hasRole('OPERADOR'));
            
            // Verificamos si el usuario tiene el rol
            if ($user->hasRole('OPERADOR')) {
                return redirect('/operadores');
            }
        }
        return $next($request);
    }
}
