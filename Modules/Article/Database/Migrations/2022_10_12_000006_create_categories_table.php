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

        Schema::create('category_article', function (Blueprint $table) {
            $table->bigInteger('category_id');
            $table->bigInteger('article_id');
            # $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            # $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique(['category_id', 'article_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_article');
        Schema::dropIfExists('categories');
    }

};
