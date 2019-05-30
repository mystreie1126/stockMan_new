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
        Schema::create('sm_HQstockTake_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('web_stock_id')->nullable();
            $table->string('name')->nullable();
            $table->string('reference');
            $table->integer('updated_quantity');
            $table->integer('user_id');
            $table->integer('added')->default(0);
            $table->integer('sealed')->default(0);
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
