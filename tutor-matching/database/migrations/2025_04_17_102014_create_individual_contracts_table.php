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
        Schema::create('individual_contracts', function (Blueprint $table) {
            $table->increments('contract_id');
            $table->unsignedInteger('teacher_id');
            $table->foreign('teacher_id')->references('teacher_id')->on('teachers');
            $table->unsignedInteger('employer_id');
            $table->foreign('employer_id')->references('employer_id')->on('employers')->onDelete('cascade');
            $table->unsignedInteger('job_id');
            $table->foreign('job_id')->references('job_id')->on('individual_jobs');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_contracts');
    }
};
