<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceContainerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_device_container', function (Blueprint $table) {
            $table->increments('device_id');
            $table->string('order_number')->nullable();
            $table->string('brand');
            $table->string('model');
            $table->string('color')->nullable();
            $table->string('IMEI');
            $table->string('condition')->nullable();

            $table->integer('moved')->default(0);
            $table->string('by_user')->nullable();


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
        Schema::dropIfExists('sm_device_container');
    }
}
