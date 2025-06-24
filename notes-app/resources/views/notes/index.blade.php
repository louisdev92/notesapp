@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <h1 class="text-3xl font-extrabold mb-6 text-gray-800">📚 Mes notes</h1>

        {{-- Bouton créer une note --}}
        <a href="{{ route('notes.create') }}"
           class="inline-block bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
            ➕ Nouvelle note
        </a>

        {{-- Liste des notes --}}
        <div class="mt-8 space-y-6">
            @forelse ($notes as $note)
                <div class="border border-gray-200 p-6 rounded-xl bg-white shadow-sm hover:shadow-md transition">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $note->title }}</h2>
                    <p class="mt-3 text-gray-700 whitespace-pre-line">
                        {{ $note->content ?: 'Pas de contenu' }}
                    </p>

                    <div class="mt-4 flex space-x-4">
                        {{-- Modifier --}}
                        <a href="{{ route('notes.edit', $note) }}"
                           class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition">
                            ✏️ Modifier
                        </a>

                        {{-- Supprimer --}}
                        <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline-block"
                              onsubmit="return confirm('Supprimer cette note ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center text-red-600 hover:text-red-800 font-medium transition">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic mt-6">Vous n'avez encore rédigé aucune note.</p>
            @endforelse
        </div>
    </div>
@endsection
