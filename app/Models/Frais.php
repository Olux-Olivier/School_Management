<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frais extends Model
{
    use HasFactory;

    protected $fillable = [
        'intitule',
        'montant',
        'section',
        'classe_id',
        'annee_scolaire_id',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Classe
    |--------------------------------------------------------------------------
    */

    public function classe()
    {
        return $this->belongsTo(
            Classe::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Année scolaire
    |--------------------------------------------------------------------------
    */

    public function anneeScolaire()
    {
        return $this->belongsTo(
            AnneeScolaire::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Utilisateur ayant créé
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Utilisateur ayant modifié
    |--------------------------------------------------------------------------
    */

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Paiements
    |--------------------------------------------------------------------------
    */

    public function paiements()
    {
        return $this->hasMany(
            Paiement::class,
            'frais_id'
        );
    }
}
