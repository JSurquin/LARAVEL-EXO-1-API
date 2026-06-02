<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

// Action Fortify : création d'un nouvel utilisateur à l'inscription
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules; // Réutilise les règles de mot de passe

    /**
     * @param  array<string, string>  $input
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class), // Email unique en base
            ],
            'role' => ['required', 'string', 'in:user,admin'],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'], // Hashé automatiquement via cast 'hashed'
            'role' => 'user', // Toujours 'user' à l'inscription (sécurité)
        ]);
    }
}
