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
        Schema::create('drive__profiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // $table->unsignedBigInteger('driver_id')->unique();
            $table->unsignedBigInteger('driver_id'); 
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('city')->nullable();
            $table->string('national_ID',10)->unique();
            $table->string('phone', 10)->unique();
            $table->string('documents')->nullable();
            $table->date('birth_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drive__profiles');
    }
};
