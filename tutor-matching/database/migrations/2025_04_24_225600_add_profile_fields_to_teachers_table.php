<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'specialties')) {
                $table->string('specialties', 255)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('teachers', 'introduction')) {
                $table->text('introduction')->nullable()->after('specialties');
            }
            if (!Schema::hasColumn('teachers', 'prefecture')) {
                $table->string('prefecture', 50)->nullable()->after('introduction');
            }
        });
    }
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'specialties')) {
                $table->dropColumn('specialties');
            }
            if (Schema::hasColumn('teachers', 'introduction')) {
                $table->dropColumn('introduction');
            }
            if (Schema::hasColumn('teachers', 'prefecture')) {
                $table->dropColumn('prefecture');
            }
        });
    }
};
