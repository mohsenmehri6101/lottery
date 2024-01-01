<?php

namespace App\Http\Controllers;

use App\Exceptions\Contracts\ForbiddenCustomException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
class ApiDocumentationAuth extends Controller
{
    /**
     * @throws ForbiddenCustomException
     */
    public function __construct()
    {
        $app_env = env('APP_ENV', 'production');
        $allow_api_documentation_on_production = config('configs.authentication.allow_api_documentation_on_production');
        if ($app_env === 'production' && !$allow_api_documentation_on_production) {
            throw new ForbiddenCustomException(code:Response::HTTP_FORBIDDEN);
        }
    }

    public function show(Request $request): View
    {
        return view("vendor.l5-swagger.auth");
    }

    public function login(Request $request)
    {
        $user_name = $request["username"] ?? null;
        $user_password = $request["password"] ?? null;
        $swagger_clients = config("swagger_clients");
        if (isset($user_name) && isset($user_password)) {
            $user_exist = array_search($user_name, array_column($swagger_clients, "username"));
            if ($user_exist !== false) {
                $password = $swagger_clients[$user_exist]["password"];
                if (Hash::check($user_password, $password)) {
                    session(['user_logged_in' => true]);
                    $user_logged_in = session('user_logged_in', false);
                    return response()->redirectTo("/api/documentation/");
                } else {
                    $message = "اطاعات وارده شده اشتباه است.";
                    return redirect(route("api_documentation_auth"))->with("error", $message);
                }
            } else {
                $message = "اطاعات وارده شده اشتباه است.";
                return redirect(route("api_documentation_auth"))->with("error", $message);
            }

        }
        return abort(403);

    }

    public function swap_dark_mode($darkMode = 2)
    {
        session(['darkMode' => $darkMode]);
        return redirect()->back();
    }

}
