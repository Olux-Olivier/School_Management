<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Eleve extends Model
{
    use HasFactory;

    protected $fillable = [

        'matricule',

        'nom',
        'postnom',
        'prenom',

        'sexe',

        'date_naissance',
        'lieu_naissance',

        'adresse',
        'telephone',

        'photo',

        'actif',

        'created_by',
        'updated_by',

    ];


    protected $casts = [

        'date_naissance' => 'date',

        'actif' => 'boolean',

    ];


    protected $appends = [

        'nom_complet',
        'sexe_libelle',
        'statut_libelle',

    ];


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    protected function nomComplet(): Attribute
    {
        return Attribute::make(

            get: fn () => trim(

                ($this->nom ?? '') . ' ' .
                ($this->postnom ?? '') . ' ' .
                ($this->prenom ?? '')

            ),

        );
    }


    protected function sexeLibelle(): Attribute
    {
        return Attribute::make(

            get: fn () => match ($this->sexe) {

                'M' => 'Masculin',

                'F' => 'Féminin',

                default => 'Non renseigné',

            },

        );
    }


    protected function statutLibelle(): Attribute
    {
        return Attribute::make(

            get: fn () => $this->actif
                ? 'Actif'
                : 'Inactif',

        );
    }


    /*
    |--------------------------------------------------------------------------
    | Relations de traçabilité
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relations avec les autres modèles
    |--------------------------------------------------------------------------
    */

    public function inscriptions()
    {
        return $this->hasMany(
            Inscription::class
        );
    }
}
