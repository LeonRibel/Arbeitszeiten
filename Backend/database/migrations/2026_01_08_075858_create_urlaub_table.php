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
        Schema::create('urlaub', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mitarbeiter_id'); // Mitarbeiter ID
            $table->dateTime('start'); // Startdatum der Krankheit
            $table->dateTime('ende');   // Enddatum der Krankheit
            $table->enum('status', ['angefragt', 'genehmigt', 'abgelehnt'])->default('angefragt'); // ENUM Status
            $table->integer('tage'); // Anzahl der Tage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urlaub');
    }
};
