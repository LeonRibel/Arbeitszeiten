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
        Schema::create('projekte', function (Blueprint $table) {
            $table->id();
            $table->string('aufgabe');
            $table->unsignedBigInteger('kunde_id')->nullable();
            $table->enum('status', ['aktiv', 'abgeschlossen'])->default('aktiv');
            $table->decimal('gesamt', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('kunde_id')->references('id')->on('kunden')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projekte');
    }
};
