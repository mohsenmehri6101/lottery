<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Gym\Entities\Gym;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gyms', function (Blueprint $table) {
            $table->id();
            $table->comment('(باشگاه های ورزشی)باشگاه ورزشی های ورزشی');
            $table->string('name')->nullable()->comment('نام');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->decimal('price', 15, 3)->default(0)->comment('قیمت');
            // info locations
            $table->string('latitude')->nullable()->comment('عرض جغرافیایی');
            $table->string('longitude')->nullable()->comment('طول جغرافیایی');
            $table->unsignedBigInteger('city_id')->nullable()->comment('city_id');
            $table->text('address')->nullable()->comment('آدرس کامل');
            $table->text('short_address')->nullable()->comment('آدرس کوتاه');
            // info locations
            $table->tinyInteger('gender_acceptance')->nullable()->comment('پذیرش جنسیت');
            $table->integer('priority_show')->default(0)->nullable()->comment('اولویت در نمایش');
            $table->boolean('is_ball')->nullable()->comment('اجاره توپ');
            $table->decimal('ball_price', 15, 3)->default(0)->nullable()->comment('قیمت توپ ورزشی');
            $table->integer('score')->nullable()->default(0)->comment('score');
            $table->tinyInteger('status')->nullable()->default(Gym::status_active)->comment('status');
            $table->bigInteger('like_count')->default(0)->comment('like count');
            $table->bigInteger('dislike_count')->default(0)->comment('dislike count');
            $table->tinyInteger('profit_share_percentage')->nullable()->comment('مقدار سهم سود(سایت) از این سالن ورزشی');
            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('gyms');
    }
};
