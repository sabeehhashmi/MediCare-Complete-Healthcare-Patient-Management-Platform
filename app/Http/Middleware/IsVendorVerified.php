<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsVendorVerified
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
        //if (Auth::user()->user_type_id == 3 && Auth::user()->verified == '0') {
        if (Auth::user()->profile_once_updated == 0) {
            return redirect()->route('vendor.edit_profile')->with('unverified_error', 'Please complete your profile to continue');
        } else {
            if (Auth::user()->verified == '1') {
                return $next($request);
            } else {
                return redirect()->route('vendor.wait_for_verification')->with('unverified_error', 'Please wait for admin to verify your account');
            }
        }
    }
}
