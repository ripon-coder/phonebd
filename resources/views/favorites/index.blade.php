@extends('layouts.app', [
    'title' => 'My Favorite Items — PhoneBD',
    'meta_description' => 'Your saved favorite mobile phones and gadgets.',
])

@section('og_image', asset('images/og-default.jpg'))
@section('og_type', 'website')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="flex mb-4 text-sm text-slate-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center hover:text-slate-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 font-medium text-slate-900 md:ml-2">Favorites</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-3 mt-4" x-data="favoritesPage()">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-md lg:text-2xl font-bold text-slate-900 tracking-tight">My Favorites</h1>
            <div class="flex items-center gap-4">
                <span class="text-slate-500 text-sm" x-text="allIds.length + ' items saved'"></span>
                <button x-show="allIds.length > 0" @click="clearAll()" class="text-red-600 text-sm font-medium hover:text-red-700 hover:underline transition-colors">Clear All</button>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-3" id="favorites-container">
            {{-- Content will be loaded here --}}
        </div>

        {{-- Loading Spinner (Initial) --}}
        <div x-show="loading && displayedCount === 0" class="flex justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        {{-- Load More Button --}}
        <div class="mt-8 text-center" x-show="hasMore" style="display: none;">
            <button @click="loadMore()" :disabled="loading" class="px-4 py-1.5 md:px-6 md:py-2 text-xs md:text-sm bg-slate-900 text-white rounded-sm font-medium hover:bg-slate-800 transition-colors disabled:opacity-50 flex items-center gap-2 mx-auto">
                <span x-show="!loading">Load More</span>
                <span x-show="loading">Loading...</span>
                <svg x-show="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('favoritesPage', () => ({
                loading: false,
                allIds: [],
                displayedCount: 0,
                perPage: 10,
                hasMore: false,
                
                init() {
                    this.loading = true;
                    const rawIds = JSON.parse(localStorage.getItem('favorites') || '[]');
                    
                    if (rawIds.length === 0) {
                        document.getElementById('favorites-container').innerHTML = '<div class="col-span-full text-center py-12 text-slate-500">You haven\'t saved any items yet.</div>';
                        this.loading = false;
                        return;
                    }

                    // Validate IDs first
                    fetch('{{ route('products.favorites_check') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ ids: rawIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.allIds = data.valid_ids;
                        
                        // Update localStorage if some IDs were invalid
                        if (this.allIds.length !== rawIds.length) {
                            localStorage.setItem('favorites', JSON.stringify(this.allIds));
                        }

                        if (this.allIds.length === 0) {
                            document.getElementById('favorites-container').innerHTML = '<div class="col-span-full text-center py-12 text-slate-500">You haven\'t saved any items yet.</div>';
                            this.loading = false;
                            return;
                        }

                        this.loadMore();
                    })
                    .catch(error => {
                        console.error('Error checking favorites:', error);
                        // Fallback to loading whatever we have if check fails
                        this.allIds = rawIds;
                        this.loadMore();
                    });
                },

                loadMore() {
                    this.loading = true;
                    const nextBatch = this.allIds.slice(this.displayedCount, this.displayedCount + this.perPage);
                    
                    if (nextBatch.length === 0) {
                        this.loading = false;
                        this.hasMore = false;
                        return;
                    }

                    fetch('{{ route('products.favorites_list') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ ids: nextBatch })
                    })
                    .then(response => response.text())
                    .then(html => {
                        const container = document.getElementById('favorites-container');
                        container.insertAdjacentHTML('beforeend', html);
                        
                        // Re-initialize Alpine on the container to pick up new x-data components
                        Alpine.initTree(container);

                        this.displayedCount += nextBatch.length;
                        this.hasMore = this.displayedCount < this.allIds.length;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.loading = false;
                    });
                },

                clearAll() {
                    if (confirm('Are you sure you want to remove all items from your favorites?')) {
                        localStorage.removeItem('favorites');
                        this.allIds = [];
                        this.displayedCount = 0;
                        this.hasMore = false;
                        document.getElementById('favorites-container').innerHTML = '<div class="col-span-full text-center py-12 text-slate-500">You haven\'t saved any items yet.</div>';
                    }
                }
            }))
        })
    </script>
    @endpush
@endsection
