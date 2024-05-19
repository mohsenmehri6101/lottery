<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->comment('رشته باشگاه ورزشی');
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sport_gym', function (Blueprint $table) {
            $table->bigInteger('sport_id');
            $table->bigInteger('gym_id');
            # $table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            # $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade');
            $table->unique(['sport_id', 'gym_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('sport_gym');
        Schema::dropIfExists('sports');
    }
};
