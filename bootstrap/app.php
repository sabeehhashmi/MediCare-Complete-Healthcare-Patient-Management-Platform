<?php

use App\Http\Middleware\Hospital;
use App\Http\Middleware\Clinic;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/front.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {


        // ------- Global Middleware ------- //

        $middleware->append([
            PreventRequestsDuringMaintenance::class,
            ValidatePostSize::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,

        ]);
        // --------------------------------- //
       
        
        // ------ Admin Middleware ------ //
        $middleware->alias([
            // Admin Middleware
            'admin' => IsAdmin::class,
            // Vendor Middleware
            'vendor' => \App\Http\Middleware\Vendor::class,
            'doctor' => \App\Http\Middleware\Doctor::class,
            'callcenter' => \App\Http\Middleware\CallCenter::class,
            'agent' => \App\Http\Middleware\Agent::class,
            'hospital' => Hospital::class,
            'clinic' => Clinic::class,
            'guest' => RedirectIfAuthenticated::class, 
            // Vendor Verified Middleware
            'is_vendor_verified' => \App\Http\Middleware\Vendor::class,
            //'is_doctor_verified' => \App\Http\Middleware\IsDoctorVerified::class,
        ]);


     
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
