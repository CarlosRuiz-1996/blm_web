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

        //redirecciona segun el rol a su vista pricnipal
        if (Auth::check()) {
            $auth = Auth::user();
            $user = User::find($auth->id);

            
            // Verificamos si el usuario tiene el rol
            if ($user->hasRole('OPERADOR/CAJERO')) {
                return redirect('/operadores');
            }

            if ($user->hasRole('Seguridad')) {
                return redirect('/seguridad');
            }
            if ($user->hasRole('Ventas')) {
                return redirect('/ventas');
            }
            
            if ($user->hasRole('Bancos')) {
                return redirect('/bancos');
            }
            if ($user->hasRole('Operaciones')) {
                return redirect('/operaciones');
            }
            if ($user->hasRole('Boveda')) {
                return redirect('/boveda/inicio');
            }
            
            if ($user->hasRole('RH')) {
                return redirect('/rh');
            }
            if ($user->hasRole('Juridico')) {
                return redirect('/juridico');
            }
            
            if ($user->hasRole('Cumplimiento')) {
                return redirect('/cumplimiento');
            }
        }
        return $next($request);
    }
}
