<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandardTalbeWithSoftDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_standardstock_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pos_stock_id');
            $table->string('reference');
            $table->integer('standard_quantiy');
            $table->integer('shop_id');
            $table->softDeletes();
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
        Schema::dropIfExists('sm_standardstock_branches');
    }
}
