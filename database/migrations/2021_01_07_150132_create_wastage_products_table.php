<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWastageProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wastage_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('wastage_id');
            $table->bigInteger('purchase_product_id')->nullable();
            $table->string('name')->nullable();
            $table->string('purchase_product_category_name')->nullable();
            $table->string('purchase_product_sub_category_name')->nullable();
            $table->string('warranty')->nullable();
            $table->string('serial')->nullable();
            $table->double('quantity', 20,2)->nullable();
            $table->double('unit_price')->nullable();
            $table->double('total')->nullable();
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
        Schema::dropIfExists('wastage_products');
    }
}
