<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        # channels
        Schema::create('channels', function (Blueprint $table) {
            $table->comment('جدول ثبت انواع کانالهای اطلاع رسانی');
            # example: telegram, email, sms, ...
            $table->id();
            $table->string('name')->unique()->comment('english name');;
            $table->string('title')->nullable()->comment('persian name');;
            $table->string('description')->nullable();
        });

        # events
        Schema::create('events', function (Blueprint $table) {
            $table->comment('جدول ثبت انواع رخدادهایی که نیاز به اطلاع رسانی دارند');
            # example: login,register,shopping,change_password,edit_profile,...
            $table->id();
            $table->string('name')->unique()->comment('english name');
            $table->string('title')->nullable()->comment('persian name');
            $table->string('tag')->nullable()->comment('tag');
            $table->string('description')->nullable();
            $table->integer('priority')->default(0)->comment('priority event');
            $table->unsignedBigInteger('notification_template_id')->nullable();
        });

        # channel_event_user
        Schema::create('channel_event_user', function (Blueprint $table) {
            $table->comment('جدول مدیریت اطلاع رسانی کاربر(توسط خود کاربر مدیریت میشود)');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('channel_id');
            $table->boolean('status')->default(true);
            $table->unique(['channel_id', 'event_id', 'user_id']);
        });

        $this->insertChannels();
        $this->insertNotificationTemplate();
        $this->insertEvents();
    }

    public function insertChannels()
    {
        /** @var \Modules\Notification\Http\Repositories\ChannelRepository $channel_repository */
        $channel_repository = resolve('ChannelRepository');
        $channel_repository->create(['name' => 'sms']);
        $channel_repository->create(['name' => 'email']);
        $channel_repository->create(['name' => 'telegram']);
    }

    public function insertNotificationTemplate()
    {
    }

    public function insertEvents()
    {
        /** @var \Modules\Notification\Http\Repositories\EventRepository $event_repository */
        $event_repository = resolve('EventRepository');
        $event_repository->create(['name' => 'login']);
        $event_repository->create(['name' => 'register', 'priority' => 10]);
    }

    public function down()
    {
        Schema::dropIfExists('channel_event_user');
        Schema::dropIfExists('events');
        Schema::dropIfExists('channels');
    }
};
