<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('parent')->nullable();
        });

        Schema::create('category_gym', function (Blueprint $table) {
            $table->bigInteger('category_id');
            $table->bigInteger('gym_id');
            # $table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            # $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique(['category_id', 'gym_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_gym');
        Schema::dropIfExists('categories');
    }

};
