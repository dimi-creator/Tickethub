<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Standard, VIP, Super VIP
            $table->text('description')->nullable(); // Description du type de billet
            $table->decimal('price', 10, 2); // Prix de ce type de billet
            $table->integer('total_quantity'); // Nombre total de billets de ce type
            $table->integer('available_quantity'); // Nombre de billets disponibles
            $table->integer('sort_order')->default(0); // Ordre d'affichage
            $table->boolean('is_active')->default(true); // Si ce type est disponible à la vente
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};

