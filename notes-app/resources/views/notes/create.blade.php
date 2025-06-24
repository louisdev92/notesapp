@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Créer une note</h1>

        <form action="{{ route('notes.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="title" class="block">Titre</label>
                <input type="text" name="title" id="title" class="w-full border px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="content" class="block">Contenu</label>
                <textarea name="content" id="content" class="w-full border px-3 py-2" rows="5" required></textarea>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Enregistrer</button>
        </form>
    </div>
@endsection
