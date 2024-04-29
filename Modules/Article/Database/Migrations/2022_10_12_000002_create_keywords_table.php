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

        Schema::create('article_keyword', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('keyword_id');
            #$table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            # $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade');
            $table->unique(['article_id', 'keyword_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_keyword');
        Schema::dropIfExists('keywords');
    }

};
