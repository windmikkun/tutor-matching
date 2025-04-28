<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->string('lesson_type')->nullable()->comment('個別/集団');
            $table->integer('student_count')->nullable()->comment('生徒数');
            $table->string('student_demographics')->nullable()->comment('生徒層');
            $table->unsignedInteger('hourly_rate')->nullable()->comment('時給');
            $table->json('profile_images')->nullable()->comment('プロフィール画像（複数）');
        });
    }
    public function down(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn(['lesson_type', 'student_count', 'student_demographics', 'hourly_rate', 'profile_images']);
        });
    }
};
