<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {

            $table->id();

            // Matricule unique de l'élève
            $table->string('matricule', 20)->unique();

            // Identité
            $table->string('nom');
            $table->string('postnom')->nullable();
            $table->string('prenom')->nullable();

            // Informations personnelles
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();

            // Coordonnées
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();

            // Photo
            $table->string('photo')->nullable();

            // Statut
            $table->boolean('actif')->default(true);

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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
