<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gyms_attributes', function (Blueprint $table) {
            $table->comment('ویژگی های باشگاه ورزشی');
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->string('slug')->nullable();
        });

        Schema::create('attribute_gym', function (Blueprint $table) {
            $table->bigInteger('attribute_id');
            $table->bigInteger('gym_id');
            # $table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            # $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->unique(['attribute_id', 'gym_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_gym');
        Schema::dropIfExists('gyms_attributes');
    }

};
