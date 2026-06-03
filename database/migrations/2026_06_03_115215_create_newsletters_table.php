<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration : création de la table newsletters
return new class extends Migration
{
    /**
     * Applique la migration — crée la table newsletters.
     */
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();                           // Clé primaire auto-incrémentée
            $table->string('subject');              // Sujet de la newsletter (objet de l'e-mail)
            $table->text('body');                   // Corps HTML/texte de la newsletter
            $table->timestamp('sent_at')->nullable(); // Date d'envoi effectif — null tant que non envoyée
            $table->timestamps();                   // created_at et updated_at automatiques
        });
    }

    /**
     * Annule la migration — supprime la table newsletters.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
