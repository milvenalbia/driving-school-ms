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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreignId('created_by')->constrained('users');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middle')->nullable();           
            $table->string('gender')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->date('birth_date')->nullable();
            $table->string('address')->nullable();
            $table->integer('driving_experience')->nullable();
            $table->string('image_path')->nullable();
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
