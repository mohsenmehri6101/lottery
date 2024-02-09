<?php

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Authentication\Http\Requests\ChangePasswordRequest;
use Modules\Authentication\Http\Requests\LoginRequest;
use Modules\Authentication\Http\Requests\OtpConfirmRequest;
use Modules\Authentication\Http\Requests\OtpRequest;
use Modules\Authentication\Http\Requests\ProfileRequest;
use Modules\Authentication\Http\Requests\RegisterResendCodeRequest;
use Modules\Authentication\Services\AuthenticationService;
use App\Helper\Response\ResponseHelper;

class AuthenticationController extends Controller
{
    public function __construct(public AuthenticationService $authenticationService)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authentication/authenticate/otp",
     *     tags={"authentication"},
     *     summary="ارسال کد تایید(ورود یا ثبت نام)",
     *     @OA\Parameter(name="mobile",in="query",required=true, @OA\Schema(type="integer"),description="شماره موبایل"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function otp(OtpRequest $request): JsonResponse
    {
        $message_success = 'کد تایید برای شما ارسال شد.';
        $data = $this->authenticationService->otp($request);
        return ResponseHelper::responseSuccess(data: $data, message: $message_success);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authentication/authenticate/otp-confirm",
     *     tags={"authentication"},
     *     summary="تایید کد(ثبت نام یا ورود)",
     *     @OA\Parameter(name="mobile",in="query",required=true, @OA\Schema(type="integer"),description="mobile"),
     *     @OA\Parameter(name="code",in="query",required=true, @OA\Schema(type="integer"),description="code"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function otpConfirm(OtpConfirmRequest $request): JsonResponse
    {
        $token_and_user = $this->authenticationService->otpConfirm($request);
        return ResponseHelper::responseSuccess(data: $token_and_user);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authentication/authenticate/otp-confirm-v2",
     *     tags={"authentication"},
     *     summary="تایید کد(ثبت نام)",
     *     @OA\Parameter(name="mobile",in="query",required=true, @OA\Schema(type="string"),description="mobile"),
     *     @OA\Parameter(name="code",in="query",required=true, @OA\Schema(type="integer"),description="code"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function otpConfirmV2(OtpConfirmRequest $request): JsonResponse
    {
        $user = $this->authenticationService->otpConfirmV2($request);
        return ResponseHelper::responseSuccess(data: $user);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/authentication/authenticate/login",
     *     tags={"authentication"},
     *     summary="صفحه ورود",
     *     @OA\Parameter(name="username",in="query",required=true, @OA\Schema(type="string"),description="username"),
     *     @OA\Parameter(name="password",in="query",required=true, @OA\Schema(type="string"),description="password"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token_and_user = $this->authenticationService->login($request);
        return ResponseHelper::responseSuccess(data: $token_and_user);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/authentication/authenticate/change-password",
     *     tags={"authentication"},
     *     security={{"bearerAuth":{}}},
     *     summary="صفحه تغییر رمز ورود",
     *     @OA\Parameter(name="password",in="query",required=true, @OA\Schema(type="string"),description="password"),
     *     @OA\Parameter(name="confirm_password",in="query",required=true, @OA\Schema(type="string"),description="confirm_password"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $message = 'رمز شما با موفقیت تغییر یافت';
        $this->authenticationService->changePassword($request);
        return ResponseHelper::responseSuccess(message: $message);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/authentication/authenticate/profile",
     *     tags={"authentication"},
     *     summary="اطلاعات پروفایل",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="withs:userCreator,userEditor,gyms,userDetail,events,notifications,readNotifications,unreadNotifications,roles,permissions,check_profile"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function profile(ProfileRequest $request): JsonResponse
    {
        $data = $this->authenticationService->profile($request);
        return ResponseHelper::responseSuccess(data: $data);
    }

    public function resendOtp(RegisterResendCodeRequest $request): JsonResponse
    {
        return $this->authenticationService->otp($request) ? ResponseHelper::responseSuccess() : ResponseHelper::responseError();
    }


    /**
     * @OA\Post(
     *     path="/api/v1/authentication/authenticate/logout",
     *     tags={"authentication"},
     *     summary="صفحه خروج",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function logout(): JsonResponse
    {
        $status_logout = $this->authenticationService->logout();
        if ($status_logout) {
            return ResponseHelper::responseSuccess();
        }
        return ResponseHelper::responseError();
    }


    /**
     * @OA\Get (
     *     path="/api/v1/authentication/authenticate/create-token-super-admin",
     *     tags={"authentication"},
     *     summary="ایجاد توکن برای ادمین",
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function createTokenSuperAdmin(): JsonResponse
    {
        $request = [
            'username' => '09366246101',
            'password' => '123456789',
        ];
        $token_and_user = $this->authenticationService->login($request);
        return ResponseHelper::responseSuccess(data: $token_and_user);
    }


    /**
     * @OA\Get (
     *     path="/api/v1/authentication/authenticate/create-token-gym_manager",
     *     tags={"authentication"},
     *     summary="ایجاد توکن برای مسئول سالن",
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function createTokenGymManager(): JsonResponse
    {
        $request = [
            'username' => '09366246102',
            'password' => '123456789',
        ];

        $token_and_user = $this->authenticationService->login($request);
        return ResponseHelper::responseSuccess(data: $token_and_user);
    }



    /**
     * @OA\Get (
     *     path="/api/v1/authentication/authenticate/create-token-user",
     *     tags={"authentication"},
     *     summary="ایجاد توکن برای کاربر معمولی",
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function createTokenUser(): JsonResponse
    {
        $request = [
            'username' => '09366246103',
            'password' => '123456789',
        ];

        $token_and_user = $this->authenticationService->login($request);
        return ResponseHelper::responseSuccess(data: $token_and_user);
    }
}
