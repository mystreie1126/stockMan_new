<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHqInventoryCountHistorytabe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_hqInventoryCountHistory', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('web_stock_id');
            $table->string('reference')->nullable();
            $table->integer('current_quantity');
            $table->integer('user_id');
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
        Schema::dropIfExists('sm_hqInventoryCountHistory');
    }
}
