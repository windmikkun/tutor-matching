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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('nearest_station', 100)->nullable()->after('address');
        });
        Schema::table('employers', function (Blueprint $table) {
            $table->string('nearest_station', 100)->nullable()->after('address');
            $table->string('recruiting_subject', 100)->nullable()->after('nearest_station');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('nearest_station');
        });
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn(['nearest_station', 'recruiting_subject']);
        });
    }
};
