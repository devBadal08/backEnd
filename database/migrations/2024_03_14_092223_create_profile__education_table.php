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
        Schema::create('profile_education', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('profile_id'); // Foreign key to the profiles table
            // $table->foreign('profile_id')->references('id')->on('user'); // Foreign key to the profiles table
            $table->string('type');
            $table->string('organization_name');
            $table->string('degree')->nullable();
            $table->date('start_year')->nullable();
            $table->date('end_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_education');
    }
};
