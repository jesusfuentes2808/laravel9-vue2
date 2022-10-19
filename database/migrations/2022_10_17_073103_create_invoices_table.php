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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string("client_name")->nullable(false);
            $table->string("client_nit")->nullable(false);
            $table->string("business_name")->nullable(false);
            $table->string("business_nit")->nullable(false);
            $table->decimal("iva", 10, 2)->nullable(false);
            $table->decimal("total", 10, 2)->nullable(false);
            $table->decimal("total_without_iva", 10, 2)->nullable(false);
            $table->bigInteger('sale_user_id')->unsigned();
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
        Schema::dropIfExists('invoices');
    }
};
