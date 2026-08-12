<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {

            $table->id();

            // Élève concerné
            $table->foreignId('eleve_id')
                ->constrained('eleves')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Année scolaire
            $table->foreignId('annee_scolaire_id')
                ->constrained('annees_scolaires')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Classe
            $table->foreignId('classe_id')
                ->constrained('classes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Date de l'inscription
            $table->date('date_inscription');

            // Montant de l'inscription
            $table->decimal('montant', 10, 2);

            // Statut de l'inscription
            $table->boolean('actif')
                ->default(true);

            // Traçabilité
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Un élève ne peut avoir qu'une seule inscription
            | pour une même année scolaire
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'eleve_id',
                'annee_scolaire_id'
            ]);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
