<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        //        Schema::create('transactions', function (Blueprint $table) {
        //            $table->id();
        //            $table->string('amount')->nullable()->comment('price');
        //            $table->text('description')->nullable()->comment('شرح عملیات');
        //            $table->tinyInteger('specification')->default(0)->nullable()->comment('تشخیص(بدهکار-بستانکار)');
        //            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
        //            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
        //            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
        //            $table->timestamps();
        //            $table->softDeletes();
        //        });
        //        Schema::create('transaction_details', function (Blueprint $table) {
        //            $table->id();
        //            $table->text('description')->nullable()->comment('شرح عملیات');
        //            $table->string('amount')->nullable()->comment('amount');
        //            $table->unsignedBigInteger('transaction_id')->nullable()->comment('transaction_id');
        //            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
        //            $table->tinyInteger('status')->nullable()->default(0/*0*/)->comment('');
        //        });
    }

    public function down(): void
    {
        // Schema::dropIfExists('transaction_details');
        // Schema::dropIfExists('transactions');
    }
};
