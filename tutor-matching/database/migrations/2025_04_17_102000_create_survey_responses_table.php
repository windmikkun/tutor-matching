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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id('response_id');
            $table->unsignedInteger('survey_id');
$table->foreign('survey_id')->references('survey_id')->on('surveys');
            $table->unsignedInteger('user_id');
$table->foreign('user_id')->references('user_id')->on('users');
            $table->text('answer');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
