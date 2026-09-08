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
        Schema::create('historique_paiements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paiement_id')
                ->constrained('paiements')
                ->cascadeOnDelete();

            $table->decimal('montant', 12, 2);

            $table->date('date_paiement');

            /*
            |--------------------------------------------------------------------------
            | Mode de paiement
            |--------------------------------------------------------------------------
            |
            | Exemple : Espèces, Chèque, Virement, Carte bancaire, etc.
            |
            */
            $table->string('mode_paiement', 50);

             /*
            |--------------------------------------------------------------------------
            | Référence automatique du paiement
            |--------------------------------------------------------------------------
            |
            | Exemple :
            |
            | ESP-2026-00001-HUM
            | ESP-2026-00002-HUM
            | ESP-2026-00001-SEC
            |
            | Le compteur sera géré par année scolaire + section.
            |
            */
            $table->string('reference', 100)->unique();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_paiements');
    }
};
