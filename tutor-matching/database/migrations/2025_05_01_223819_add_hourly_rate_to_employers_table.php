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
        Schema::table('employers', function (Blueprint $table) {
            $table->integer('hourly_rate')->nullable()->after('student_demographics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn('hourly_rate');
        });
    }
};
