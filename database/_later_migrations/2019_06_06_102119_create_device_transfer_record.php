<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTransferRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_device_transfer', function (Blueprint $table) {
            $table->increments('transfer_id');
            $table->integer('device_id')->nullable();
            $table->string('IMEI');
            $table->integer('shop_id');
            $table->integer('by_user')->nullable();
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
        Schema::dropIfExists('sm_device_transfer');
    }
}
