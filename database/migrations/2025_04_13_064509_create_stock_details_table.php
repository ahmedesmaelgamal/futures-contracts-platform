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
        Schema::create('stock_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')
                ->constrained('stocks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->double('price')->unsigned()->comment('price of each stock');
            $table->double('quantity')->unsigned()->comment('quantity of each stock');

            $table->decimal('vendor_commission', 10, 2)->nullable()->comment('vendor commission');
            $table->decimal('investor_commission', 10, 2)->nullable()->comment('investor commission');
            $table->decimal('sell_diff', 10, 2)->nullable()->comment('selling difference');

            $table->boolean('is_sold')->default(0)->comment('1 = sold, 0 = not sold');

            $table->timestamps();

            // Indexes for performance
            $table->index('stock_id');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_details');
    }
};
