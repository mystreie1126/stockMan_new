<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewReplishmentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_replishment_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->nullable();
            $table->integer('web_stock_id')->nullable();
            $table->integer('shop_stock_id')->nullable();
            $table->integer('shop_id')->nullable();
            $table->integer('updated_quantity')->nullable();
            $table->integer('standard_quantity')->nullable();
            $table->integer('uploaded')->nullable();
            $table->integer('rep_by_sale')->nullable();
            $table->integer('rep_by_custom')->nullable();
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
        Schema::dropIfExists('sm_replishment_history');
    }
}
