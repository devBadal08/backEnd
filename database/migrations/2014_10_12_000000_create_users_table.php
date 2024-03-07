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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            // $table->string('middle_name');
            $table->string('last_name')->nullable();
            // $table->date('dob');
            // $table->char('gender');
            $table->string('phone',12)->nullable();
            // $table->int('alt_phone');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->boolean('is_admin')->nullable();
            // $table->string('marital_status');
            // $table->float('height');
            // $table->float('weight');
            // $table->string('hobbies');
            // $table->longText('about_yourself');
            // $table->longText('about_job');
            // $table->string('degree');
            // $table->string('organization');
            // $table->int('passing_year');
            // $table->string('higher_sec');
            // $table->string('secondary');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
