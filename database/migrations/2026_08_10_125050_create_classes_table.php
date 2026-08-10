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
        Schema::create('classes', function (Blueprint $table) {

            $table->id();

            // Nom de la classe : 1ère, 2ème, 3ème, 4ème...
            $table->string('nom');

            // 0 = Maternelle
            // 1 = Primaire
            // 2 = Secondaire
            // 3 = Humanités
            $table->unsignedTinyInteger('niveau');

            // Maternelle, Primaire, Secondaire, Humanités
            $table->string('section');

            // Utilisé uniquement pour les Humanités
            // Exemple : Commercial, Pédagogique...
            $table->string('option')->nullable();

            // Classe active ou inactive
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
