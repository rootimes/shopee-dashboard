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
        Schema::create('product_profits', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->index()->comment('商品編號');
            $table->string('order_id')->comment('訂單編號');
            $table->string('display_name')->nullable()->comment('商品顯示名稱');
            $table->unsignedInteger('sales_price')->nullable()->comment('商品活動價格');
            $table->unsignedInteger('quantity')->nullable()->comment('數量');
            $table->decimal('cost_price', 10, 2)->nullable()->comment('商品成本價格');
            $table->decimal('platform_fee', 10, 2)->nullable()->comment('平台手續費');
            $table->decimal('discount_amount', 10, 2)->nullable()->comment('折扣金額');
            $table->dateTime('order_completed_time')->nullable()->index()->comment('訂單完成時間');
            $table->decimal('total_profit', 10, 2)->nullable()->comment('總利潤');

            $table->timestamps();

            $table->unique(['product_id', 'order_id']);
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_profits');
    }
};
