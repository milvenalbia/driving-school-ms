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
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['barangay', 'municipalilty', 'province','region']);
        
            // Adding nullable foreign key columns
            $table->foreignId('region')->nullable()->constrained('regions');
            $table->foreignId('province')->nullable()->constrained('provinces');
            $table->foreignId('municipality')->nullable()->constrained('municipalities');
            $table->foreignId('barangay')->nullable()->constrained('barangays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
