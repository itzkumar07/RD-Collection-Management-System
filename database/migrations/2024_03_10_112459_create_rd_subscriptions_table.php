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
        Schema::create('rd_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref_no')->nullable();
            $table->foreignUuid('user_id')->nullable()->references('id')->on('users');
            $table->foreignUuid('rd_plan_id')->nullable()->references('id')->on('rd_plans');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('instalment_amount', 10, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rd_subscriptions');
    }
};
