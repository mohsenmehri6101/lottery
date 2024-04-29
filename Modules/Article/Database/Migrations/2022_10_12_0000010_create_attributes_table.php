<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles_attributes', function (Blueprint $table) {
            $table->comment('ویژگی های باشگاه ورزشی');
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->string('slug')->nullable();
        });

        Schema::create('attribute_article', function (Blueprint $table) {
            $table->bigInteger('attribute_id');
            $table->bigInteger('article_id');
            $table->unique(['attribute_id', 'article_id']);
        });

        Schema::create('attribute_article_prices', function (Blueprint $table) {
            $table->comment('جدول ثبت قیمت هر امکان برای هر باشگاه');
            $table->id();
            $table->bigInteger('attribute_id');
            $table->bigInteger('article_id');
            $table->decimal('price', 15, 3)->default(0)->comment('قیمت');
            $table->unique(['attribute_id', 'article_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_article_prices');
        Schema::dropIfExists('attribute_article');
        Schema::dropIfExists('articles_attributes');
    }

};
