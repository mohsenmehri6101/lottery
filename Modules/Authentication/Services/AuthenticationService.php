<?php

namespace Modules\Authentication\Services;

use App\Exceptions\Contracts\UserNotActiveException;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Http\Repositories\UserRepository;
use Modules\Authentication\Http\Requests\ChangePasswordRequest;
use Modules\Authentication\Http\Requests\LoginRequest;
use Modules\Authentication\Http\Requests\OtpConfirmRequest;
use Modules\Authentication\Http\Requests\OtpRequest;
use Modules\Authentication\Http\Requests\ProfileRequest;
use Modules\Authentication\Http\Requests\RegisterResendCodeRequest;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

use function cache;
use function send_sms;

class AuthenticationService
{
    use AuthenticationApiTokenTrait;
    public function __construct(public UserRepository $userRepository)
    {
    }

    public function otp(OtpRequest|RegisterResendCodeRequest $request)
    {
        try {
            $fields = $request->validated();

            /**
             *
             *
             * @var string $mobile
             */
            extract($fields);

            $mobile = $mobile ?? null;

            $code = $this->generate_otp_random();
            $expired_time_config = config('configs.authentication.otp.expired_time');
            $data = ['expired_time'=>$expired_time_config];;
             $this->send_notification_otp($mobile, $code);
             return $data;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function login(LoginRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $loginRequest = new LoginRequest();
                $fields = Validator::make(data: $request,
                    rules: $loginRequest->rules()
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $username
             * @var $password
             */
            extract($fields);

            $username_or_mobile_or_email = $username ?? null;
            $password = $password ?? null;

            /** @var User|null $user */
            $user = UserService::getUser(username: $username_or_mobile_or_email);
            $user = $user ?? UserService::getUser(mobile: $username_or_mobile_or_email) ?? null;
            $user = $user ?? UserService::getUser(email: $username_or_mobile_or_email);

            if ($user && Hash::check($password, $user->password)) {
                return self::setTokenOrApiKey(user: $user, mobile: $user?->mobile ?? null);
            }

            abort(HttpFoundationResponse::HTTP_UNAUTHORIZED/* 401 */, trans('custom.authentication.messages.login_unauthorized'));
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function profile(ProfileRequest $request)
    {
        try {
            $fields=$request->validated();
            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];

            $check_profile= in_array('check_profile',$withs);

            $result =[];

            $user =User::query()->where('id',get_user_id_login())->with('UserDetail')->first();
            $result['user']= $user;

            if($check_profile){
                /** @var UserService $userService */
                $userService = resolve('UserService');
                $check_profile_information = $userService->checkProfile([]);
                $result['check_profile']=$check_profile_information;
            }

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public static function setApiKey($mobile = null): ?string
    {
        $length_api_key_string = config('configs.api_key.length');
        $expired_time_config = config('configs.api_key.expired_time');
        $ttl_cache = intval($expired_time_config * 60);
        $expired_time = now()->addMinutes($expired_time_config);
        $api_key = random_string(length: $length_api_key_string);
        cache()->set($api_key, ['mobile' => $mobile, 'expired_time' => $expired_time], $ttl_cache);
        return $api_key;
    }
    public static function checkApiKey($api_key, $mobile = null): bool
    {
        $information_in_cache = cache()->get($api_key);
        $mobile_in_cache = $information_in_cache['mobile'] ?? null;
        $expired_time = $information_in_cache['expired_time'] ?? null;
        if (Carbon::make($expired_time) < now()) {
            $message = trans('custom.authentication.messages.expired_time_api_key');
            // todo should be customException
            throw new Exception(message: $message, code: HttpFoundationResponse::HTTP_BAD_REQUEST/* 400 */);
        }
        return $mobile_in_cache && $mobile_in_cache == $mobile;
    }
    public static function setTokenOrApiKey(User $user = null, $mobile = null): array
    {
        // todo check user exist and shoud be active , if not ? throw exception need
        if($user && $user->status !=User::status_active){
            throw new UserNotActiveException();
        }
        // $user && $user->status !=User::status_active || throw new UserNotActiveException();

        $token = $user ? JWTAuth::fromuser($user) : null;
        if (is_null($token) && is_null($user)) {
            $data['apiKey'] = self::setApiKey(mobile: $mobile) ?? null;
        } else {
            $user = UserService::getUser($user);
            $data = ['token' => $token, 'user' => $user?->toArray() ?? [], 'apiKey' => null];
        }
        return $data;
    }
    public function otpConfirm(OtpConfirmRequest $request): array
    {
        try {
            $fields = $request->validated();

            /**
             * @var $mobile
             * @var $code
             */
            extract($fields);

            $mobile = $mobile ?? null;

            /** @var User $user */
            $user = UserService::getUser(mobile: $mobile, withs: ['userDetail']);
            cache()->forget($mobile);
            return self::setTokenOrApiKey(user: $user, mobile: $mobile);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function otpConfirmV2(OtpConfirmRequest $request)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $mobile
             * @var $code
             */
            extract($fields);

            $mobile = $mobile ?? null;

            /** @var UserService $userService */
            $userService = resolve('UserService');

            /** @var User $user */
            $user = $userService::getUser(mobile: $mobile, withs: ['userDetail']);

            if(!$user){
                $user =$userService->store(['mobile'=>$mobile]);
            }

            cache()->forget($mobile);

            return $user;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function generate_otp_random(): int
    {
        $min_number_random = config('configs.authentication.otp.min_number_random');
        $max_number_random = config('configs.authentication.otp.max_number_random');
        return rand($min_number_random, $max_number_random);
    }
    public function send_notification_otp($mobile, $otp_random_number)
    {
        try {
            if ($mobile) {
                $expired_time_config = config('configs.authentication.otp.expired_time');
                $ttl_cache = intval($expired_time_config);
                $expired_time = now()->addMinutes($expired_time_config);
                cache()->set($mobile, ['code' => $otp_random_number, 'expired_time' => $expired_time], $ttl_cache);

                $message_notife = trans('notifications_template.send_otp_code', [
                    'web_site_name' => env('APP_NAME','سلام سالن'),
                    'code' => $otp_random_number,
                ]);
                send_sms($mobile, $message_notife);
                return true;
            }
        } catch (Exception $exception) {
            // todo should be throw customException
            throw $exception;
        }
    }

    public static function bool_check_api_key($api_key, $mobile = null): bool
    {
        $information_in_cache = cache()->get($api_key);
        $mobile_in_cache = $information_in_cache['mobile'] ?? null;
        $expired_time = $information_in_cache['expired_time'] ?? null;
        # $firebase_token = $information_in_cache['firebase_token'] ?? null;
        return (bool)Carbon::make($expired_time) >= now();
    }

    public function logout(): bool
    {
        auth()->logout();
        return true;
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $password
             * @var $confirm_password
             */
            extract($fields);

            /** @var User $user */
            $user = auth()->user();
            $user->update(['password'=>$password]);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
