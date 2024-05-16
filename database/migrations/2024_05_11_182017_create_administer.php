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
        Schema::create('administer', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 70);
            $table->string('cellphone');
            $table->decimal('salary');
            $table->date('started');
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administer');
    }
};
