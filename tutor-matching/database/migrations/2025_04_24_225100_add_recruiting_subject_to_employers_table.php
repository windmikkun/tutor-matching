<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            if (!Schema::hasColumn('employers', 'recruiting_subject')) {
                $table->string('recruiting_subject', 100)->nullable();
            }
        });
    }
    public function down(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            if (Schema::hasColumn('employers', 'recruiting_subject')) {
                $table->dropColumn('recruiting_subject');
            }
        });
    }
};
