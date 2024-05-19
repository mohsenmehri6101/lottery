<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->comment('qr_codes');
            $table->id();
            $table->string('url');
            $table->string('string_random');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }

};
