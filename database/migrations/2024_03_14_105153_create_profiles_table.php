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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('user_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->char('gender')->nullable();
            $table->string('phone',10)->nullable();
            $table->string('alt_phone',10)->nullable();
            $table->string('email')->unique();
            $table->string('village')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('marital_status')->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('hobbies')->nullable();
            $table->longText('about_self')->nullable();
            $table->longText('about_job')->nullable();
            $table->json('education')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
