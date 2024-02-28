<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Gym\Entities\Reserve;
use Modules\Gym\Entities\ReserveTemplate;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reserve_templates', function (Blueprint $table) {
            $table->id();
            $table->time('from');
            $table->time('to');
            $table->unsignedBigInteger('gym_id');
            $table->tinyInteger('week_number')->comment('week_number');
            $table->decimal('price', 15, 3)->default(0)->comment('price');
            $table->boolean('cod')->default(false)->comment('اجازه پرداخت نقدی');
            $table->boolean('is_ball')->nullable()->comment('اجاره توپ');
            $table->tinyInteger('gender_acceptance')->nullable()->comment('پذیرش جنسیت');
            $table->boolean('discount')->nullable()->default(0)->comment('تخفیف');
            $table->tinyInteger('status')->nullable()->default(ReserveTemplate::status_active/*1*/)->comment(implode(' - ', Reserve::getStatusPersian()));
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['from', 'to', 'week_number', 'gym_id']);
        });

        Schema::create('reserves', function (Blueprint $table) {
            $table->comment('وقت های سالن های ذخیره شده توسط کاربر');
            $table->id();
            $table->tinyInteger('status')->nullable()->default(Reserve::status_active/*1*/)->comment('');
            $table->unsignedBigInteger('reserve_template_id')->nullable();
            $table->unsignedBigInteger('gym_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->tinyInteger('payment_status')->nullable()->default(0)->comment('');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->date('dated_at')->nullable()->comment('تاریخ رزرو');
            $table->boolean('want_ball')->default(false)->comment('توپ میخواهد؟');
            $table->timestamp('reserved_at')->nullable()->comment('تاریخ رزرو موقت');
            $table->unsignedBigInteger('reserved_user_id')->nullable()->comment('شناسه کاربر رزرو کننده');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['dated_at', 'reserve_template_id']);
        });

        Schema::create('attribute_gym_price_reserve', function (Blueprint $table) {
            $table->comment('جدول ثبت قیمت هر امکانات برای هر نمونه وقت ذخیره شده');
            $table->bigInteger('attribute_gym_price_id');
            $table->bigInteger('reserve_id');
            $table->unique(['attribute_gym_price_id', 'reserve_id'], 'unique_attribute_gym_price_reserve');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_gym_price_reserve');
        Schema::dropIfExists('reserve_templates');
        Schema::dropIfExists('reserves');
    }

};
