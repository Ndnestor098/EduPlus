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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->float('matematicas')->nullable();
            $table->float('ingles')->nullable();
            $table->float("fisica")->nullable();
            $table->float("ciencia")->nullable();
            $table->float("computacion")->nullable();
            $table->float("literatura")->nullable();
            $table->float("arte")->nullable();
            $table->float("historia")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
