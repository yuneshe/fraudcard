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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 12, 2);
            $table->string('merchant');
            $table->dateTime('transaction_time');
            $table->float('risk_score')->default(0);
            $table->boolean('fraud_status')->default(false);
            $table->integer('merchant_category')->default(1);
            $table->float('location_distance')->default(0);
            $table->integer('card_age_days')->default(0);
            $table->integer('transaction_frequency')->default(0);
            $table->timestamps();
            
            $table->index('transaction_id');
            $table->index('fraud_status');
            $table->index('risk_score');
            $table->index('transaction_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
