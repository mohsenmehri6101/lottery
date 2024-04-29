<?php

namespace Modules\Authentication\Http\Middleware;

use App\Exceptions\Contracts\ApiKeyDeniedException;
use Closure;
use Illuminate\Http\Request;
use Modules\Authentication\Services\AuthenticationService;

class checkApiKeyMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ApiKeyDeniedException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        # get api-key
        $api_key = request()->header('api-key');
        if ($request->user() && ($request->user()->hasRole('admin') || $request->user()->hasRole('super_admin'))) {
            return $next($request);
        }

        if (isset($api_key) && filled($api_key) && AuthenticationService::bool_check_api_key($api_key)) {
            return $next($request);
        }
        return throw new ApiKeyDeniedException();
    }
}
