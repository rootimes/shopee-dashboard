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
            $table->string('product_id')->index()->comment('商品編號');
            $table->string('order_id')->comment('訂單編號');
            $table->unsignedInteger('sales_price')->nullable()->comment('商品活動價格');
            $table->unsignedInteger('quantity')->nullable()->comment('數量');
            $table->timestamps();

            $table->primary(['product_id', 'order_id']);
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
