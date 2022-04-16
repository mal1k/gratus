<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->char('transaction_id')->unique();
            $table->integer('org_id')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->integer('tipper_id');
            $table->integer('amount');
            $table->boolean('anon_transfer')->nullable();
            $table->integer('stars')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedTinyInteger('status');
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
        Schema::dropIfExists('transactions');
    }
}
