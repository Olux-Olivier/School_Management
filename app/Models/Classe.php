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
            get: fn () => $this->niveau == 3
                ? trim($this->nom . ' ' . $this->option)
                : trim($this->nom . ' ' . $this->section)
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
