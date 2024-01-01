<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->comment('contact us');
            $table->id();
            $table->string('name')->nullable()->comment('name');
            $table->string('email')->nullable()->comment('email');
            $table->string('phone')->nullable()->comment('phone');
            $table->text('text')->nullable()->comment('text');
            $table->tinyInteger('status')->nullable()->default(0)->comment('');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
