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
        Schema::create('scout_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedInteger('employer_id');
$table->foreign('employer_id')->references('employer_id')->on('employers')->onDelete('cascade');
            $table->unsignedInteger('teacher_id');
$table->foreign('teacher_id')->references('teacher_id')->on('teachers');
            $table->string('message', 1000)->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scout_requests');
    }
};
