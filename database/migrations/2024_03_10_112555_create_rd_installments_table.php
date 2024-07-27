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
        Schema::create('rd_installments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rd_subscription_id')->nullable()->references('id')->on('rd_subscriptions');
            $table->string('method')->nullable();
            $table->string('gateway')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('meta_data')->nullable();
            $table->date('date')->nullable()->nullable();
            $table->text('remark')->nullable()->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rd_installments');
    }
};
