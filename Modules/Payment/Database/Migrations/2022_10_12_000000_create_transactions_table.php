<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        // every transaction two.
                Schema::create('transactions', function (Blueprint $table) {
                    $table->comment('جدول برای ذخیره رکوردهای تراکنش.'); // کامنت جدول
                    // کلید اصلی
                    $table->id();
                    // date.
                    $table->unsignedBigInteger('user_destination')->nullable()->comment('user_destination');
                    $table->unsignedBigInteger('user_resource')->nullable()->comment('user_resource');
                    $table->decimal('price', 15, 3)->default(0)->comment('price');
                    $table->text('description')->nullable()->comment('شرح عملیات');
                    $table->tinyInteger('specification')->default(0)->nullable()->comment('تشخیص(بدهکار-بستانکار)');
                    $table->tinyInteger('transaction_type')->nullable()->comment('نوع تراکنش (برداشت یا واریز)');
                    $table->tinyInteger('operation_type')->nullable()->comment('نوع عملیات (تسهیم، واریز کیف پول، پرداخت حق کاربر، و غیره)');
                    $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
                    $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
                    $table->timestamp('timed_at')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                });


    }

    public function down(): void
    {
         Schema::dropIfExists('transactions');
    }
};
