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

<<<<<<< HEAD:Modules/Article/Database/Migrations/2022_10_12_000006_create_categories_table.php
        Schema::create('article_category', function (Blueprint $table) {
=======
        Schema::create('category_gym', function (Blueprint $table) {
>>>>>>> 9fbdb5c38ecd2efd2925a40a73d64b2ec1bb3123:Modules/Gym/Database/Migrations/2022_10_12_000006_create_categories_table.php
            $table->bigInteger('category_id');
            $table->bigInteger('gym_id');
            # $table->foreign('gym_id')->references('id')->on('gyms')->onDelete('cascade');
            # $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique(['category_id', 'gym_id']);
        });
    }

    public function down(): void
    {
<<<<<<< HEAD:Modules/Article/Database/Migrations/2022_10_12_000006_create_categories_table.php
        Schema::dropIfExists('article_category');
=======
        Schema::dropIfExists('category_gym');
>>>>>>> 9fbdb5c38ecd2efd2925a40a73d64b2ec1bb3123:Modules/Gym/Database/Migrations/2022_10_12_000006_create_categories_table.php
        Schema::dropIfExists('categories');
    }

};
