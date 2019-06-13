<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewDeviceTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_device_transferToBranch', function (Blueprint $table) {
            $table->increments('transfer_id');
            $table->integer('device_id');
            $table->integer('staff_id');
            $table->integer('shop_id');
            $table->text('notes')->nullable();
            $table->integer('send');
            $table->datetime('transfer_date');
            $table->integer('confirmed')->default(0);
            $table->integer('branch_user_id')->nullable();
            $table->date('confirm_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_device_transferToBranch');
    }
}
