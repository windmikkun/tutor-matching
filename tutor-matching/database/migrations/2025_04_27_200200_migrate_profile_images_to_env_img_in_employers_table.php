<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->json('env_img')->nullable()->comment('教室等の画像（複数）');
        });
        // 既存データをコピー
        DB::statement('UPDATE employers SET env_img = profile_images WHERE profile_images IS NOT NULL');
        // 旧カラムを削除
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn('profile_images');
        });
    }
    public function down(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->json('profile_images')->nullable()->comment('教室等の画像（複数）');
        });
        DB::statement('UPDATE employers SET profile_images = env_img WHERE env_img IS NOT NULL');
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn('env_img');
        });
    }
};
