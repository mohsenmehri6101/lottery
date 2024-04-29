<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->comment('جدول اسلایدر ها');
            $table->id();
            $table->string('title')->nullable()->comment('عنوان');
            $table->string('image')->nullable()->comment('image');
            $table->string('link')->nullable()->comment('لینک');
            $table->string('text')->nullable()->comment('متن');
            $table->tinyInteger('status')->default(\Modules\Slider\Entities\Slider::status_unknown/*0*/)->nullable()->comment('وضعیت اسلایدر');
            $table->unsignedBigInteger('city_id')->nullable()->comment('city_id');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }

};
