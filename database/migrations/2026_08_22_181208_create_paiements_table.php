<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Élève
            |--------------------------------------------------------------------------
            */

            $table->foreignId('eleve_id')
                ->constrained('eleves')
                ->cascadeOnUpdate()
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Année scolaire du paiement
            |--------------------------------------------------------------------------
            |
            | Cette année peut être l'année active ou une année précédente.
            | On ne modifie donc jamais l'année scolaire active pour enregistrer
            | une ancienne dette.
            |
            */

            $table->foreignId('annee_scolaire_id')
                ->constrained('annee_scolaires')
                ->cascadeOnUpdate()
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Frais concerné
            |--------------------------------------------------------------------------
            */

            $table->foreignId('frais_id')
                ->constrained('frais')
                ->cascadeOnUpdate()
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Mois concerné
            |--------------------------------------------------------------------------
            |
            | Septembre à juin.
            |
            */

            $table->string('mois', 20);


            /*
            |--------------------------------------------------------------------------
            | Montants
            |--------------------------------------------------------------------------
            |
            | montant_du :
            | montant du frais au moment où le paiement est enregistré.
            |
            | montant_paye :
            | montant réellement versé par l'élève.
            |
            | restant :
            | montant restant après ce paiement.
            |
            */

            $table->decimal('montant_du', 10, 2);

            $table->decimal('montant_paye', 10, 2);

            $table->decimal('restant', 10, 2);


            /*
            |--------------------------------------------------------------------------
            | Date du paiement
            |--------------------------------------------------------------------------
            */

            $table->date('date_paiement');


            /*
            |--------------------------------------------------------------------------
            | Mode de paiement
            |--------------------------------------------------------------------------
            |
            | Exemple :
            | espèces, mobile money, virement, chèque...
            |
            */

            $table->string('mode_paiement', 30);


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

            $table->string('reference', 50)
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Traçabilité
            |--------------------------------------------------------------------------
            */

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


    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
