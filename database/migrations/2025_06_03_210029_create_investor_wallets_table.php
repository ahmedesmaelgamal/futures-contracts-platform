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
        Schema::create('investor_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vendor_id')->comment('auth id of vendor to know who do this operation')->constrained()->cascadeOnUpdate()->cascadeOnUpdate();
            $table->double('amount')->default(0);
            $table->tinyInteger('type')->nullable()->comment('0 for Deposit 1 for withdrawal');
            $table->text('note')->nullable();
            $table->dateTime('date');
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
        Schema::dropIfExists('investor_wallets');
    }
};
