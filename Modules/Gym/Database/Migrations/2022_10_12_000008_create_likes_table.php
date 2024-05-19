<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        # gym
        Schema::create('likes_gym', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
            $table->unsignedBigInteger('gym_id')->comment('gym_id');
            $table->tinyInteger('type')->default(\Modules\Gym\Entities\LikeGym::type_like/*1*/)->comment('type like =1 or dislike = 0');
            $table->unique(['user_id', 'gym_id']);
        });

        # comment
        Schema::create('likes_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
            $table->unsignedBigInteger('comment_id')->comment('comment_id');
            $table->tinyInteger('type')->default(\Modules\Gym\Entities\LikeComment::type_like/*1*/)->comment('type like =1 or dislike = 0');

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            # $table->foreignId('user_id')->constrained();
            # table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');

            $table->unique(['user_id', 'comment_id','type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes_gym');
        Schema::dropIfExists('likes_comment');
    }

};
