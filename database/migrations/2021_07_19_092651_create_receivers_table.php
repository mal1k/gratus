<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->char('first_name');
            $table->char('last_name');
            $table->char('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('punch_in')->nullable();
            $table->timestamp('punch_out')->nullable();
            $table->binary('photo')->nullable();
            $table->string('verificate_code')->nullable();
            $table->string('forgot_pass')->nullable();
            $table->rememberToken();
            $table->unsignedTinyInteger('status');
            $table->timestamps();
            $table->integer('org_id')->nullable();
            $table->integer('kys_status')->nullable();
            $table->string('nfc_chip')->nullable();
            $table->integer('if_in_group')->nullable();
            $table->string('firebase_token')->nullable();

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
        Schema::dropIfExists('receivers');
    }
}
