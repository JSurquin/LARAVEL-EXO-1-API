<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration : création de la table subscribers (liste d'abonnés)
return new class extends Migration
{
    /**
     * Applique la migration — crée la table subscribers.
     */
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();                              // Clé primaire auto-incrémentée
            $table->string('email')->unique();         // Adresse e-mail — unique pour éviter les doublons
            $table->string('name')->nullable();        // Nom de l'abonné (facultatif)
            $table->timestamps();                      // created_at et updated_at automatiques
        });
    }

    /**
     * Annule la migration — supprime la table subscribers.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
