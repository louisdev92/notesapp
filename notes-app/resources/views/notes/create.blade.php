@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-4 text-gray-800">Créer une nouvelle note</h1>

        <p class="text-gray-600 mb-6">
            Utilisez le formulaire ci-dessous pour rédiger une nouvelle note. Vous pouvez y inscrire un titre explicite ainsi que le contenu détaillé. Toutes vos notes sont privées et accessibles uniquement par vous.
        </p>

        {{-- Bouton de retour --}}
        <a href="{{ route('notes.index') }}" class="inline-block mb-6 text-blue-600 hover:underline">
            ← Retour à la liste des notes
        </a>

        {{-- Formulaire --}}
        <form action="{{ route('notes.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Titre --}}
            <div>
                <label for="title" class="block font-semibold text-gray-700">Titre</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                    required
                >
                @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Contenu --}}
            <div>
                <label for="content" class="block font-semibold text-gray-700">Contenu</label>
                <textarea
                    name="content"
                    id="content"
                    rows="6"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                    required
                >{{ old('content') }}</textarea>
                @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bouton de soumission --}}
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded">
                    Enregistrer la note
                </button>
            </div>
        </form>
    </div>
@endsection
