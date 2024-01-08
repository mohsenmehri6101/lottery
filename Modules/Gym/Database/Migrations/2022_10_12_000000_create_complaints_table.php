<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->comment('ثبت شکایات');

            $table->id();

            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->tinyInteger('status')->nullable()->default(0)->comment('status');

            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->unsignedBigInteger('factor_id')->nullable()->comment('factor_id');
            $table->unsignedBigInteger('gym_id')->nullable()->comment('gym_id');
            $table->unsignedBigInteger('reserve_id')->nullable()->comment('reserve_id');
            $table->unsignedBigInteger('reserve_template_id')->nullable()->comment('reserve_template_id');
            $table->unsignedBigInteger('common_complaint_id')->nullable()->comment('common_complaint_id');

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('common_complaints', function (Blueprint $table) {
            $table->comment('شکایات عمومی');
            $table->id();
            $table->text('text')->nullable()->comment('متن شکایت عمومی');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('common_complaints');
    }

};
