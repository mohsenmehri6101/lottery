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
        $apiKey = request()->header('api-key');
        // todo should be true delete in condition always return
        if ($request->user() && $request->user()->hasRole('admin') || true) {
            return $next($request);
        }

        if (isset($apiKey) && filled($apiKey) && AuthenticationService::bool_check_api_key($apiKey)) {
            return $next($request);
        }
        return throw new ApiKeyDeniedException();
    }

}
