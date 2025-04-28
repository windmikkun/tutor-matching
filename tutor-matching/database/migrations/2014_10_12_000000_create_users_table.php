<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('user_type', 50);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->string('avatar', 255)->default('users/default.png');
            $table->string('messenger_color', 20)->default('#2180f3');
            $table->boolean('dark_mode')->default(false);
            $table->enum('active_status', ['online','offline'])->default('offline');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('users');
    }
}
