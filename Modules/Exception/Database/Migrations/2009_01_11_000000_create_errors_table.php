<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /*
    DROP TABLE IF EXISTS `errors`;
    CREATE TABLE `errors`  (
      `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      `status_code` int(11) NULL DEFAULT NULL COMMENT 'http status code',
      `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'روت خطای اتفاق افتاده',
      `exception` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'نوع خطا(نام خطا)',
      `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'متن خطا',
      `user_creator` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT 'user_creator',
      `stack_trace` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT 'محل وقوع خطا  - شرح خطا - نوع خطا',
      `requests` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT 'اطلاعات ارسالی توسط کاربر',
      `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT 'اطلاعات هدر کاربر',
      `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'اطلاعات دستگاه کاربر',
      `extra_date` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT 'اطلاعات اضافی که توسط developer تنظیم شده است',
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'جدول ثبت خطاهای اتفاق افتاده در پروژه' ROW_FORMAT = Dynamic;
*/

        Schema::create('errors', function (Blueprint $table) {
            $table->comment('جدول ثبت خطاهای اتفاق افتاده در پروژه');
            $table->id();
            $table->integer('status_code')->nullable()->comment('http status code');
            $table->string('url')->nullable()->comment('روت خطای اتفاق افتاده');
            $table->string('exception')->nullable()->comment('نوع خطا(نام خطا)');
            $table->text('message')->nullable()->comment('متن خطا');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->json('stack_trace')->nullable()->comment('محل وقوع خطا  - شرح خطا - نوع خطا');
            $table->json('requests')->nullable()->comment('اطلاعات ارسالی توسط کاربر');
            $table->json('headers')->nullable()->comment('اطلاعات هدر کاربر');
            $table->string('user_agent')->nullable()->comment('اطلاعات دستگاه کاربر');
            $table->json('extra_date')->nullable()->comment('اطلاعات اضافی که توسط developer تنظیم شده است');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('errors');
    }

};
