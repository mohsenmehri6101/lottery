<?php

namespace Modules\Authorization\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use function user_have_permission;

class CheckPermissions
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permissions = null): mixed
    {
        if ($permissions != null){

            # separate permissions
            $permissions = explode("|",$permissions);

            foreach ($permissions as $permission) {
                try {
                    if (!user_have_permission($permission)){
                        throw new AccessDeniedHttpException(trans('exceptions.exceptionErrors.accessDenied','access forbidden'));
                    }
                }catch (Exception $exception){
                    throw new AccessDeniedHttpException(trans('exceptions.exceptionErrors.accessDenied','access forbidden'));
                }
            }
            return $next($request);
        }

        return throw new AccessDeniedHttpException(trans('exceptions.exceptionErrors.accessDenied','access forbidden'));
    }
}
