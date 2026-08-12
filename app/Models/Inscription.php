<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'annee_scolaire_id',
        'classe_id',
        'date_inscription',
        'montant',
        'actif',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date_inscription' => 'date',
            'montant' => 'decimal:2',
            'actif' => 'boolean',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    // Élève inscrit
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }


    // Année scolaire
    public function anneeScolaire()
    {
        return $this->belongsTo(
            AnneeScolaire::class,
            'annee_scolaire_id'
        );
    }


    // Classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }


    // Utilisateur ayant créé l'inscription
    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    // Utilisateur ayant modifié l'inscription
    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}
