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
        Schema::create('qualifications', function (Blueprint $table) {
            $table->id();
            $table->decimal('matematicas')->nullable();
            $table->decimal('fisica')->nullable();
            $table->decimal('quimica')->nullable();
            $table->decimal('historia')->nullable();
            $table->decimal('computacion')->nullable();
            $table->decimal('arte')->nullable();
            $table->decimal('ciencia')->nullable();
            $table->decimal('edu_fisica')->nullable();
            $table->decimal('ingles')->nullable();
            $table->decimal('literatura')->nullable();

            // One to One con la tabla Students
            $table->unsignedBigInteger('student_id')->unique();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualifications');
    }
};
