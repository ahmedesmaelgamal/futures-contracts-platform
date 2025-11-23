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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('paid')->default(0)->comment('المبلغ المدفوع');
            $table->boolean('is_graced')->default(0)->comment('هل تم منح مهله للعميل');
            $table->date('grace_date')->nullable()->comment('  تاريح الطلب + مده الامهاله = تاريخ المهله');
            $table->date('date')->nullable();
            $table->string('grace_period')->nullable()->comment('مده المهله');
            $table->tinyInteger('status')->default(0)->comment(
                '
            0 = جديد,
             1 = مسدد جزئيا,
              2 = ممهل لمده محدود,
               3 = مسدد بالكامل ,'
            );

            $table->text('notes')->nullable()->comment('ملاحظات');
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
        Schema::dropIfExists('order_statuses');
    }
};
