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
        Schema::create('percentages', function (Blueprint $table) {
            $table->id();
            $table->decimal('percentage');
            $table->integer('course');

            $table->unsignedBigInteger('work_type_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();

            $table->foreign('work_type_id')->references('id')->on('work_types')->onDelete('set null');

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('percentages');
    }
};
