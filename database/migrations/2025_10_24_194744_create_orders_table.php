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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary()->comment('訂單編號');
            $table->tinyInteger('status')->nullable()->comment('訂單狀態');
            $table->string('failure_reason')->nullable()->comment('訂單失敗原因');
            $table->dateTime('ordered_at')->nullable()->index()->comment('訂單成立日期');
            $table->unsignedInteger('total_price')->nullable()->comment('商品總價');
            $table->unsignedInteger('buyer_shipping_fee')->nullable()->comment('買家支付運費');
            $table->unsignedInteger('shopee_shipping_fee')->nullable()->comment('蝦皮補助運費');
            $table->unsignedInteger('return_shipping_fee')->nullable()->comment('退貨運費');
            $table->unsignedInteger('buyer_total_payment')->nullable()->comment('買家總支付金額');
            $table->unsignedInteger('shopee_subsidy')->nullable()->comment('蝦皮補貼金額');
            $table->unsignedInteger('shopee_coin_deduction')->nullable()->comment('蝦幣折抵');
            $table->unsignedInteger('credit_card_promotion_discount')->nullable()->comment('銀行信用卡活動折抵');
            $table->unsignedInteger('shop_voucher_discount')->nullable()->comment('賣場優惠券');
            $table->unsignedInteger('shop_coin_deduction')->nullable()->comment('賣家蝦幣回饋券');
            $table->unsignedInteger('voucher')->nullable()->comment('優惠券');
            $table->unsignedInteger('transaction_fee')->nullable()->comment('成交手續費');
            $table->unsignedInteger('other_service_fee')->nullable()->comment('其他服務費');
            $table->unsignedInteger('payment_processing_fee')->nullable()->comment('金流與系統處理費');
            $table->unsignedInteger('installment_plan')->nullable()->comment('分期付款期數');
            $table->string('city')->nullable()->comment('城市');
            $table->string('district')->nullable()->comment('行政區');
            $table->tinyInteger('shipping_option')->nullable()->comment('寄送方式');
            $table->tinyInteger('payment_method')->nullable()->comment('付款方式');
            $table->dateTime('buyer_payment_time')->nullable()->index()->comment('買家付款時間');
            $table->dateTime('actual_shipment_time')->nullable()->index()->comment('實際出貨時間');
            $table->dateTime('completed_time')->nullable()->index()->comment('訂單完成時間');
            $table->json('buyer_note')->nullable()->comment('買家備註');
            $table->json('note')->nullable()->comment('備註');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
