<?php

namespace Modules\Authorization\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TransferTokenFromPathToHeaderMiddleware
{
    public function transfer_token_from_body_to_header()
    {
        if (request()->has('token')) {
            $token = request()->get('token');
            $bearer_token = "Bearer $token";
            request()->headers->set('Authorization', $bearer_token);
        }
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permissions = null): mixed
    {
        $this->transfer_token_from_body_to_header();
        return $next($request);
    }
}
