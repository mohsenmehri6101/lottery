<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Output\ConsoleOutput;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exceptions', function (Blueprint $table) {
            $table->comment('');// todo what is that?
            $table->id();
            $table->string('exception')->unique()->comment('نام اکسپشن');
            $table->integer('status_code')->nullable()->comment('http status code');
            $table->tinyInteger('level')->default(0)->comment('سطح اولویت خطا');
            $table->string('message')->nullable()->comment('متن خطا برای نمایش به کاربر');
            $table->string('description')->nullable()->comment('توضیح در مورد این اکسپشن ');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->run_command();
    }

    public function run_command(): void
    {
        $output = new ConsoleOutput();
        \Illuminate\Support\Facades\Artisan::call("exceptions:insert", [], $output);
    }

    public function down(): void
    {
        Schema::dropIfExists('exceptions');
    }

};
