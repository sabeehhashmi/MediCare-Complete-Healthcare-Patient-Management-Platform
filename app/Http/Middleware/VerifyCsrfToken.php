<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'admin/Excel/*',
        'Excel/upload_file',
        'update_location',
        '/update_location',
        '/web_rtc_hook',
         'admin/chat/upload',
        'admin/chat/*',
    ];
}
