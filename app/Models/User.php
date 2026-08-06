<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'postnom',
        'prenom',
        'sexe',
        'telephone',
        'email',
        'username',
        'password',
        'type',
        'photo',
        'actif',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    protected $appends = [
        'nom_complet',
        'sexe_libelle',
        'statut_libelle',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    // Utilisateur ayant créé cet utilisateur
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Utilisateur ayant modifié cet utilisateur
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Utilisateurs créés par cet utilisateur
    public function usersCreated()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    // Utilisateurs modifiés par cet utilisateur
    public function usersUpdated()
    {
        return $this->hasMany(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesseurs
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
            get: fn () => $this->sexe == 'M' ? 'Masculin' : 'Féminin'
        );
    }

    protected function statutLibelle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->actif ? 'Actif' : 'Inactif'
        );
    }
}
