<nav class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-gray-100" x-data="{ mobileOpen: false }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="/" class="text-xl font-bold text-gray-900 shrink-0">Auto<span class="text-indigo-600">pahala</span></a>

            {{-- Desktop: Search + Nav Links --}}
            <div class="hidden md:flex items-center gap-6">
                {{-- Inline Search --}}
                <form action="{{ url('/search') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Cari kampanye atau user..."
                           class="w-44 xl:w-56 rounded-lg border-gray-200 bg-gray-50 pl-9 pr-3 py-1.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                </form>

                {{-- Nav Links --}}
                <a href="{{ url('/campaigns') }}" class="text-sm text-gray-600 hover:text-gray-900">Kampanye</a>
                <a href="{{ url('/#categories') }}" class="text-sm text-gray-600 hover:text-gray-900">Kategori</a>
                <a href="{{ url('/#how-it-works') }}" class="text-sm text-gray-600 hover:text-gray-900">Cara Kerja</a>
                <a href="{{ url('/#faq') }}" class="text-sm text-gray-600 hover:text-gray-900">FAQ</a>
                <a href="{{ url('/#contact') }}" class="text-sm text-gray-600 hover:text-gray-900">Kontak</a>
            </div>

            {{-- Right Side: Auth + Mobile Toggle --}}
            <div class="flex items-center gap-3">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->full_name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            <span class="hidden sm:inline max-w-[120px] truncate">{{ auth()->user()->full_name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ url('/@' . auth()->user()->username) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                Profil Publik
                            </a>
                            <a href="{{ url('/user/profile') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                Pengaturan
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ url('/admin') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5"/></svg>
                                    Admin Panel
                                </a>
                            @endif
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 text-left">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-medium text-gray-700 hover:text-gray-900">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition">Daftar</a>
                @endauth

                {{-- Mobile Menu Toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu (dropdown only on mobile) --}}
    <div x-show="mobileOpen" x-transition class="md:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            {{-- Mobile Search --}}
            <form action="{{ url('/search') }}" method="GET" class="mb-3">
                <input type="text" name="q" placeholder="Cari kampanye atau user..."
                       class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </form>

            <a href="{{ url('/campaigns') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Kampanye</a>
            <a href="{{ url('/#categories') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Kategori</a>
            <a href="{{ url('/#how-it-works') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cara Kerja</a>
            <a href="{{ url('/#faq') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-50">FAQ</a>
            <a href="{{ url('/#contact') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Kontak</a>
            @guest
                <div class="pt-2 border-t border-gray-100">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Masuk</a>
                </div>
            @endguest
        </div>
    </div>
</nav>
