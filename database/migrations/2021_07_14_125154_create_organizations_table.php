<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->char('name');
            $table->char('email')->unique();
            $table->binary('photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('forgot_pass')->nullable();
            $table->string('firebase_token')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedTinyInteger('status');
            $table->timestamps();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}
