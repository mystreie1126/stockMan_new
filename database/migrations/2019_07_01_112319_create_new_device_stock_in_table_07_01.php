<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewDeviceStockInTable0701 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dm_device_pool', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supply_order_id')->nullable();
            $table->integer('device_type');
            $table->integer('pre_own');
            $table->integer('brand_new');
            $table->string('serial_number')->nullable();
            $table->string('detail')->nullable();
            $table->integer('tested_by')->default(0);
            $table->integer('user_created');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dm_device_pool');
    }
}
