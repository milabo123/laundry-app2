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
        Schema::table('trans_order', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->nullable()->change();
            $table->string('customer_name', 50)->nullable()->after('id_customer');
            $table->string('customer_phone', 15)->nullable()->after('customer_name');
            $table->text('customer_address')->nullable()->after('customer_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_order', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->nullable(false)->change();
            $table->dropColumn(['customer_name', 'customer_phone', 'customer_address']);
        });
    }
};
