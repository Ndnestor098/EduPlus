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
            $table->decimal('scored', 8, 2);
            $table->integer('course');
            $table->string('file')->nullable();
            $table->string('image')->nullable();
            $table->boolean('public')->default(0);
            $table->string('deliver');
            $table->string('subject');
            $table->unsignedBigInteger('work_type_id'); // Usar work_type_id

            $table->timestamps();

            // Definición de las claves foráneas
            $table->foreign('work_type_id')
                ->references('id')
                ->on('work_types')
                ->onDelete('cascade');
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
