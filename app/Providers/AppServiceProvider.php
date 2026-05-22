<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
          if (request()->is('admin/chat/*') || request()->is('patient/chat/*') || request()->is('*chat*')) {
                set_time_limit(120);
                ini_set('memory_limit', '512M');
            }

        Response::macro('success', function (string $message, $object = null) {
            return response()->json([
                "status"   => "1",
                "message" => $message,
                "oData"  => $object,
                "errors" => null
            ], 200);
        });

        Response::macro('error', function (string $message, $e = null) {
            return response()->json([
                "status"   => '0',
                "message" => $message,
                "oData" => null,
                "errors"  => $e
            ], 200);
        });

    }
}
