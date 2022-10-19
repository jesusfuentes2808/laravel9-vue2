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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->decimal("qty", 10,2)->nullable(false);
            $table->string("product_description")->nullable(false);
            $table->decimal("product_price_sale", 10,2)->nullable(false);
            $table->decimal("product_price_total", 10, 2)->nullable(false);
            $table->bigInteger("product_id")->unsigned();
            $table->bigInteger("invoice_id")->unsigned();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
};
