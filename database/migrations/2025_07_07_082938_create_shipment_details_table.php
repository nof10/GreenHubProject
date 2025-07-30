<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('shipment_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('shipment_id'); 
            $table->string('type');
            $table->string('weight');
            $table->string('size');
            $table->string('destination');
            $table->string('address');
            $table->boolean('is_immediate')->default(true);
            $table->string('scheduled_date');
            $table->string('scheduled_time');
            $table->string('status');
            $table->string('payment_method');
            $table->string('summary');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('shipment_details');
    }
};