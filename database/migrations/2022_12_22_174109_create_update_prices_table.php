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
        Schema::create('update_prices', function (Blueprint $table) {
            $table->id();
            $table->float('old_purchase_price', 20);
            $table->float('old_selling_price', 20);
            $table->float('updated_purchase_price', 20);
            $table->float('updated_selling_price', 20);
            $table->float('total_quantity', 20);
            $table->float('reduce_price', 20);
            $table->float('total_price', 20);
            $table->string('note')->nullable();
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
        Schema::dropIfExists('update_prices');
    }
};
