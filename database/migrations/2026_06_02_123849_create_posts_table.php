<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');                                    // Titre de l'article
            $table->text('body');                                       // Contenu de l'article
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK vers users, suppression en cascade
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
