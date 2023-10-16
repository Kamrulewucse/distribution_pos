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
        Schema::create('sr_product_assign_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sr_product_assign_order_id');
            $table->unsignedInteger('product_brand_id');
            $table->unsignedInteger('product_model_id');
            $table->float('assign_quantity');
            $table->float('sale_quantity');
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
        Schema::dropIfExists('sr_product_assign_order_items');
    }
};
