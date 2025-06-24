@php
    use Illuminate\Support\Facades\Storage;
@endphp

<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="flex justify-between items-center h-20">
            <!-- Logo à gauche -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <span class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white select-none">
                        NotesApp
                    </span>
                </a>
            </div>

            <!-- Bouton menu mobile -->
            <div class="sm:hidden">
                <button id="mobile-menu-button" aria-label="Ouvrir le menu" class="text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Partie compte/utilisateur à droite (desktop) -->
            <div class="hidden sm:flex sm:items-center sm:space-x-8">
                @auth
                    @php
                        $user = Auth::user();

                        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                            $avatarUrl = asset('storage/' . $user->avatar_path);
                        } else {
                            $avatarId = $user->id % 100;
                            $gender = property_exists($user, 'gender') ? strtolower($user->gender) : 'men';
                            if ($gender !== 'men' && $gender !== 'women') $gender = 'men';
                            $avatarUrl = "https://randomuser.me/api/portraits/{$gender}/{$avatarId}.jpg";
                        }
                    @endphp
                    <div class="flex items-center space-x-4">
                        <img
                            src="{{ $avatarUrl }}"
                            alt="Avatar de {{ $user->name }}"
                            class="w-12 h-12 rounded-full border-2 border-blue-500 shadow-md transition-transform duration-300 hover:scale-110"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=48&background=random&color=fff';"
                        />
                        <a href="{{ route('profile.edit') }}" class="text-lg font-semibold text-blue-700 dark:text-blue-400 hover:underline transition">
                            {{ $user->name }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="text-lg font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-600 transition"
                            >
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('login') }}" class="text-lg font-semibold text-blue-600 dark:text-blue-400 hover:underline transition">
                            Se connecter
                        </a>
                        <a href="{{ route('register') }}" class="text-lg font-semibold text-blue-600 dark:text-blue-400 hover:underline transition">
                            S'inscrire
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Menu mobile (hidden par défaut) -->
    <div id="mobile-menu" class="sm:hidden hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-6 py-4">
        @auth
            @php
                $user = Auth::user();

                if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                    $avatarUrl = asset('storage/' . $user->avatar_path);
                } else {
                    $avatarId = $user->id % 100;
                    $gender = property_exists($user, 'gender') ? strtolower($user->gender) : 'men';
                    if ($gender !== 'men' && $gender !== 'women') $gender = 'men';
                    $avatarUrl = "https://randomuser.me/api/portraits/{$gender}/{$avatarId}.jpg";
                }
            @endphp
            <div class="flex flex-col items-start space-y-4">
                <div class="flex items-center space-x-4">
                    <img
                        src="{{ $avatarUrl }}"
                        alt="Avatar de {{ $user->name }}"
                        class="w-12 h-12 rounded-full border-2 border-blue-500 shadow-md"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=48&background=random&color=fff';"
                    />
                    <a href="{{ route('profile.edit') }}" class="text-lg font-semibold text-blue-700 dark:text-blue-400 hover:underline transition">
                        {{ $user->name }}
                    </a>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button
                        type="submit"
                        class="w-full text-left text-lg font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-600 transition"
                    >
                        Déconnexion
                    </button>
                </form>
            </div>
        @else
            <div class="flex flex-col space-y-4">
                <a href="{{ route('login') }}" class="text-lg font-semibold text-blue-600 dark:text-blue-400 hover:underline transition">
                    Se connecter
                </a>
                <a href="{{ route('register') }}" class="text-lg font-semibold text-blue-600 dark:text-blue-400 hover:underline transition">
                    S'inscrire
                </a>
            </div>
        @endauth
    </div>

    <script>
        const menuBtn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</nav>
