<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
    
        if ($user) {
            $routeName = $request->route()->getName();
    
            if ($user->usereable_type == 'App\Models\Administrateur' && $routeName !== 'dashboard_admin') {
                return redirect()->route('dashboard_admin');
            }
    
            if ($user->usereable_type == 'App\Models\Professeur' && $routeName !== 'dashboard_professeur') {
                return redirect()->route('dashboard_professeur');
            }
    
            if ($user->usereable_type == 'App\Models\Eleve' && $routeName !== 'dashboard_eleve') {
                return redirect()->route('dashboard_eleve');
            }
        }
    
        return $next($request); // Laisse la requête continuer
    }
}
