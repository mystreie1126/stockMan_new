<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousedevicePoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_device_pool', function (Blueprint $table) {
            $table->increments('device_id');
            $table->string('order_number')->nullable();
            $table->string('IMEI');
            $table->string('brand');
            $table->string('model');
            $table->string('color');
            $table->string('condition');
            $table->string('storage');
            $table->integer('by_user');
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
        Schema::dropIfExists('sm_device_pool');
    }
}
