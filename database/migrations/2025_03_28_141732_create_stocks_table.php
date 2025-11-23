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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('quantity')->comment('this is quantity');
            $table->string('total_price_sub')->nullable()->comment('this is buy price if investor want to cancel the investment');

            $table->string('total_price_add')->nullable()->comment('this is total price after add all commission and selling difference in add operation');

            $table->string('vendor_commission')->nullable()->comment('this is vendor commission');
            $table->string('investor_commission')->nullable()->comment('this is investor commission');
            $table->string('sell_diff')->nullable()->comment('selling difference');


            $table->string('price')->nullable()->comment('(total_price -(vendor_commission + investor_commission + sell_diff))/quantity');
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
        Schema::dropIfExists('stocks');
    }
};
