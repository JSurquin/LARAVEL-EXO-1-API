<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

// Trait partagé — règles de validation communes pour les mots de passe
trait PasswordValidationRules
{
    /**
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        // required + règles Laravel Password::default() + confirmation obligatoire
        return ['required', 'string', Password::default(), 'confirmed'];
    }
}
