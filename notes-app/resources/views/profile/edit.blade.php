@extends('layouts.app')

@section('title', 'Profil - NotesApp')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
        {{ __('Mon profil') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Données utilisateur --}}
            @php
                $avatarId = Auth::user()->id % 100;
                $gender = property_exists(Auth::user(), 'gender') ? strtolower(Auth::user()->gender) : 'men';
                if (!in_array($gender, ['men', 'women'])) $gender = 'men';

                if (Auth::user()->avatar_path) {
                    $avatarUrl = asset('storage/' . Auth::user()->avatar_path);
                } else {
                    $avatarUrl = "https://randomuser.me/api/portraits/{$gender}/{$avatarId}.jpg";
                }
            @endphp

            {{-- Section informations utilisateur --}}
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex flex-col sm:flex-row gap-8">

                    {{-- Formulaire infos utilisateur --}}
                    <div class="sm:w-2/3 w-full">
                        <header class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                Informations personnelles
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Mettez à jour vos données.</p>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Nom -->
                            <div>
                                <x-input-label for="name" value="Nom" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                              :value="old('name', Auth::user()->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                              :value="old('email', Auth::user()->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                                    <div class="mt-2 text-sm text-gray-800 dark:text-gray-300">
                                        Votre adresse e-mail n’est pas vérifiée.
                                        <button form="send-verification"
                                                class="underline text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-white">
                                            Renvoyer le lien de vérification
                                        </button>
                                    </div>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600">
                                            Un nouveau lien a été envoyé !
                                        </p>
                                    @endif
                                @endif
                            </div>

                            <!-- Bouton -->
                            <div class="flex items-center gap-4">
                                <x-primary-button>Enregistrer</x-primary-button>
                                @if (session('status') === 'profile-updated')
                                    <p class="text-sm text-green-600 dark:text-green-400">Enregistré.</p>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Avatar et Upload --}}
                    <div class="sm:w-1/3 w-full flex flex-col items-center justify-center sm:justify-end space-y-6">

                        <label
                            for="avatarInput"
                            class="relative cursor-pointer rounded-full p-1 bg-gradient-to-tr from-blue-500 via-purple-500 to-indigo-500 shadow-lg w-45 h-45 flex items-center justify-center overflow-hidden"
                            title="Cliquez ou glissez une image pour changer votre avatar"
                            ondragover="event.preventDefault(); this.classList.add('ring', 'ring-4', 'ring-indigo-500')"
                            ondragleave="event.preventDefault(); this.classList.remove('ring', 'ring-4', 'ring-indigo-500')"
                            ondrop="event.preventDefault();
                                    this.classList.remove('ring', 'ring-4', 'ring-indigo-500');
                                    const input = document.getElementById('avatarInput');
                                    if (event.dataTransfer.files.length) {
                                        input.files = event.dataTransfer.files;
                                        input.dispatchEvent(new Event('change'));
                                    }"
                        >
                            <img
                                id="avatarPreview"
                                src="{{ $avatarUrl }}"
                                alt="Avatar de {{ Auth::user()->name }}"
                                class="w-full h-full rounded-full object-cover border-4 border-white dark:border-gray-800 transition-transform duration-300 hover:scale-110"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=128&background=random&color=fff';"
                            />
                            <div
                                id="uploadOverlay"
                                class="absolute inset-0 bg-black bg-opacity-30 rounded-full flex flex-col items-center justify-center text-white opacity-0 hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0-8l-3 3m3-3l3 3M16 8a4 4 0 10-8 0v4h8V8z" />
                                </svg>
                                <span class="text-sm font-semibold select-none">Changer</span>
                            </div>
                        </label>

                        {{-- Affichage erreurs --}}
                        @if ($errors->any())
                            <div class="mb-4 text-red-600 text-center">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Formulaire upload --}}
                        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="w-full max-w-xs text-center">
                            @csrf
                            @method('PATCH')

                            <input
                                type="file"
                                id="avatarInput"
                                name="avatar"
                                accept="image/*"
                                class="hidden"
                                required
                            />

                            <button
                                type="submit"
                                id="avatarSubmit"
                                class="mt-4 w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-md shadow hover:from-purple-700 hover:to-blue-700 transition-opacity duration-300 opacity-50 cursor-not-allowed"
                                disabled
                            >
                                Changer l’image
                            </button>

                            @if (session('status') === 'avatar-updated')
                                <p class="mt-3 text-green-600 dark:text-green-400 font-semibold animate-fadeIn">
                                    Avatar mis à jour !
                                </p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Section mot de passe --}}
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Section suppression compte --}}
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');
            const avatarSubmit = document.getElementById('avatarSubmit');

            avatarInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) {
                    avatarSubmit.disabled = true;
                    avatarSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    alert('Veuillez sélectionner un fichier image valide.');
                    avatarInput.value = '';
                    avatarSubmit.disabled = true;
                    avatarSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (event) => {
                    avatarPreview.src = event.target.result;
                    avatarSubmit.disabled = false;
                    avatarSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
