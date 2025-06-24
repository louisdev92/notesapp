@extends('layouts.app')

@section('content')
    @php
        // Couleurs post-it pastel (fond + bordure)
        $colors = [
            'bg-yellow-100 border-yellow-200',
            'bg-green-100 border-green-200',
            'bg-pink-100 border-pink-200',
            'bg-blue-100 border-blue-200',
            'bg-purple-100 border-purple-200',
            'bg-orange-100 border-orange-200',
            'bg-lime-100 border-lime-200',
        ];
    @endphp

    <div class="max-w-5xl mx-auto mt-12 px-6">
        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white tracking-tight">📚 Mes notes</h1>

            {{-- Bouton Ajouter --}}
            <a href="{{ route('notes.create') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-0.5 hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter une note
            </a>
        </div>

        {{-- Liste des notes --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($notes as $note)
                @php
                    $color = $colors[array_rand($colors)];
                @endphp

                <div class="rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border {{ $color }}">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $note->title }}</h2>
                    <p class="text-gray-800 whitespace-pre-line mb-4">
                        {{ $note->content ?: 'Pas de contenu' }}
                    </p>

                    <div class="flex justify-end gap-4">
                        {{-- Modifier --}}
                        <a href="{{ route('notes.edit', $note) }}"
                           class="text-indigo-600 hover:text-indigo-800 font-medium transition">
                            ✏️ Modifier
                        </a>

                        {{-- Supprimer --}}
                        <form action="{{ route('notes.destroy', $note) }}" method="POST"
                              onsubmit="return confirm('Supprimer cette note ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-medium transition">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 italic mt-8">
                    Vous n'avez encore rédigé aucune note.
                </div>
            @endforelse
        </div>
    </div>
@endsection
