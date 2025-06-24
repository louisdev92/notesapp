@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Modifier la note</h1>

        <form action="{{ route('notes.update', $note) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="block">Titre</label>
                <input type="text" name="title" id="title" value="{{ $note->title }}" class="w-full border px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="content" class="block">Contenu</label>
                <textarea name="content" id="content" class="w-full border px-3 py-2" rows="5" required>{{ $note->content }}</textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour</button>
        </form>
    </div>
@endsection
