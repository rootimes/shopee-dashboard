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
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('tmp_id')->nullable()->index()->comment('臨時ID');
            $table->string('name')->nullable()->comment('商品名稱');
            $table->string('shopee_name')->nullable()->comment('蝦皮商品規格名稱');
            $table->string('image_url')->nullable()->comment('商品圖片網址');
            $table->decimal('cost_price', 10, 2)->nullable()->comment('人民幣成本價');
            $table->unsignedInteger('stock')->nullable()->comment('庫存數量');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
