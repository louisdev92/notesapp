@extends('layouts.app')

@section('content')
    @php
        $colors = [
            'bg-yellow-100 border-yellow-400',
            'bg-green-100 border-green-400',
            'bg-pink-100 border-pink-400',
            'bg-blue-100 border-blue-400',
            'bg-purple-100 border-purple-400',
            'bg-orange-100 border-orange-400',
            'bg-lime-100 border-lime-400',
        ];
    @endphp

    <style>
        /* Conteneur principal en relative pour le positionnement absolu */
        #notes-container {
            position: relative;
            min-height: 80vh; /* assez de place */
            padding: 20px;
        }

        /* Style des post-it */
        .note-card {
            position: absolute;
            width: 280px;
            min-height: 180px;
            cursor: grab;
            user-select: none;
            border-width: 2px;
            box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
            transition: box-shadow 0.3s ease;
            padding: 16px;
            border-radius: 12px;
            background-clip: padding-box;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .note-card:active {
            cursor: grabbing;
            box-shadow: 0 8px 16px rgb(0 0 0 / 0.3);
        }
    </style>

    <div class="max-w-full mx-auto mt-8 px-6">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-4xl font-bold text-gray-800 tracking-tight">📚 Mes notes</h1>

            <a href="{{ route('notes.create') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-0.5 hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter une note
            </a>
        </div>

        {{-- Conteneur pour les notes en position absolue --}}
        <div id="notes-container" class="relative">
            @forelse ($notes as $note)
                @php
                    $color = $colors[$loop->index % count($colors)];
                    // Défaut si pas de position
                    $x = $note->x_position ?? (100 + $loop->index * 30);
                    $y = $note->y_position ?? (100 + $loop->index * 30);
                @endphp

                <div class="note-card {{ $color }} border"
                     data-id="{{ $note->id }}"
                     style="left: {{ $x }}px; top: {{ $y }}px;"
                >
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-bold text-gray-900 truncate" title="{{ $note->title }}">{{ $note->title }}</h2>
                        <form action="{{ route('notes.togglePin', $note) }}" method="POST" class="pin-form">
                            @csrf
                            <button type="submit" title="Épingler / Désépingler"
                                    class="pin-button text-yellow-500 hover:text-yellow-700 text-lg select-none">
                                {{ $note->is_pinned ? '📍' : '📌' }}
                            </button>
                        </form>
                    </div>

                    <p class="text-gray-800 whitespace-pre-line mb-4" style="overflow: auto;">
                        {{ $note->content ?: 'Pas de contenu' }}
                    </p>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('notes.edit', $note) }}"
                           class="text-indigo-600 hover:text-indigo-800 font-medium transition">
                            ✏️ Modifier
                        </a>

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
                <p class="text-gray-500 italic">Aucune note pour l’instant.</p>
            @endforelse
        </div>
    </div>

    {{-- JS pour drag & drop libre --}}
    <script>
        document.querySelectorAll('.note-card').forEach(card => {
            let isDragging = false;
            let startX, startY, initialLeft, initialTop;

            card.addEventListener('mousedown', e => {
                isDragging = true;
                startX = e.clientX;
                startY = e.clientY;
                const style = window.getComputedStyle(card);
                initialLeft = parseInt(style.left, 10);
                initialTop = parseInt(style.top, 10);
                card.style.cursor = 'grabbing';
                card.style.zIndex = 1000;
            });

            document.addEventListener('mousemove', e => {
                if (!isDragging) return;
                const dx = e.clientX - startX;
                const dy = e.clientY - startY;
                card.style.left = (initialLeft + dx) + 'px';
                card.style.top = (initialTop + dy) + 'px';
            });

            document.addEventListener('mouseup', e => {
                if (!isDragging) return;
                isDragging = false;
                card.style.cursor = 'grab';
                card.style.zIndex = '';

                // Enregistrer la nouvelle position via fetch
                const id = card.dataset.id;
                const left = parseInt(card.style.left, 10);
                const top = parseInt(card.style.top, 10);

                fetch("{{ route('notes.updatePosition', ['note' => 'NOTE_ID']) }}".replace('NOTE_ID', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ x: left, y: top }),
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur réseau');
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) alert('Erreur lors de la sauvegarde de la position');
                    })
                    .catch(() => alert('Erreur réseau lors de la sauvegarde'));
            });
        });

        // Gestion épingle (toggle épingle sans déplacer)
        document.querySelectorAll('.pin-form').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();

                const button = form.querySelector('.pin-button');
                const card = form.closest('.note-card');
                const id = card.dataset.id;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur réseau');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Toggle icône épingle
                            button.textContent = button.textContent === '📌' ? '📍' : '📌';
                        } else {
                            alert('Erreur lors du toggle épingle.');
                        }
                    })
                    .catch(() => alert('Erreur réseau lors du toggle épingle'));
            });
        });
    </script>
@endsection
