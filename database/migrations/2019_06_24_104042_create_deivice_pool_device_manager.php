<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeivicePoolDeviceManager extends Migration
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
            $table->string('order_id');
            $table->integer('category');
            $table->integer('new_deivice')->default(1);
            $table->datetime('stock_in_time');
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
