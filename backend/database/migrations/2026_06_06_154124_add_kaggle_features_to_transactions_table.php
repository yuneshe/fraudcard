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
        Schema::table('transactions', function (Blueprint $table) {
            // Add Kaggle dataset features
            $table->integer('Time')->nullable()->after('transaction_time');
            $table->decimal('V1', 10, 4)->nullable()->after('Time');
            $table->decimal('V2', 10, 4)->nullable()->after('V1');
            $table->decimal('V3', 10, 4)->nullable()->after('V2');
            $table->decimal('V4', 10, 4)->nullable()->after('V3');
            $table->decimal('V5', 10, 4)->nullable()->after('V4');
            $table->decimal('V6', 10, 4)->nullable()->after('V5');
            $table->decimal('V7', 10, 4)->nullable()->after('V6');
            $table->decimal('V8', 10, 4)->nullable()->after('V7');
            $table->decimal('V9', 10, 4)->nullable()->after('V8');
            $table->decimal('V10', 10, 4)->nullable()->after('V9');
            $table->decimal('V11', 10, 4)->nullable()->after('V10');
            $table->decimal('V12', 10, 4)->nullable()->after('V11');
            $table->decimal('V13', 10, 4)->nullable()->after('V12');
            $table->decimal('V14', 10, 4)->nullable()->after('V13');
            $table->decimal('V15', 10, 4)->nullable()->after('V14');
            $table->decimal('V16', 10, 4)->nullable()->after('V15');
            $table->decimal('V17', 10, 4)->nullable()->after('V16');
            $table->decimal('V18', 10, 4)->nullable()->after('V17');
            $table->decimal('V19', 10, 4)->nullable()->after('V18');
            $table->decimal('V20', 10, 4)->nullable()->after('V19');
            $table->decimal('V21', 10, 4)->nullable()->after('V20');
            $table->decimal('V22', 10, 4)->nullable()->after('V21');
            $table->decimal('V23', 10, 4)->nullable()->after('V22');
            $table->decimal('V24', 10, 4)->nullable()->after('V23');
            $table->decimal('V25', 10, 4)->nullable()->after('V24');
            $table->decimal('V26', 10, 4)->nullable()->after('V25');
            $table->decimal('V27', 10, 4)->nullable()->after('V26');
            $table->decimal('V28', 10, 4)->nullable()->after('V27');
            $table->renameColumn('amount', 'Amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'Time', 'V1', 'V2', 'V3', 'V4', 'V5', 'V6', 'V7', 'V8', 'V9',
                'V10', 'V11', 'V12', 'V13', 'V14', 'V15', 'V16', 'V17', 'V18', 'V19',
                'V20', 'V21', 'V22', 'V23', 'V24', 'V25', 'V26', 'V27', 'V28'
            ]);
            $table->renameColumn('Amount', 'amount');
        });
    }
};
