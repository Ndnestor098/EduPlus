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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200);

            $table->text('description');
            $table->decimal('scored');
            $table->string('mtcf');
            $table->integer('course');
            $table->string('pdf')->nullable();
            $table->string('img')->nullable();

            $table->string('subject', 50);
            $table->string('deliver');

            $table->unsignedBigInteger('teacher_id')->nullable(); // Cambiar el tipo de datos según corresponda
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
