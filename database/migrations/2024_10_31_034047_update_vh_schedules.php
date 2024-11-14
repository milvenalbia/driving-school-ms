<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vehicle_schedulings', function (Blueprint $table) {
            // Change the 'start_date' and 'end_date' to strings
            $table->string('start_date')->nullable()->change();
            $table->string('end_date')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('vehicle_schedulings', function (Blueprint $table) {
            // Revert 'start_date' and 'end_date' back to date type
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
        });
    }
};
