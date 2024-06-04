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
        Schema::create('work_students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 50);

            $table->integer('course');
            $table->decimal('qualification')->nullable();
            $table->string('file')->nullable();
            $table->string('image')->nullable();

            $table->unsignedBigInteger('student_id'); // Cambiar el tipo de datos según corresponda
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->unsignedBigInteger('work_id');
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_students');
    }
};
