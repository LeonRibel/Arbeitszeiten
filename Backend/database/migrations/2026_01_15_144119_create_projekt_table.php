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
        Schema::create('projekt', function (Blueprint $table) {
            $table->id();
            $table->string('aufgabe');
            $table->string('kunde_id');
            $table->string('status');
            $table->decimal('gesamt', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projekt');
    }
};
