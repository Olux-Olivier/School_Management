<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'niveau',
        'section',
        'option',
        'variante',
        'actif',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'niveau' => 'integer',
            'actif' => 'boolean',
        ];
    }

    protected $appends = [
        'niveau_libelle',
        'nom_complet',
        'statut_libelle',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Utilisateur ayant créé la classe
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    /**
     * Utilisateur ayant modifié la classe
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function inscriptions()
    {
        return $this->hasMany(
            Inscription::class
        );
    }

    public function frais()
    {
        return $this->hasMany(
            Frais::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Libellé du niveau
     */
    protected function niveauLibelle(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->niveau) {
                0 => 'Maternelle',
                1 => 'Primaire',
                2 => 'Secondaire',
                3 => 'Humanités',
                default => 'Inconnu',
            }
        );
    }


    /**
     * Nom complet de la classe
     */
    protected function nomComplet(): Attribute
    {
       return Attribute::make(
            get: function () {
                $nom = $this->nom;

                if ($this->niveau == 3) {
                    // Humanités : nom + option
                    $nom .= ' ' . $this->option;
                } else {
                    // Autres sections : nom + section
                    $nom .= ' ' . $this->section;
                }

                // Ajouter la variante si elle existe
                if (!empty($this->variante)) {
                    $nom .= ' ' . $this->variante;
                }

                return trim($nom);
            }
        );
    }


    /**
     * Libellé du statut
     */
    protected function statutLibelle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->actif ? 'Actif' : 'Inactif'
        );
    }
}
