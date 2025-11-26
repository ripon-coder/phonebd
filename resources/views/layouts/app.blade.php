{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="theme-color" content="#0ea5e9" />
    <meta name="description" content="@yield('meta_description', 'PhoneBD — Mobile & gadget specs, lightning fast.')">

    <title>@yield('title','PhoneBD') — {{ config('app.name', 'PhoneBD') }}</title>

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Example Google Font (optional) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Vite assets (app.css should include Tailwind) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific styles --}}
    @stack('styles')

    {{-- Alpine.js for interactivity --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
      /* small critical CSS to ensure white bg and smoother font rendering */
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
        <div class="flex items-center justify-between h-16">
          
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
          <div class="hidden md:flex flex-1 max-w-lg mx-8">
            <form action="" method="GET" class="w-full relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </div>
              <input 
                name="q" 
                type="search" 
                placeholder="Search phones, brands, specs..." 
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-full leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 sm:text-sm"
                autocomplete="off"
              >
            </form>
          </div>

          {{-- Right: Desktop Nav --}}
          <nav class="hidden md:flex items-center gap-1">
            <a href="#" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all">Brands</a>
            <a href="#" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all">Latest</a>
            <a href="#" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all">Blog</a>
          </nav>

          {{-- Mobile Menu Button --}}
          <div class="flex items-center gap-2 md:hidden">
            <button @click="searchOpen = !searchOpen" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
        </div>
      </div>

      {{-- Mobile Search Bar --}}
      <div x-show="searchOpen" x-collapse class="md:hidden border-t border-gray-100 bg-gray-50 px-4 py-3">
        <form action="" method="GET">
            <input type="search" name="q" placeholder="Search phones..." class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        </form>
      </div>

      {{-- Mobile Menu --}}
      <div 
        x-show="mobileMenuOpen" 
        x-collapse
        class="md:hidden border-t border-gray-100 bg-white shadow-lg"
        style="display: none;"
      >
        <div class="px-4 pt-2 pb-6 space-y-1">
            <a href="#" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Home</a>
            <a href="#" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Brands</a>
            <a href="#" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Latest Phones</a>
            <a href="#" class="block px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Blog</a>
        </div>
      </div>
    </header>

    {{-- Flash / status messages --}}
    <div class="max-w-7xl mx-auto px-2 sm:px-2 md:px-6 lg:px-8 mt-4">
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
      <div class="max-w-7xl mx-auto px-2 sm:px-2 md:px-4 lg:px-4 pb-8">
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
