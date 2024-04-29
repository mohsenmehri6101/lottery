<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->comment('جدول کانفیگ');
            $table->id();
            $table->string('key')->unique()->comment('کلید');
            $table->string('title')->nullable()->comment('عنوان');
            $table->json('value')->nullable()->comment('value should be config');
            $table->string('tag')->nullable()->comment('tag');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configs');
    }

};
