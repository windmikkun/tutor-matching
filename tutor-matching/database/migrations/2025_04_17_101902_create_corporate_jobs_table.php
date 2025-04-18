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
        Schema::create('corporate_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
$table->unsignedBigInteger('employer_id');
$table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
$table->string('title', 100);
$table->text('description')->nullable();
$table->text('subjects')->nullable();
$table->text('target_grades')->nullable();
$table->text('location')->nullable();
$table->enum('employment_type', ['full_time','part_time','contract']);
$table->enum('salary_type', ['hourly','monthly','yearly']);
$table->decimal('salary_min', 10, 2)->nullable();
$table->decimal('salary_max', 10, 2)->nullable();
$table->text('benefits')->nullable();
$table->text('requirements')->nullable();
$table->date('application_deadline')->nullable();
$table->date('start_date')->nullable();
$table->enum('status', ['draft','active','closed'])->default('draft');
$table->timestamp('created_at')->useCurrent();
$table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporate_jobs');
    }
};
