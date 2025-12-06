@extends('layouts.app', [
    'title' => 'All Devices — PhoneBD',
    'meta_description' => 'Browse all mobile phones and accessories available on PhoneBD.',
])

@section('og_image', asset('images/fallback-img.png'))
@section('og_type', 'website')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="flex mb-3 text-sm text-slate-500" aria-label="Breadcrumb">
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
                    <span class="ml-1 font-medium text-slate-900 md:ml-2">All Devices</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-4 mt-2">
        <div class="flex flex-col lg:flex-row gap-4">
            {{-- Sidebar Filters (Desktop) --}}
            <aside class="hidden lg:block w-64 shrink-0 space-y-4">
                <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                    {{-- Retain Sort Order --}}
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- Global Clear All --}}
                    @if(request()->hasAny(['brands', 'categories', 'min_price', 'max_price', 'price_range', 'status']))
                        <div class="mb-6">
                            <a href="{{ route('products.index') }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Clear All Filters
                            </a>
                        </div>
                    @endif

                    {{-- Category Filter --}}
                    <div>
                        <h3 class="font-bold text-slate-900 mb-4">Categories</h3>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        onchange="this.form.submit()">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div class="mt-4">
                        <h3 class="font-bold text-slate-900 mb-4">Availability</h3>
                        <div class="space-y-2">
                            @foreach (['official' => 'Official', 'unofficial' => 'Unofficial', 'upcoming' => 'Coming Soon'] as $value => $label)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="status[]" value="{{ $value }}" 
                                        {{ in_array($value, request('status', [])) ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        onchange="this.form.submit()">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Brand Filter --}}
                    <div class="mt-4">
                        <h3 class="font-bold text-slate-900 mb-4">Brands</h3>
                        <div class="space-y-2 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                        <div class="space-y-2 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach ($brands as $brand)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="brands[]" value="{{ $brand->id }}" 
                                        {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        onchange="this.form.submit()">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Filter --}}
                    <div class="mt-4">
                        <h3 class="font-bold text-slate-900 mb-4">Price Range</h3>
                        <div class="space-y-2">
                             @php
                                $priceRanges = [
                                    '5000-10000' => '৳5,000 - ৳10,000',
                                    '10000-20000' => '৳10,000 - ৳20,000',
                                    '20000-30000' => '৳20,000 - ৳30,000',
                                    '30000-50000' => '৳30,000 - ৳50,000',
                                    '50000-100000' => '৳50,000 - ৳1,00,000',
                                ];
                                $currentMin = request('min_price');
                                $currentMax = request('max_price');
                            @endphp
                            
                            @foreach($priceRanges as $range => $label)
                                @php
                                    [$min, $max] = explode('-', $range);
                                    $isChecked = $currentMin == $min && $currentMax == $max;
                                @endphp
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="price_range" value="{{ $range }}" 
                                        {{ $isChecked ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500 border-slate-300"
                                        onclick="setPriceAndSubmit(this, '{{ $min }}', '{{ $max }}')">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach

                            {{-- Custom Price --}}
                            <div class="pt-4 border-t border-slate-100 mt-4">
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">Custom Range</span>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                    <span class="text-slate-400">-</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                                <button type="submit" class="mt-2 w-full bg-slate-900 text-white text-xs font-bold py-2 rounded hover:bg-slate-800 transition-colors">Apply</button>
                            </div>
                        </div>
                    </div>
                </form>
            </aside>

            {{-- Main Content --}}
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3 gap-4">
                    <div>
                        <h1 class="text-xl lg:text-2xl font-bold text-slate-900 tracking-tight">All Devices</h1>
                        <span class="text-slate-500 text-sm">{{ $products->total() }} devices found</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        {{-- Mobile Filter Toggle --}}
                        <button onclick="document.getElementById('mobile-filters').classList.remove('translate-x-full')" 
                            class="lg:hidden flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded text-sm font-medium text-slate-700 hover:border-slate-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filters
                        </button>

                        {{-- Sort Dropdown --}}
                        <div class="relative flex-1 sm:flex-none">
                            <select onchange="updateSort(this.value)" class="w-full sm:w-48 appearance-none bg-white border border-slate-200 text-slate-700 py-2 pl-4 pr-8 rounded leading-tight focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm font-medium cursor-pointer">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Best Rating</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-3">
                    @foreach ($products as $phone)
                        <a href="{{ route('product.show', ['category_slug' => $phone->category->slug, 'product' => $phone->slug]) }}"
                            class="group relative bg-white rounded-sm border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200 block">
                            
                            <div class="relative aspect-square bg-slate-50/50 group-hover:bg-slate-50 transition-colors">
                                <div class="absolute top-2 right-2 z-10">
                                    <div x-data="favorite({{ $phone->id }})" @click.prevent="toggle()" :class="isFavorite ? 'text-red-500' : 'text-slate-400 hover:text-red-500'" class="group relative transition-colors cursor-pointer bg-white/80 rounded-full p-1 shadow-sm backdrop-blur-sm">
                                        <svg class="w-5 h-5" :fill="isFavorite ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <div class="hidden md:block absolute right-full top-1/2 -translate-y-1/2 mr-2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                            <span x-text="isFavorite ? 'Remove' : 'Add to Favorites'"></span>
                                            <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-800 rotate-45"></div>
                                        </div>
                                    </div>
                                </div>
                                @if ($phone->image)
                                    <img loading="lazy" src="{{ $phone->getImageUrl('image') }}"
                                        alt="{{ $phone->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="p-3">
                                <h3
                                    class="font-bold text-slate-900 text-sm mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $phone->title }}</h3>
                                <div class="flex items-center justify-between">
                                    <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">
                                        @if ($phone->base_price)
                                            ৳{{ number_format($phone->base_price) }}
                                        @else
                                            Expected Soon
                                        @endif
                                    </span>
                                    @if($phone->avg_rating > 0)
                                        <div class="flex items-center gap-1 text-xs font-medium text-amber-500">
                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            {{ number_format($phone->avg_rating, 1) }}/5
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Filter Slide-over --}}
    <div id="mobile-filters" class="fixed inset-0 z-50 transform translate-x-full transition-transform duration-300 lg:hidden">
        <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('mobile-filters').classList.add('translate-x-full')"></div>
        <div class="absolute right-0 top-0 bottom-0 w-80 bg-white shadow-xl overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Filters</h2>
                    <button onclick="document.getElementById('mobile-filters').classList.add('translate-x-full')" class="text-slate-500 hover:text-slate-900">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                {{-- Mobile Filter Form (Clone of desktop, but simplified for mobile context if needed) --}}
                <form action="{{ route('products.index') }}" method="GET">
                     @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    
                    {{-- Global Clear All --}}
                    @if(request()->hasAny(['brands', 'categories', 'min_price', 'max_price', 'price_range', 'status']))
                        <div class="mb-6">
                            <a href="{{ route('products.index') }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Clear All Filters
                            </a>
                        </div>
                    @endif

                    {{-- Category Filter --}}
                    <div class="mb-6">
                        <h3 class="font-bold text-slate-900 mb-3">Categories</h3>
                        <div class="space-y-2">
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-slate-700">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div class="mb-6">
                        <h3 class="font-bold text-slate-900 mb-3">Availability</h3>
                        <div class="space-y-2">
                            @foreach (['official' => 'Official', 'unofficial' => 'Unofficial', 'upcoming' => 'Coming Soon'] as $value => $label)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="status[]" value="{{ $value }}" 
                                        {{ in_array($value, request('status', [])) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-slate-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Brand Filter --}}
                    <div class="mb-6">
                        <h3 class="font-bold text-slate-900 mb-3">Brands</h3>
                        <div class="space-y-2">
                            @foreach ($brands as $brand)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="brands[]" value="{{ $brand->id }}" 
                                        {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-slate-700">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Filter --}}
                    <div class="mb-6">
                        <h3 class="font-bold text-slate-900 mb-3">Price Range</h3>
                        <div class="space-y-2">
                            @foreach($priceRanges as $range => $label)
                                @php
                                    [$min, $max] = explode('-', $range);
                                    $isChecked = $currentMin == $min && $currentMax == $max;
                                @endphp
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="price_range_mobile" value="{{ $range }}" 
                                        {{ $isChecked ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300"
                                        onclick="setPriceAndSubmitMobile(this, '{{ $min }}', '{{ $max }}')">
                                    <span class="text-sm text-slate-700">{{ $label }}</span>
                                </label>
                            @endforeach

                             <div class="pt-4 border-t border-slate-100 mt-4">
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">Custom Range</span>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                    <span class="text-slate-400">-</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white font-bold py-2 rounded text-sm hover:bg-slate-800 transition-colors shadow-sm">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateSort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            window.location.href = url.toString();
        }

        function setPriceAndSubmit(radio, min, max) {
            const form = document.getElementById('filter-form');
            const minInput = form.querySelector('input[name="min_price"]');
            const maxInput = form.querySelector('input[name="max_price"]');
            
            // If clicking the same radio, clear it
            if (minInput.value == min && maxInput.value == max) {
                minInput.value = '';
                maxInput.value = '';
                radio.checked = false;
            } else {
                minInput.value = min;
                maxInput.value = max;
            }
            form.submit();
        }

        function setPriceAndSubmitMobile(radio, min, max) {
             // Just set the values, let the Apply button submit
            const form = radio.closest('form');
            const minInput = form.querySelector('input[name="min_price"]');
            const maxInput = form.querySelector('input[name="max_price"]');
            
            minInput.value = min;
            maxInput.value = max;
        }
    </script>
    @endpush
@endsection
