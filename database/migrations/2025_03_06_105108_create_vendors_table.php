<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم المكتب لان المكتب بالنسالبا فندور');
            $table->string('phone')->unique();
            $table->string('commercial_number')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('national_id')->nullable();
            $table->string('username')->unique();
            $table->double('balance')->default(0);
            $table->string('password');
            $table->string('role_id')->nullable();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('parent_id')->nullable()->constrained('vendors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('plan_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('status')->default(1);
            $table->string('profit_ratio')->default(0);
            $table->boolean('is_profit_ratio_static')->default(0)->comment('0 = static, 1 = dynamic');
            $table->string('image')->nullable();
            $table->string('otp')->nullable();
            $table->string('otp_expire_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');;
    }
};
