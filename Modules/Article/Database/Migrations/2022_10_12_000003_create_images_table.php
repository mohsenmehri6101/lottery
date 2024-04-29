<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('images_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->string('title')->nullable()->comment('عنوان');
            $table->string('original_name')->nullable()->comment('نام اصلی عکس');
            $table->string('image')->nullable()->comment('نام عکس');
            $table->string('type')->nullable()->comment('نوع عکس');
            $table->string('url')->nullable()->comment('آدرس کامل عکس');
            # $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images_articles');
    }

};
