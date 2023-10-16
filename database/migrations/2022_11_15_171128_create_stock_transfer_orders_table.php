<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->unsignedInteger('sourch_warehouse_id');
            $table->unsignedInteger('target_warehouse_id');
            $table->timestamp('date')->nullable();
             $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('stock_transfer_orders');
    }
};
