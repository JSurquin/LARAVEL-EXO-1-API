<x-app-layout title="Démo composants">
    <x-alert type="success">Bravo, vos composants fonctionnent !</x-alert>
    <x-alert type="error">Exemple d'erreur</x-alert>
    <x-card title="Ma première card">
        <p>Contenu avec un badge : <x-badge color="green">Actif</x-badge></p>
        <div class="mt-4 flex gap-2">
            <x-button variant="primary">Valider</x-button>
            <x-button variant="danger">Supprimer</x-button>
        </div>
    </x-card>
</x-app-layout>