<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0)->nullable()->comment('status');
            $table->string('resnumber',40);
            $table->decimal('amount',15,3)->default(0);
            $table->unsignedBigInteger('factor_id')->nullable()->comment('factor_id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }

};
