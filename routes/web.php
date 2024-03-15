<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

# php artisan serve --host=192.168.125.8 --port=8000
# #########################################################
# php artisan l5-swagger:generate
Route::get("/api/documentation/auth", [\App\Http\Controllers\ApiDocumentationAuth::class, "show"])->name("api_documentation_auth");
Route::post("/api/documentation/auth", [\App\Http\Controllers\ApiDocumentationAuth::class, "login"])->name("api_documentation_auth_login");
Route::get('/api/documentation/swap-dark-mode/{darkMode}', [\App\Http\Controllers\ApiDocumentationAuth::class, 'swap_dark_mode'])->name('api_documentation_swap_dark_mode');

// catch-all route
// Define specific routes
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/qrcode/{qrcode}', [\App\Http\Controllers\HomeController::class, 'qrcodeConverter']);
Route::get('/about', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/notification', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/transaction', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/my-reserves', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/setting', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/profile', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/contact-us', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/faq', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/gyms', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/transaction', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/setting', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/profile', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/contact-us', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/faqs', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/rules', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/purchase-process', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/gyms', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/checkout', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/privacy-page', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/my-courses', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/my-purchases', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/rules', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/confirm-payment', [\App\Http\Controllers\HomeController::class, 'index'])->name('web.confirm.payment');
Route::get('/reserves/tracking-code', [\App\Http\Controllers\HomeController::class, 'index'])->name('web.confirm.payment');
Route::get('/gyms/{id}', [\App\Http\Controllers\HomeController::class, 'index']);
/*   -------------------------------------------------------------------------------------  */
Route::prefix('admin')->group(function () {
    Route::get('/sports', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/attributes', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/categories', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/tags', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/faqs', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/gyms', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/keywords', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/sliders', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/reserves', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/users', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/errors', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/factors', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/payments', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/reserves', [\App\Http\Controllers\HomeController::class, 'index']);
});
/*   -------------------------------------------------------------------------------------  */
Route::prefix('manager')->group(function () {
    Route::get('/gyms', [\App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/reserves', [\App\Http\Controllers\HomeController::class, 'index']);
});

Route::get('/confirm-payment', [PaymentController::class, 'confirmPayment'])->name('web.confirm_payment_get');
Route::post('/confirm-payment', [PaymentController::class, 'confirmPayment'])->name('web.confirm_payment_post');

/*   -------------------------------------------------------------------------------------  */

/*
* 3 روز.
پیاده سازی کامل سیستم اهراز هویت (فرانت و بک اند)
	- سیستم ثبت نام
		- پیاده سازی ثبت نام برای نقش های مختلف
			- پیاده سازی ثبت نام کاربرهای معمولی
			- پیاده سازی ثبت نام کاربرهای مسئول سالن ورزشی.
	- سیستم لاگین
		- لاگین کاربر معمولی و مسئول سالن ورزشی و ادمین.
	- سیستم فراموشی رمز عبور
		- ارسال پیامک و یا لینک تغییر رمز عبور یا همان فراموشی رمز عبور.
	- پیاده سازی سیستم ورود با رمز یک بار مصرف.
	- تست همه بخش های قبلی.
-------------------------------------------------------------------------------
2 روز.
- عملیات کامل crud جدول reserve_templates.
- نمایش اطلاعات رزرو برای یک سالن ورزشی.
	- حذف یک رزرو.
	- ویرایش یک رزرو.
	- جستجوی یک رزرو.
	- اعتبارسنجی یک رزرو.
------------------------------------------------------------------------------
* 2 روز.
- عملیات پرداخت.
	- تحقیق و توسعه در مورد نحوه انتخاب پرداخت.
	- اتصال به پنل پرداخت.
	-  ذخیره در جدول پرداخت.
	- اتصال جدول پرداخت و جدول فاکتور.
	- اتصال جدول فاکتور و reserves .
-----------------------------------------------------------------------------
* 2 روز.
- عملیات مشاهده لیست وقت های ذخیره ی یک سالن ورزشی.
-------------------------------------------------------------------------------
* یک روز
- مشاهده تراکنش های من.
- مشاهده فاکتور های من.
- مشاهده رزروهای من.
- مشاهده این سه جزء با جزئیات من.
------------------------------------------------------------------------------
5 روز.
- اطلاع رسانی.
	- تحقیق و توسعه در مورد نحوه پیاده سازی روش اطلاع رسانی.
	- پیاده سازی سیستم ارسال پیامک با اعتصاب interface و event برای توانایی عوض کردن سامانه پیامکی.
	- پیاده سازی سیستم اطلاع رسانی در محل های مورد نیاز برای خبر رسانی.
*/
