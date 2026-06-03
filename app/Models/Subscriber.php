<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Modèle Eloquent représentant un abonné à la newsletter
class Subscriber extends Model
{
    // Permet de générer des enregistrements factices via les factories (tests / seeders)
    use HasFactory;

    // Colonnes autorisées à l'assignation de masse
    protected $fillable = ['email', 'name'];
}
