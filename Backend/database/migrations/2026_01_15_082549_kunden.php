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
        Schema::create('kunden', function (Blueprint $table) {
            $table->id();
            $table->string('firmenname');
            $table->string('ansprechpartner');
            $table->string('email');
            $table->string('ort');
            $table->string('straße');
            $table->string('land')->default('DE');
            $table->string('plz');
            $table->string('hausnummer');
            $table->string('ust_id')->nullable();
            $table->string('handelsregister_id')->nullable();
            $table->string('telefon');
            $table->enum('kundenart', ['B2B', 'B2C'])->default('B2B');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunden');
    }
};
