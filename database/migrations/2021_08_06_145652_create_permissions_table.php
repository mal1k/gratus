<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{
    DB,
    Schema
};

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->char('name', 191)->unique();
            $table->text('description');
            $table->timestamps();
        });

        $this->initPermissionsTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }

    private function initPermissionsTable()
    {
        $data = [
            [
                'name' => 'full-granted',
                'description' => 'The user can attend both admin panel and api docs pages',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'api-mobile-granted',
                'description' => 'The user can attend only mobile api docs page',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        DB::table('permissions')->insert($data);
    }
}
