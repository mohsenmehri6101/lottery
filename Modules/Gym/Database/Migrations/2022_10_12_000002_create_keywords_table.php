<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->string('slug');
        });

        Schema::create('gym_keyword', function (Blueprint $table) {
            $table->unsignedBigInteger('gym_id');
            $table->unsignedBigInteger('keyword_id');
            #$table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            # $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade');
            $table->unique(['gym_id', 'keyword_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_keyword');
        Schema::dropIfExists('keywords');
    }

};
