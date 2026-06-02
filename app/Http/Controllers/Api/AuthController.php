<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Contrôleur d'authentification API — login/logout via tokens Sanctum
class AuthController extends Controller
{
    // POST /api/auth/login — vérifie les identifiants et retourne un token Bearer
    public function login(Request $request)
    {
        // Validation des champs email et password
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Tentative de connexion avec le guard web (session)
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        // Génère un token Sanctum plain-text pour les requêtes API suivantes
        $token = $request->user()->createToken('api')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    // POST /api/auth/logout — révoque le token Bearer courant (route protégée)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté']);
    }
}
