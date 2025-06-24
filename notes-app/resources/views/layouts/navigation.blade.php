<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <span class="text-xl font-bold text-gray-800 dark:text-white">NotesApp</span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700 dark:text-gray-200">Bonjour, {{ Auth::user()->name }}</span>
                        <a href="{{ route('profile.edit') }}" class="text-sm text-blue-600 dark:text-blue-400">Profil</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 dark:text-red-400">Déconnexion</button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 dark:text-blue-400">Se connecter</a>
                        <a href="{{ route('register') }}" class="text-sm text-blue-600 dark:text-blue-400">S'inscrire</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
