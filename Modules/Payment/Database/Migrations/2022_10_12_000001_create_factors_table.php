<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Payment\Entities\Factor;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('factors', function (Blueprint $table) {
            $table->comment('جدول فاکتورها');
            $table->id();
            $table->string('code')->unique()->comment('کد پیگیری خرید');
            $table->decimal('vat_rate', 3, 2)->default(0.00)->comment('نرخ مالیات بر ارزش افزوده (value added tax)'); // نرخ مالیات بر ارزش افزوده
            $table->decimal('total_price', 15, 3)->default(0)->comment('مجموع قیمت بدون مالیات'); // مجموع قیمت بدون مالیات
            $table->decimal('total_price_vat', 15, 3)->default(0)->comment('مجموع قیمت شامل مالیات'); // مجموع قیمت با مالیات
            $table->text('description')->nullable()->comment('توضیحات');
            $table->tinyInteger('status')->nullable()->default(Factor::status_unknown/*0*/)->comment('وضعیت پرداخت');
            $table->unsignedBigInteger('gym_id')->nullable()->comment('gym_id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('کاربری که فاکتور براش ایجاد شده');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->unsignedBigInteger('payment_id_paid')->nullable()->comment('payment_id_paid');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('factor_reserve', function (Blueprint $table) {
            $table->comment('جدول واسط بین فاکتور و دوره ها');
            # ### ### ### ###
            $table->decimal('price', 15, 3)->default(0);
            $table->unsignedBigInteger('reserve_id');
            $table->unsignedBigInteger('factor_id');
            $table->primary(['factor_id', 'reserve_id'], 'factor_reserve');
            $table->unique(['factor_id', 'reserve_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factor_reserve');
        Schema::dropIfExists('factors');
    }
};

/*
موسسه حسابرسی میتواند
(گزارش افزوده فقط با مبلغ سر و کار دارد) گزارش ارزش افزوده گزارشی است که هر سه ماه باید به اداره مالیات ارائه بدهیم.
گزارش فصلی هم به اداره دارایی باید ارائه دهیم(اشخاص حقیقی و حقوقی هم شامل میشود - کد ملی - کد اقتصادی)
کد کارگاه - همه ی کسب و کارها باید بیمه تامین اجتماعی داشته باشند
هر کسی که کد کارگاه نداشته باشد باید سی درصد جریمه بدهد
کد کارگاه - همون پروانه کسب یعنی
سامانه موئدیان

حساب دفتر کل به شرح خط زیر است
بدهکار - بستانکار - مانده - شماره ردیف - تاریخ - نام و نام خانوادگی

هر شخصی یک صورت حساب جدا دارد

بدهکار
بستانکار
------------------
واریزی ها - بدهکار
برداشت - بستانکار
------------------
حساب تفصیلی -
------------------
تا زمانیکه خدماتی دریافت نکرده میشود نوع بدهکار، زمانیکه خدمات را تحویل گرفت بستانکار میشود.
انواع گزارش ها
- حساب دفتر کل
- گزارش واریزها
- گزارش سالیانه
- گزارش صورت حساب
- گزارش سود و زیان
- گزارش ترازنامه
- پرداختی های شما
------------------
بدهکار
بستانکار
---------
واریزی ها - بدهکار
برداشت - بستانکار
---------
حساب تفصیلی -
---------
تا زمانیکه خدماتی دریافت نکرده میشود نوع بدهکار، زمانیکه خدمات را تحویل گرفت بستانکار میشود.
انواع گزارش ها:
- حساب دفتر کل.
- گزارش واریزها.
- گزارش سالیانه.
- گزارش صورت حساب.
- گزارش سود و زیان.
- گزارش ترازنامه.
- پرداختی های شما.
 */
