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
            $table->string('first_name', 50)->nullable()->after('user_id');
            $table->string('last_name', 50)->nullable()->after('first_name');
            // nameカラムは将来的に削除可能だが、既存データ移行後に検討
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};
