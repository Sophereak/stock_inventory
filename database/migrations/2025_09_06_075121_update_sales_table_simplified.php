<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if the table exists and has old columns
        if (Schema::hasTable('sales')) {
            // Drop foreign key constraint first
            Schema::table('sales', function (Blueprint $table) {
                // Check if the column exists before trying to modify
                if (Schema::hasColumn('sales', 'inventory_id')) {
                    $table->dropForeign(['inventory_id']);
                }
                
                // Drop old columns if they exist
                if (Schema::hasColumn('sales', 'sale_number')) {
                    $table->dropColumn('sale_number');
                }
                if (Schema::hasColumn('sales', 'customer_name')) {
                    $table->dropColumn('customer_name');
                }
                if (Schema::hasColumn('sales', 'customer_phone')) {
                    $table->dropColumn('customer_phone');
                }
                if (Schema::hasColumn('sales', 'notes')) {
                    $table->dropColumn('notes');
                }
                
                // Add sold_at column if it doesn't exist
                if (!Schema::hasColumn('sales', 'sold_at')) {
                    $table->timestamp('sold_at')->useCurrent()->after('total_amount');
                }
            });
        }
    }

    public function down()
    {
        // This is a destructive migration, so careful with down method
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                if (!Schema::hasColumn('sales', 'sale_number')) {
                    $table->string('sale_number')->nullable()->after('id');
                }
                if (!Schema::hasColumn('sales', 'customer_name')) {
                    $table->string('customer_name')->nullable()->after('total_amount');
                }
                if (!Schema::hasColumn('sales', 'customer_phone')) {
                    $table->string('customer_phone')->nullable()->after('customer_name');
                }
                if (!Schema::hasColumn('sales', 'notes')) {
                    $table->text('notes')->nullable()->after('customer_phone');
                }
                
                if (Schema::hasColumn('sales', 'sold_at')) {
                    $table->dropColumn('sold_at');
                }
            });
        }
    }
};