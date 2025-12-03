{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="theme-color" content="#0ea5e9" />
    <meta name="description" content="{{ $meta_description ?? trim($__env->yieldContent('meta_description', 'PhoneBD — Mobile & gadget specs, lightning fast.')) }}">

    <title>{{ $title ?? trim($__env->yieldContent('title', config('app.name', 'PhoneBD'))) }} | Phonebd.net</title>

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $title ?? trim($__env->yieldContent('title', config('app.name', 'PhoneBD'))) }} | Phonebd.net" />
    <meta property="og:description" content="{{ $meta_description ?? trim($__env->yieldContent('meta_description', 'PhoneBD — Mobile & gadget specs, lightning fast.')) }}" />
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))" />
    <meta property="og:site_name" content="PhoneBD" />

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ url()->current() }}" />
    <meta name="twitter:title" content="{{ $title ?? trim($__env->yieldContent('title', config('app.name', 'PhoneBD'))) }} | Phonebd.net" />
    <meta name="twitter:description" content="{{ $meta_description ?? trim($__env->yieldContent('meta_description', 'PhoneBD — Mobile & gadget specs, lightning fast.')) }}" />
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))" />

    {{-- Canonical --}}
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    {{-- CSRF --}}
    <meta name="csrf-token" content="">
    <script>
        fetch('/csrf-token')
            .then(response => response.json())
            .then(data => {
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
            });
    </script>

    {{-- Preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Example Google Font (optional) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Vite assets (app.css should include Tailwind) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific styles --}}
    @stack('styles')

    {{-- Structured Data / JSON-LD --}}
    @stack('schema')

    {{-- Alpine.js for interactivity --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('favorite', (id) => ({
                isFavorite: false,
                init() {
                    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                    this.isFavorite = favorites.includes(id);
                },
                toggle() {
                    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                    if (this.isFavorite) {
                        favorites = favorites.filter(i => i !== id);
                    } else {
                        favorites.push(id);
                    }
                    localStorage.setItem('favorites', JSON.stringify(favorites));
                    this.isFavorite = !this.isFavorite;
                    window.dispatchEvent(new CustomEvent('favorites-updated', { detail: { count: favorites.length } }));
                }
            }))

            Alpine.data('favoritesCount', () => ({
                count: 0,
                init() {
                    this.updateCount();
                    window.addEventListener('favorites-updated', (e) => {
                        this.count = e.detail.count;
                    });
                    // Also listen for storage events in case it changes in another tab
                    window.addEventListener('storage', (e) => {
                        if (e.key === 'favorites') {
                            this.updateCount();
                        }
                    });
                },
                updateCount() {
                    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                    this.count = favorites.length;
                }
            }))

            Alpine.data('search', () => ({
                query: '',
                results: [],
                isOpen: false,
                isLoading: false,
                controller: null,
                init() {
                    this.$watch('query', (value) => {
                        if (value.length < 2) {
                            this.results = [];
                            this.isOpen = false;
                            return;
                        }
                        this.fetchResults(value);
                    });
                },
                fetchResults(value) {
                    // Cancel previous request if it exists
                    if (this.controller) {
                        this.controller.abort();
                    }
                    this.controller = new AbortController();
                    const signal = this.controller.signal;

                    this.isLoading = true;
                    this.isOpen = true; // Force open to show loading state
                    fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(value)}`, { signal })
                        .then(response => response.json())
                        .then(data => {
                            this.results = data;
                            this.isOpen = true;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            // Ignore abort errors
                            if (error.name !== 'AbortError') {
                                this.isLoading = false;
                            }
                        });
                },
                close() {
                    this.isOpen = false;
                }
            }))
        })
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
      /* small critical CSS to ensure white bg and smoother font rendering */
      html { scroll-behavior: smooth; }
      html,body{background:#ffffff;color:#0f172a;font-family:'Inter',system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial;}
      a{color:inherit}
      [x-cloak] { display: none !important; }
      /* Hide scrollbar for Chrome, Safari and Opera */
      .hide-scrollbar::-webkit-scrollbar {
        display: none;
      }
      /* Hide scrollbar for IE, Edge and Firefox */
      .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
      }
      /* Safe area padding for bottom nav */
      .pb-safe {
        padding-bottom: env(safe-area-inset-bottom);
      }
      @keyframes fadeInDown {
        from {
          opacity: 0;
          transform: translate3d(0, -20px, 0);
        }
        to {
          opacity: 1;
          transform: translate3d(0, 0, 0);
        }
      }
      .animate-fade-in-down {
        animation: fadeInDown 0.5s ease-out;
      }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900 font-sans">
  <div id="app" class="min-h-screen flex flex-col">

    {{-- Header --}}
    <header 
        x-data="{ mobileMenuOpen: false, searchOpen: false }" 
        class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 transition-all duration-300"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
          
          {{-- Left: Logo --}}
          <div class="flex items-center gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-200 group-hover:shadow-blue-300 transition-all duration-300">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <span class="font-bold text-xl tracking-tight text-gray-900 group-hover:text-blue-600 transition-colors">PhoneBD</span>
            </a>
          </div>

          {{-- Center: Desktop Search --}}
          <div class="hidden md:flex flex-1 max-w-lg mx-8" x-data="search" @click.away="close()">
            <form action="{{ route('search.index') }}" method="GET" class="w-full relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-slate-900 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </div>
              <input 
                name="q" 
                type="search" 
                x-model.debounce.300ms="query"
                @focus="isOpen = true"
                placeholder="Search phones, brands, specs..." 
                class="block w-full pl-10 pr-3 py-1.5 border border-gray-200 rounded-full leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-0 focus:border-slate-900 transition-all duration-200 sm:text-sm"
                autocomplete="off"
              >
              
              {{-- Suggestions Dropdown --}}
              <div x-show="isOpen && (results.length > 0 || isLoading)" 
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="opacity-0 translate-y-1"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   class="absolute top-full left-0 w-full mt-2 bg-white rounded-lg shadow-xl border border-slate-100 overflow-hidden z-50"
                   style="display: none;">
                  
                  {{-- Loading State --}}
                  <div x-show="isLoading" class="py-4 text-center text-slate-500">
                      <svg class="animate-spin h-5 w-5 mx-auto text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                  </div>

                  <div x-show="!isLoading && results.length > 0" class="py-2">
                      <template x-for="result in results" :key="result.url">
                          <a :href="result.url" class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 transition-colors">
                              <div class="w-10 h-10 rounded-md bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100 p-1">
                                  <template x-if="result.image">
                                      <img :src="result.image" class="w-full h-full object-contain mix-blend-multiply" loading="lazy">
                                  </template>
                                  <template x-if="!result.image">
                                      <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                      </svg>
                                  </template>
                              </div>
                              <div class="flex-1 min-w-0">
                                  <div class="flex items-center justify-between">
                                      <h4 class="text-sm font-medium text-slate-900 truncate" x-text="result.title"></h4>
                                      <span x-show="result.type === 'product'" class="text-xs font-bold text-blue-600 whitespace-nowrap ml-2" x-text="result.price"></span>
                                  </div>
                                  <p class="text-xs text-slate-500 capitalize" x-text="result.type"></p>
                              </div>
                          </a>
                      </template>
                  </div>
                  <a x-show="!isLoading && results.length > 0" :href="'{{ route('search.index') }}?q=' + query" class="block w-full text-center py-2 bg-slate-50 text-sm font-medium text-blue-600 hover:text-blue-700 border-t border-slate-100">
                      View all results
                  </a>
              </div>
            </form>
          </div>

          {{-- Right: Desktop Nav --}}
          <nav class="hidden md:flex items-center gap-1">
            <a href="{{ route('products.index') }}" class="px-3 py-1 rounded-sm text-sm font-medium transition-all {{ request()->routeIs('products.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Devices</a>
            <a href="{{ route('brands.index') }}" class="px-3 py-1 rounded-sm text-sm font-medium transition-all {{ request()->routeIs('brands.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Brands</a>
            <a href="{{ route('favorites.index') }}" class="px-3 py-1 rounded-sm text-sm font-medium transition-all {{ request()->routeIs('favorites.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}" x-data="favoritesCount">
                <span class="relative">
                    Favorites
                    <span x-show="count > 0" x-text="count" class="absolute -top-2 -right-3 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-slate-900 rounded-full border-2 border-white"></span>
                </span>
            </a>
            <a href="#" class="px-3 py-1 rounded-sm text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all">Latest</a>
            <a href="{{ route('blog.index') }}" class="px-3 py-1 rounded-sm text-sm font-medium transition-all {{ request()->routeIs('blog.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Blog</a>
          </nav>

          {{-- Mobile Menu Button --}}
          <div class="flex items-center gap-2 md:hidden">
            <button @click="searchOpen = !searchOpen" class="p-2 rounded-sm text-gray-600 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-sm text-gray-600 hover:bg-gray-100">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
        </div>
      </div>

      {{-- Mobile Search Bar --}}
      <div x-show="searchOpen" x-collapse class="md:hidden border-t border-gray-100 bg-gray-50 px-4 py-2" x-data="search" @click.away="close()">
        <form action="{{ route('search.index') }}" method="GET" class="relative">
            <input 
                type="search" 
                name="q" 
                x-model.debounce.300ms="query"
                @focus="isOpen = true"
                placeholder="Search phones..." 
                class="w-full px-4 py-2 rounded-sm border border-gray-200 focus:ring-0 focus:border-slate-900 outline-none"
            >

            {{-- Suggestions Dropdown --}}
            <div x-show="isOpen && (results.length > 0 || isLoading)" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute top-full left-0 w-full mt-2 bg-white rounded-sm shadow-xl border border-slate-100 overflow-hidden z-50"
                 style="display: none;">
                
                {{-- Loading State --}}
                <div x-show="isLoading" class="py-4 text-center text-slate-500">
                    <svg class="animate-spin h-5 w-5 mx-auto text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <div x-show="!isLoading && results.length > 0" class="py-2">
                    <template x-for="result in results" :key="result.url">
                        <a :href="result.url" class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 transition-colors">
                            <div class="w-10 h-10 rounded-sm bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100 p-1">
                                <template x-if="result.image">
                                    <img :src="result.image" class="w-full h-full object-contain mix-blend-multiply" loading="lazy">
                                </template>
                                <template x-if="!result.image">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-slate-900 truncate" x-text="result.title"></h4>
                                    <span x-show="result.type === 'product'" class="text-xs font-bold text-blue-600 whitespace-nowrap ml-2" x-text="result.price"></span>
                                </div>
                                <p class="text-xs text-slate-500 capitalize" x-text="result.type"></p>
                            </div>
                        </a>
                    </template>
                </div>
                <a x-show="!isLoading && results.length > 0" :href="'{{ route('search.index') }}?q=' + query" class="block w-full text-center py-2 bg-slate-50 text-sm font-medium text-blue-600 hover:text-blue-700 border-t border-slate-100">
                    View all results
                </a>
            </div>
        </form>
      </div>

      {{-- Mobile Menu --}}
      <div 
        x-show="mobileMenuOpen" 
        x-collapse
        class="md:hidden border-t border-gray-100 bg-white shadow-lg"
        style="display: none;"
      >
        <div class="px-4 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-1.5 rounded-sm text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Home</a>
            <a href="{{ route('products.index') }}" class="block px-3 py-1.5 rounded-sm text-sm font-medium {{ request()->routeIs('products.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Devices</a>
            <a href="{{ route('brands.index') }}" class="block px-3 py-1.5 rounded-sm text-sm font-medium {{ request()->routeIs('brands.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Brands</a>
            <a href="{{ route('favorites.index') }}" class="block px-3 py-1.5 rounded-sm text-sm font-medium {{ request()->routeIs('favorites.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}" x-data="favoritesCount">
                <span class="relative inline-block">
                    Favorites
                    <span x-show="count > 0" x-text="count" class="absolute -top-1 -right-3 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-slate-900 rounded-full border-2 border-white"></span>
                </span>
            </a>
            <a href="#" class="block px-3 py-1.5 rounded-sm text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Latest Phones</a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-1.5 rounded-sm text-sm font-medium {{ request()->routeIs('blog.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">Blog</a>
        </div>
      </div>
    </header>



    {{-- Flash / status messages --}}
    <div class="max-w-7xl mx-auto px-2 sm:px-2 md:px-6 lg:px-8 mt-2">
      @if(session('success'))
        <div class="rounded-md bg-green-50 border border-green-100 px-4 py-2 text-green-800 text-sm">
          {{ session('success') }}
        </div>
      @endif
      @if(session('error'))
        <div class="rounded-md bg-red-50 border border-red-100 px-4 py-2 text-red-800 text-sm">
          {{ session('error') }}
        </div>
      @endif
    </div>

    {{-- Main content --}}
    <main class="flex-1">
      @yield('hero')
      <div class="max-w-7xl mx-auto px-2 sm:px-2 md:px-4 lg:px-4 pb-2">
        @yield('content')
      </div>
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
  </div>

  {{-- Page-specific scripts --}}
  @stack('scripts')

  {{-- Optional small script to toggle mobile menu (no heavy libs) --}}
  <script>
    (function(){
      const btn = document.getElementById('mobile-menu-btn');
      btn?.addEventListener('click', ()=> {
        // Simple behaviour: toggle search focus on small screens (customise as needed)
        const q = document.getElementById('q');
        if (q) {
          q.focus();
        }
      });
    })();
  </script>
</body>
</html>
