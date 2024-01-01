<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->unsignedBigInteger('city_id')->nullable()->comment('city_id');
            $table->text('address')->nullable();
            $table->text('short_address')->nullable();
            // info locations
            $table->tinyInteger('gender_acceptance')->nullable()->comment('پذیرش جنسیت');
            $table->boolean('is_ball')->nullable()->comment('اجاره توپ');
            $table->integer('score')->nullable()->default(0)->comment('score');
            $table->tinyInteger('status')->nullable()->default(0)->comment('status');
            $table->bigInteger('like_count')->default(0)->comment('like count');
            $table->bigInteger('dislike_count')->default(0)->comment('dislike count');
            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();

            # $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            # $table->foreignId('user_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gyms');
    }
};
