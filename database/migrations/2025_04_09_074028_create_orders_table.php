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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('investor_id')->constrained('investors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('order_number')->nullable()->unique();

            $table->integer(column: 'quantity');
            $table->double('required_to_pay')->comment('دا بيقي ال delivered_price_to_client  ونسبه الربح');
            $table->double('expected_price');
            $table->double('Total_expected_commission');
            $table->double('sell_diff');
            $table->double('delivered_price_to_client');
            $table->date('date');


            $table->string('investor_commission')->default(0);
            $table->string('vendor_commission')->default(0);

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
        Schema::dropIfExists('orders');
    }
};
