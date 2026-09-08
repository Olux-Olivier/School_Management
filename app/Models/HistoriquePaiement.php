<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriquePaiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'paiement_id',
        'montant',
        'date_paiement',
        'mode_paiement',
        'reference',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'date_paiement' => 'date',
        ];
    }

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
