<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Passkeys\Passkeys;

// Migration Fortify — table passkeys (WebAuthn / authentification sans mot de passe)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passkeys', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Passkeys::userModel(), 'user_id')->constrained()->cascadeOnDelete();
            $table->string('name');                    // Nom affiché de la passkey
            $table->string('credential_id')->unique(); // Identifiant unique WebAuthn
            $table->json('credential');                // Données d'identification publique
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passkeys');
    }
};
