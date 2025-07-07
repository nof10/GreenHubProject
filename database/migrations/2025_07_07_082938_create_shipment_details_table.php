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
        Schema::create('shipment_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('shipment_id'); 
            $table->string('type');
            $table->string('weight');
            $table->string('size');
            $table->string('summary')->nullable();
            $table->string('destination');
            $table->string('address');
            $table->string('scheduled_date')->nullable();
            $table->string('scheduled_time')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_immediate')->default(true);
            $table->string('payment_method'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_details');
    }
};
