<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiDocumentationAuthCheck
{
    public function handle(Request $request, Closure $next)
    {
        $current_url = request()->url() ?? null;
        if (!str_contains($current_url, 'api/documentation') || str_contains($current_url, 'api/documentation/auth')) {
            return $next($request);
        }

        $user_logged_in = session('user_logged_in', false);
        if ($user_logged_in) {
            return $next($request);
        }
        return response()->redirectToRoute("api_documentation_auth");
    }
}
