@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-4 text-gray-800">Modifier la note</h1>

        <p class="text-gray-600 mb-6">
            Mettez à jour le contenu de votre note ci-dessous. Vous pouvez modifier le titre et le contenu selon vos besoins.
        </p>

        {{-- Bouton retour --}}
        <a href="{{ route('notes.index') }}" class="inline-block mb-6 text-blue-600 hover:underline">
            ← Retour à la liste des notes
        </a>

        {{-- Formulaire --}}
        <form action="{{ route('notes.update', $note) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Titre --}}
            <div>
                <label for="title" class="block font-semibold text-gray-700">Titre</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $note->title) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
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
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required
                >{{ old('content', $note->content) }}</textarea>
                @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bouton de mise à jour --}}
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md hover:shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 ease-in-out"
                >
                    Mettre à jour la note
                </button>
            </div>
        </form>
    </div>
@endsection
