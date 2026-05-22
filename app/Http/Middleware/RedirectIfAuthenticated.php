<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            $dashboards = [
                CLINIC_ROLE      => '/clinic/dashboard',
                HOSPITAL_ROLE    => '/hospital/dashboard',
                CALL_CENTER_ROLE => '/callcenter/dashboard',
                AGENT_ROLE       => '/agent/dashboard',
                ADMIN_ROLE       => '/admin/dashboard',
                USER_ROLE        => '/useraccount-bookings',
                DOCTOR_ROLE      => '/doctor/dashboard',
                STAFF_ROLE       => '/staff/dashboard',
            ];

            if (isset($dashboards[$user->role])) {
                return redirect($dashboards[$user->role]);
            }

            return redirect('/'); 
        }
        
        return $next($request);
    }
}
