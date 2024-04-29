<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->comment('اطلاعات حساب مالی اشخاص');
            $table->id();
            $table->string('account_number')->nullable('شماره حساب');
            $table->string('card_number')->nullable('شماره کارت');
            $table->string('shaba_number')->nullable('شماره شبا');
            $table->unsignedBigInteger('bank_id')->nullable()->comment('شناسه بانک');
            $table->unsignedBigInteger('user_id')->nullable()->comment('کاربر صاحب حساب');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }

};
