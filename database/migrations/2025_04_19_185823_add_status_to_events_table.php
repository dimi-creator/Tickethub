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
       // Ajout de la colonne 'status' à 'events' uniquement si elle n'existe pas
    if (!Schema::hasColumn('events', 'status')) {
        Schema::table('events', function (Blueprint $table) {
            $table->string('status')->default('draft');
        });
    }

    Schema::table('tickets', function (Blueprint $table) {
        // Vérifie si la colonne 'status' existe déjà
        if (!Schema::hasColumn('tickets', 'status')) {
            $table->string('status')->default('pending');
        }

        // Vérifie si la colonne 'paid_at' existe déjà
        if (!Schema::hasColumn('tickets', 'paid_at')) {
            $table->timestamp('paid_at')->nullable();
        }
      });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
