<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiverDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(255);
        Schema::create('receiver_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('receiver_id');
            $table->boolean('is_kyc_passed');
            $table->char('phone_number');
            $table->smallInteger('ssn')->unsigned();
            $table->date('birth_date');
            $table->char('address');
            $table->char('address_2');
            $table->char('postal_code', 30);
            $table->char('city');
            $table->char('state');
            $table->char('country');
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
        Schema::dropIfExists('receiver_data');
    }
}
