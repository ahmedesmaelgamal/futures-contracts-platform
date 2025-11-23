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
        Schema::create('unsurpasseds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');

            $table->string('national_id');
            $table->string('office_name')->nullable();
            $table->string('office_phone')->nullable();
            $table->timestamps();
            $table->foreignId('investor_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->double('debt')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unsurpasseds');
    }
};
