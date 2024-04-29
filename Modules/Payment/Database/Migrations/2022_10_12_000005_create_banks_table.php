<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->comment('لیست بانک ها');
            $table->id();
            $table->string('name')->unique()->nullable('نام بانک');
            $table->string('persian_name')->unique()->nullable('نام فارسی بانک');
            # $table->tinyInteger('status')->nullable()->default(0)->comment('');
        });
        $this->insert_banks();
    }
    private function insert_banks(): void
    {
        foreach (Modules\Payment\Entities\BanksEnum::cases() as $bank) {
            Modules\Payment\Entities\Bank::query()->create([
                'name' => $bank->name,
                'persian_name' => $bank->value,
            ]);
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
