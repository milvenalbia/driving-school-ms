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
        Schema::table('course_enrolleds', function (Blueprint $table) {
            $table->string('user_id')->nullable()->change(); // Update the column type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_enrolleds', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->change(); // Revert the change if needed
        });
    }
};
