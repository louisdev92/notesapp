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

                $avatarUrl = Auth::user()->avatar_path
                    ? asset('storage/' . Auth::user()->avatar_path)
                    : "https://randomuser.me/api/portraits/{$gender}/{$avatarId}.jpg";
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
                    <div class="sm:w-1/3 w-full flex items-center justify-center sm:justify-end">
                        <div class="text-center space-y-4">
                            <div class="rounded-full p-1 bg-gradient-to-tr from-blue-500 via-purple-500 to-indigo-500 shadow-lg inline-block">
                                <img
                                    src="{{ $avatarUrl }}"
                                    alt="Avatar de {{ Auth::user()->name }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 transition-transform duration-300 hover:scale-105"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=128&background=random&color=fff';"
                                />
                            </div>

                            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="space-y-2">
                                @csrf
                                @method('PATCH')

                                <input type="file" name="avatar" accept="image/*" class="text-sm text-gray-700 dark:text-gray-200" required>
                                <x-input-error :messages="$errors->get('avatar')" class="mt-1" />

                                <x-secondary-button class="w-full">Changer l’image</x-secondary-button>

                                @if (session('status') === 'avatar-updated')
                                    <p class="text-sm text-green-600 dark:text-green-400">Avatar mis à jour !</p>
                                @endif
                            </form>
                        </div>
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
@endsection
