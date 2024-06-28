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
        /** could add index: valuation_term_id, student_evaluator_id, rated_student_id, project_id */
        Schema::create('valuations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('valuation_term_id')->constrained();
            $table->foreignId('student_evaluator_id')->constrained('users');
            $table->foreignId('rated_student_id')->constrained('users');
            $table->foreignId('project_id')->constrained();
            $table->boolean('self_evaluation')->default(false);
            $table->string('mark1');
            $table->string('mark2');
            $table->string('mark3');
            $table->string('mark4');
            $table->string('mark5');
            $table->string('mark6');
            $table->string('mark7');
            $table->text('extra_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valuations');
    }
};
