@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <h1 class="text-3xl font-extrabold mb-6 text-gray-800">Mes notes</h1>

        <a href="{{ route('notes.create') }}"
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded shadow transition">
            + Nouvelle note
        </a>

        <div class="mt-8 space-y-4">
            @forelse ($notes as $note)
                <div class="border border-gray-300 p-6 rounded-lg bg-white shadow hover:shadow-lg transition">
                    <h2 class="text-2xl font-semibold text-gray-900">{{ $note->title }}</h2>
                    <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $note->content ?: 'Pas de contenu' }}</p>
                    <div class="mt-4 space-x-4">
                        <a href="{{ route('notes.edit', $note) }}"
                           class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Modifier
                        </a>
                        <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Supprimer cette note ?')"
                                    class="text-red-600 hover:text-red-800 font-medium">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 italic">Aucune note pour le moment.</p>
            @endforelse
        </div>
    </div>
@endsection
