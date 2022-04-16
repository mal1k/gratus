<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('first_name');
            $table->char('last_name');
            $table->char('email')->unique();
            $table->binary('photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('verificate_code')->nullable();
            $table->string('forgot_pass')->nullable();
            $table->string('firebase_token')->nullable();
            $table->rememberToken();
            $table->unsignedTinyInteger('status');
            $table->timestamps();

            $table->index('first_name');
            $table->index('last_name');
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
