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
         Schema::table('shipment_details', function (Blueprint $table) {
            // جعل الحقول التالية تقبل NULL
            $table->string('scheduled_date')->nullable()->change();
            $table->string('scheduled_time')->nullable()->change();
            $table->string('payment_method')->nullable()->change();
            $table->string('summary')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_details', function (Blueprint $table) {
            // إعادة الحقول للوضع السابق (لا تقبل NULL)
            $table->string('scheduled_date')->nullable(false)->change();
            $table->string('scheduled_time')->nullable(false)->change();
            $table->string('payment_method')->nullable(false)->change();
            $table->string('summary')->nullable(false)->change();
        });
    }
};
