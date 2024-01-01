<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->unique();
            $table->string('slug')->nullable();
            $table->string('type')->nullable();
        });

        Schema::create('gym_tag', function (Blueprint $table) {
            $table->bigInteger('gym_id');
            $table->bigInteger('tag_id');
            # $table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            # $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->unique(['gym_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_tag');
        Schema::dropIfExists('tags');
    }

};
