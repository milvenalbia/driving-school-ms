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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->string('user_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middle')->nullable();
            $table->string('email')->unique();
            $table->string('gender')->nullable();
            $table->string('phone_number');
            $table->date('birth_date')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('district')->nullable();
            $table->string('municipalilty')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('birth_palce')->nullable();
            $table->string('image_path')->nullable();
            $table->foreignId('assigned_instructor')->nullable()->constrained('instructors');
            $table->integer('course_attendance')->nullable();
            $table->boolean('course_completed')->nullable();
            $table->boolean('theoretical_test')->nullable(); 
            $table->boolean('practical_test')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
