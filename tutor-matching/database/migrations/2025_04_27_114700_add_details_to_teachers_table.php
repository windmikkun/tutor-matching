<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('education', 255)->nullable()->after('prefecture'); // 学歴
            $table->string('current_school', 255)->nullable()->after('education'); // 在学中の学校
            $table->text('trial_lesson')->nullable()->after('current_school'); // プレ授業欄
            $table->unsignedInteger('estimated_hourly_rate')->nullable()->after('trial_lesson'); // 推定時給
        });
    }
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['education', 'current_school', 'trial_lesson', 'estimated_hourly_rate']);
        });
    }
};
