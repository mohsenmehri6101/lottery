<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Entities\UserDetail;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->comment('اطلاعات کاربر(اطلاعات هویتی + اطلاعات ورود)');
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('email')->nullable()->unique();
            $table->string('mobile')->unique();
            $table->string('code')->unique()->nullable();
            $table->string('parent_code')->nullable();
            $table->tinyInteger('status')->nullable()->default(User::status_active/*1*/)->comment(implode(' - ', User::getStatusUserPersian()));
            $table->string('avatar')->nullable()->comment('عکس پروفایل کاربر');
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('user_details', function (Blueprint $table) {
            $table->comment('(اطلاعات شخصی + اطلاعات مکانی کاربر)ریزاطلاعات کاربران');
            $table->id();
            $table->bigInteger('user_id')->nullable()->comment('user_id');
            $table->string('name')->nullable();
            $table->string('family')->nullable();
            $table->string('father')->nullable();
            $table->string('national_code')->unique()->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('gender')->default(UserDetail::gender_unknown/*0*/)->nullable();
            // info locations
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->unsignedBigInteger('city_id')->nullable()->comment('city_id');
            $table->text('address')->nullable();
            #### #### #### ####
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'id']);
        });
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

};
