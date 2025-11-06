<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    // Disable CSRF untuk semua route (development only - tidak aman untuk production)
    protected $except = [
        '*',
    ];
}

