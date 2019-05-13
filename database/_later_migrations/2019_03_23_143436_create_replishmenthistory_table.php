<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplishmenthistoryTable extends Migration
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
            $table->integer('shop_product_id');
            $table->integer('pos_product_id');
            $table->string('reference');
            $table->integer('quantity');
            $table->integer('shop_id');
            $table->integer('send')->default(0);
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
