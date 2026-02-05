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
        Schema::create('fehlzeiten', function (Blueprint $table) {
            $table->increments('fehlzeiten_id'); // Primärschlüssel AUTO_INCREMENT
            $table->unsignedBigInteger('mitarbeiter_id'); // Mitarbeiter ID
            $table->dateTime('Kstart'); // Startdatum der Krankheit
            $table->dateTime('Kende');   // Enddatum der Krankheit
            $table->enum('status', ['nicht eingereicht', 'eingereicht'])->default('nicht eingereicht'); // ENUM Status
            $table->integer('tage'); // Anzahl der Tage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fehlzeiten');
    }
};
