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
        Schema::create('client__profiles', function (Blueprint $table) {

            $table->id();

            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
            $table->string('phone')->unique();
           
        });
    }




    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client__profiles');
    }
};
