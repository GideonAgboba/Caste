<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id');
            $table->integer('country_id')->nullable();
            $table->string('fullname');
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->longText('bio')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('shirt_size')->nullable();
            $table->integer('waist_size')->nullable();
            $table->integer('rating')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_suspended')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->longText('not_bot')->nullable();
            $table->string('path')->default('user.png');
            $table->string('cover_path')->default('user_cover.png');
            $table->string('actor_type')->nullable();
            $table->string('model_type')->nullable();
            $table->string('crew_type')->nullable();
            $table->string('crew_type_info')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
