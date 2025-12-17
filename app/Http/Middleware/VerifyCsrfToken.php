<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        // Stripe webhook: include several patterns to account for possible
        // deploy prefixes, subfolders or `api` prefixes that the webserver
        // might attach to the incoming request URI.
        'stripe/webhook',      // typical web route
        '/stripe/webhook',     // with leading slash
        'stripe/*',            // any stripe sub-paths
        'api/stripe/webhook',  // if placed under api routes or with api prefix
        '*/stripe/webhook',    // wildcard prefix (e.g. releases/current/stripe/webhook)
        '*/api/stripe/webhook',
    ];
}
