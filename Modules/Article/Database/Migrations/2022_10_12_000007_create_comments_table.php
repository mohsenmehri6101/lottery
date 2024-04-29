<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->comment('جدول ذخیره کامنت (توضیحات)');
            $table->id();
            $table->text('text')->comment('توضیحات_کامنت');
            $table->unsignedBigInteger('parent')->nullable();
            $table->unsignedBigInteger('article_id')->nullable()->comment('article_id');
            $table->tinyInteger('status')->nullable()->default(0)->comment('وضعیت کامنت');
            $table->bigInteger('likes_count')->default(0)->comment('likes_count');
            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
            $table->timestamps();

            # $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            # $table->foreignId('user_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }

};
