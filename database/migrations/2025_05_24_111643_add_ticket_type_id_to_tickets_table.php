<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('ticket_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('unit_price', 10, 2)->nullable(); // Prix unitaire payé pour ce billet
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['ticket_type_id']);
            $table->dropColumn(['ticket_type_id', 'unit_price']);
        });
    }
};
