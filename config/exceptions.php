<?php

return [
    'exceptions' => [
        [
            'exception' => 'ApiKeyDeniedException',
            'level' => 0,
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN/* 403 */,
            'message' => 'api-key ارسال شده معتبر نیست',
            'description' => '',
        ],
        [
            'exception' => 'ForbiddenDeviceException',
            'level' => 0,
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN/* 403 */,
            'message' => '',
            'description' => '',
        ],
        [
            'exception' => 'PDOException',
            'level' => 0,
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR/* 500 */,
            'message' => 'خطا در دیتابیس',
            'description' => 'خطایی در اتصال به دیتایس، یا در کوئری اجرایی افتاده است.',
        ],
        [
            'exception' => 'ValidationException',
            'level' => 0,
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY/* 422 */,
            'message' => 'خطا در داده های ارسال شده',
            'description' => 'اطلاعات ارسالی از طرف front دارای اشکالاتی می باشد',
        ],
        [
            'exception' => 'AuthenticationException',
            'level' => 0,
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED, /* 401 */
            'message' => 'مشکلی در احراز هویت کاربر وجود دارد',
            'description' => 'کاربر مجاز نیست یا اطلاعات احراز هویت نادرست هستند.',
        ],
        [
            'exception' => 'ModelNotFoundException',
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND, /* 404 */
            'message' => 'مورد مورد نظر پیدا نشد',
            'description' => 'رکورد مورد نظر در دیتابیس یافت نشد.',
        ],
        [
            'exception' => 'AuthorizationException',
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, /* 403 */
            'message' => 'شما اجازه انجام این عملیات را ندارید',
            'description' => 'دسترسی غیر مجاز به این عملیات رد شد.',
        ],
        [
            'exception' => 'MethodNotAllowedHttpException',
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_METHOD_NOT_ALLOWED, /* 405 */
            'message' => 'متد HTTP نامعتبر است',
            'description' => 'متد ارسالی درخواست HTTP برای این درخواست مجاز نیست.',
        ],
        [
            'exception' => 'HttpException',
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR/* 500 */,
            'message' => '',
            'description' => '',
        ],
        [
            'exception' => 'UserNotActiveException',
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN/* 403 */,
            'message' => 'شما فعال نیستید',
            'description' => '',
        ],
        [
            'exception' => 'ApiKeyDeniedException',
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN/* 403 */,
            'message' => 'api-key ارسال شده معتبر نیست',
            'description' => '',
        ],
        [
            'exception' => 'ForbiddenCustomException',
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN/* 403 */,
            'message' => 'اجازه سطح دسترسی ندارید',
            'description' => '',
        ],
        [
            'exception' => 'CreateLinkPaymentException',
            'status_code' => \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR/* 500 */,
            'message' => 'خطا در ایجاد لینک',
            'description' => '',
        ],
    ]
];
