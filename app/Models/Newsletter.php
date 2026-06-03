<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Modèle Eloquent représentant une newsletter stockée en base
class Newsletter extends Model
{
    // Colonnes autorisées à l'assignation de masse (create / update)
    protected $fillable = ['subject', 'body', 'sent_at'];

    // Convertit automatiquement sent_at en instance Carbon pour les comparaisons de dates
    protected $casts    = ['sent_at' => 'datetime'];
}
