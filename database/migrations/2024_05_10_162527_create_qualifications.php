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
            $table->integer('matematicas')->nullable();
            $table->integer('fisica')->nullable();
            $table->integer('quimica')->nullable();
            $table->integer('historia')->nullable();
            $table->integer('informatica')->nullable();
            $table->integer('arte')->nullable();
            $table->integer('edu_fisica')->nullable();
            $table->integer('ingles')->nullable();
            $table->integer('lenguaje')->nullable();

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
