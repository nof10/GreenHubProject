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
        Schema::create('drivers', function (Blueprint $table) {

           $table->id();
            $table->string('face_id')->unique();
            $table->string('national_id',10)->unique();
            $table->unsignedBigInteger('vehical_id')->unique();
            $table->string('name');
            $table->string('phone', 10)->unique();
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
