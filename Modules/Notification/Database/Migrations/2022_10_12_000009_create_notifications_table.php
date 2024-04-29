<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        # notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('text')->nullable();
            $table->timestamp('send_at')->comment('زمان ارسال');
            $table->integer('priority')->default(0)->comment('priority');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
        });

        # notification_template
        Schema::create('notification_template', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('text')->nullable();
            $table->unsignedBigInteger('channel_id')->nullable()->comment('channel_id');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
        });

        # notification_sent_user
        //Schema::create('notification_sent_user', function (Blueprint $table) {
        //    $table->comment('اعلان های ارسال شده(قبلا ارسال شده)');
        //    # $table->id();
        //    $table->unsignedBigInteger('notification_id');
        //    $table->unsignedBigInteger('user_id');
        //    $table->timestamp('read_at');
        //    $table->unique(['notification_id', 'user_id']);
        //});

        # notification_user
        Schema::create('notification_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('use_in_db')->default(true);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unique(['notification_id', 'user_id']);
        });

        # notification_permission
        Schema::create('notification_permission', function (Blueprint $table) {
            # $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('permission_id');
            $table->unique(['notification_id', 'permission_id']);
        });

        # notification_role
        Schema::create('notification_role', function (Blueprint $table) {
            # $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('role_id');
            $table->unique(['notification_id', 'role_id']);
        });
//        $this->createViewTable();
    }

    public function deleteViewTable()
    {
        DB::statement("DROP VIEW companiesView");
    }

    public function createViewTable()
    {
        DB::statement("CREATE VIEW companiesView AS SELECT * FROM users");
    }

    public function down()
    {
        Schema::dropIfExists('notification_role');
        Schema::dropIfExists('notification_permission');
        Schema::dropIfExists('notification_user');
        # Schema::dropIfExists('notification_sent_user');
        Schema::dropIfExists('notification_template');
        Schema::dropIfExists('notifications');
//        $this->deleteViewTable();
    }

};
