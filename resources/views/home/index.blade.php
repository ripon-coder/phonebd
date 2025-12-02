@extends('layouts.app', [
    'title' => 'PhoneBD — Mobile Specs & Prices',
    'meta_description' => 'Dummy mobile specs, dummy brand list for testing layout.',
])


@section('content')

    {{-- Categories --}}
    <div class="mb-6 mt-2">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-md lg:text-lg  font-semibold text-slate-900 tracking-tight">Browse Categories</h2>
            <a href="{{ route('products.index') }}"
                class="md:hidden text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
                View All
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        {{-- Mobile: Horizontal Scroll, Desktop: Flex Wrap --}}
        <div
            class="flex flex-nowrap md:flex-wrap gap-1 overflow-x-auto md:overflow-visible pb-1 md:pb-0 snap-x snap-mandatory hide-scrollbar">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['categories[]' => $category->id]) }}"
                    class="snap-start shrink-0 px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-medium hover:border-slate-900 hover:text-slate-900 transition-all duration-200 shadow-sm">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <x-home.browse-by-price />



    {{-- Latest Phones --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-md lg:text-lg font-semibold text-slate-900 tracking-tight">Just Arrived</h2>
            <a href="{{ route('products.index', ['sort' => 'latest']) }}"
                class="text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
                View All
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-3">
            @foreach ($latestPhones as $phone)
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
                            <img src="{{ $phone->getImageUrl('image') }}"
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
                            <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">৳{{ number_format($phone->base_price) }}</span>
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
    </div>

    {{-- Popular Brands --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-md lg:text-lg font-semibold text-slate-900 tracking-tight">Popular Brands</h2>
            <a href="{{ route('brands.index') }}"
                class="text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
                View All
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-4">
            @foreach ($brands as $brand)
                <a href="{{ route('brands.show', $brand) }}"
                    class="group flex flex-col items-center justify-center bg-white border border-slate-100 rounded-sm p-3 hover:border-slate-300 transition-all duration-200">
                    <div
                        class="w-10 h-10 mb-2 relative grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                        @if ($brand->image)
                            <img src="{{ $brand->getImageUrl('image') }}" class="w-full h-full object-contain"
                                alt="{{ $brand->name }}">
                        @else
                            {{-- Fallback Text/Icon if no image --}}
                            <span
                                class="text-xl font-bold text-slate-300 group-hover:text-slate-500">{{ substr($brand->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <span
                        class="text-[11px] font-semibold text-slate-500 group-hover:text-slate-900 transition-colors uppercase tracking-wide text-center">{{ $brand->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <x-home.dynamic-pages :dynamic-pages="$dynamicPages" />



    {{-- Official Phones --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-md lg:text-lg font-semibold text-slate-900 tracking-tight">Official Phones</h2>
            <a href="{{ route('products.index', ['status[]' => 'official']) }}"
                class="text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
                View All
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-3">
            @foreach ($officialPhones as $phone)
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
                            <img src="{{ $phone->getImageUrl('image') }}"
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
                            <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">৳{{ number_format($phone->base_price) }}</span>
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
    </div>

    {{-- Unofficial Phones --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-md lg:text-lg font-semibold text-slate-900 tracking-tight">Unofficial Phones</h2>
            <a href="{{ route('products.index', ['status[]' => 'unofficial']) }}"
                class="text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
                View All
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-3">
            @foreach ($unofficialPhones as $phone)
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
                                </div>
                            </div>
                        </div>
                        @if ($phone->image)
                            <img src="{{ $phone->getImageUrl('image') }}"
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
                            <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">৳{{ number_format($phone->base_price) }}</span>
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
    </div>

    {{-- Upcoming Phones --}}
    <div class="mb-0">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-md lg:text-lg font-semibold text-slate-900 tracking-tight">Coming Soon</h2>
            <a href="{{ route('products.index', ['status[]' => 'upcoming']) }}"
                class="text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
                View All
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-3">
            @foreach ($upcomingPhones as $phone)
                <a href="{{ route('product.show', ['category_slug' => $phone->category->slug, 'product' => $phone->slug]) }}"
                    class="group relative bg-white rounded-sm border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200 block">
                    <div class="absolute top-3 left-3 z-10">
                        <span
                            class="bg-gray-400 text-white text-[8px] md:text-[10px] lg:text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">Upcoming</span>
                    </div>
                    <div class="absolute top-2 right-2 z-10">
                        <div x-data="favorite({{ $phone->id }})" @click.prevent="toggle()" :class="isFavorite ? 'text-red-500' : 'text-slate-400 hover:text-red-500'" class="group relative transition-colors cursor-pointer bg-white/80 rounded-full p-1 shadow-sm backdrop-blur-sm">
                            <svg class="w-5 h-5" :fill="isFavorite ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <div class="hidden md:block absolute right-full top-1/2 -translate-y-1/2 mr-2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                <span x-text="isFavorite ? 'Remove' : 'Add to Favorites'"></span>
                            </div>
                        </div>
                    </div>

                    <div class="relative aspect-square bg-slate-50/50 p-4 group-hover:bg-slate-50 transition-colors">
                        @if ($phone->image)
                            <img src="{{ $phone->getImageUrl('image') }}"
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
                            <span class="md:text-base lg:text-md text-sm font-bold text-blue-500">
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
    </div>

@endsection
