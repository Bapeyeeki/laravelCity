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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('city'); // Kolumna 'city' do przechowywania nazwy miasta
            $table->decimal('lat', 10, 7); // Kolumna dla szerokości geograficznej
            $table->decimal('lng', 10, 7); // Kolumna dla długości geograficznej
            $table->integer('population'); // Kolumna dla populacji
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};