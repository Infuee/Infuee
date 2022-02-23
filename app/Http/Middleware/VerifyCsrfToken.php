<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // '/admin/users',
        // 'transfer_complete',
        // 'hire-influencer',
        // 'create/campaign',
        // '*/create/job',
        // 'plan-setting',
        // 'custom-plan-setting/*'
    ];
}
