<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSmReplishmentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_sm_all_replishment_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference');
            $table->string('product_name')->nullable();
            $table->integer('web_stock_id');
            $table->integer('shop_stock_id');
            $table->integer('shop_id');
            $table->integer('updated_quantity');
            $table->integer('standard_quantity');
            $table->integer('uploaded');
            $table->integer('rep_by_sale');
            $table->integer('rep_by_custom');
            $table->integer('rep_by_standard');
            $table->dateTime('selected_startDate')->nullable();
            $table->dateTime('selected_endDate')->nullable();
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
        Schema::dropIfExists('test_sm_replishment_history');
    }
}
