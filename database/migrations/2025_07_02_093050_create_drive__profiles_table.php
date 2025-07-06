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
            $table->unsignedBigInteger('driver_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('gender');
            $table->string('city');
            $table->date('birth_date');
            $table->string('national_ID',10)->unique();
            $table->string('phone', 10)->unique();
            $table->string('documents');
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
