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
        Schema::create('trans_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_customer');
            $table->string('order_code', 50)->unique();
            $table->date('order_date');
            $table->date('order_end_date')->nullable();
            $table->tinyInteger('order_status')->default(0); // 0=pending, 1=process, 2=done, 3=delivered
            $table->integer('order_pay')->nullable();
            $table->integer('order_change')->nullable();
            $table->integer('total')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_customer')->references('id')->on('customers')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_order');
    }
};
