<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet', function (Blueprint $table) {
            $table->id();
            $table->string('sum_price')->nullable()->comment('مبلغ کل پول(کیف پول)');
            $table->string('block_price')->nullable()->comment('مبلغ پول بلاک شده');
            $table->tinyInteger('status')->nullable()->default(0)->comment('وضعیت پرداخت');
            $table->unsignedBigInteger('user_id')->nullable()->comment('کاربری که فاکتور براش ایجاد شده');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet');
    }
};
