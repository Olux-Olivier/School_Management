<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'annee_scolaire_id',
        'frais_id',
        'motif',
        'mois',
        'montant_du',
        'montant_paye',
        'restant',
        'date_paiement',
        'mode_paiement',
        'reference',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'montant_du' => 'decimal:2',
            'montant_paye' => 'decimal:2',
            'restant' => 'decimal:2',
            'date_paiement' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    // Élève ayant effectué le paiement
    public function eleve()
    {
        return $this->belongsTo(
            Eleve::class,
            'eleve_id'
        );
    }

    // Année scolaire concernée
    public function anneeScolaire()
    {
        return $this->belongsTo(
            AnneeScolaire::class,
            'annee_scolaire_id'
        );
    }

    // Frais payé
    public function frais()
    {
        return $this->belongsTo(
            Frais::class,
            'frais_id'
        );
    }

    // Utilisateur ayant enregistré le paiement
    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    // Utilisateur ayant modifié le paiement
    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

}
