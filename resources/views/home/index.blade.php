@extends('layouts.app', [
    'title' => 'PhoneBD — Mobile Specs & Prices',
    'meta_description' => 'Dummy mobile specs, dummy brand list for testing layout.',
])


@section('content')

    {{-- Categories --}}
    <div class="mb-10 mt-4">
        <div class="flex items-center justify-between mb-5 px-1">
            <h2 class="text-md lg:text-lg  font-bold text-slate-900 tracking-tight">Browse Categories</h2>
            <a href="#"
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
                <a href="#"
                    class="snap-start shrink-0 px-5 py-2.5 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-medium hover:border-slate-900 hover:text-slate-900 transition-all duration-200 shadow-sm">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Browse by Price --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5 px-1">
            <h2 class="text-md lg:text-lg font-bold text-slate-900 tracking-tight">Browse by Price</h2>
        </div>

        <div class="grid grid-cols-3 md:flex md:flex-wrap gap-2">
            @php
                $priceRanges = [
                    '৳1 - ৳5,000',
                    '৳5,000 - ৳10,000',
                    '৳10,000 - ৳15,000',
                    '৳15,000 - ৳20,000',
                    '৳20,000 - 30,000',
                    '৳30,000 - 40,000',
                    '৳40,000 - 50,000',
                    '৳50,000 - 60,000',
                    '৳60,000+',
                ];
            @endphp
            @foreach($priceRanges as $range)
                <a href="#" class="px-2 md:px-4 py-2 rounded-lg bg-slate-100 text-slate-700 text-[70%] md:text-sm font-semibold hover:bg-slate-200 hover:text-slate-900 transition-colors text-center flex items-center justify-center">
                    {{ $range }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Latest Phones --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6 px-1">
            <h2 class="text-md lg:text-lg font-bold text-slate-900 tracking-tight">Just Arrived</h2>
            <a href="#"
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
                    class="group relative bg-white rounded-xl border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200 block">
                    @if ($phone->created_at > now()->subDays(30))
                        <div class="absolute top-3 left-3 z-10">
                            <span
                                class="bg-slate-900 text-white text-[8px] md:text-[10px] lg:text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">New</span>
                        </div>
                    @endif

                    <div class="relative aspect-square bg-slate-50/50 p-1 group-hover:bg-slate-50 transition-colors">
                        @if ($phone->image)
                            <img src="{{ asset('storage/' . $phone->image) }}"
                                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3
                            class="font-bold text-slate-900 text-sm mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $phone->title }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">৳{{ number_format($phone->base_price) }}</span>
                            <div class="text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Popular Brands --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6 px-1">
            <h2 class="text-md lg:text-lg font-bold text-slate-900 tracking-tight">Popular Brands</h2>
            <a href="#"
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
                <a href="#"
                    class="group flex flex-col items-center justify-center bg-white border border-slate-100 rounded-xl p-4 hover:border-slate-300 transition-all duration-200">
                    <div
                        class="w-10 h-10 mb-2 relative grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                        @if ($brand->image)
                            <img src="{{ asset('storage/' . $brand->image) }}" class="w-full h-full object-contain"
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



    {{-- Official Phones --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6 px-1">
            <h2 class="text-md lg:text-lg font-bold text-slate-900 tracking-tight">Official Phones</h2>
            <a href="#"
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
                    class="group relative bg-white rounded-xl border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200 block">
                    <div class="relative aspect-square bg-slate-50/50 p-1 group-hover:bg-slate-50 transition-colors">
                        @if ($phone->image)
                            <img src="{{ asset('storage/' . $phone->image) }}"
                                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3
                            class="font-bold text-slate-900 text-sm mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $phone->title }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">৳{{ number_format($phone->base_price) }}</span>
                            <div class="text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Unofficial Phones --}}
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6 px-1">
            <h2 class="text-md lg:text-lg font-bold text-slate-900 tracking-tight">Unofficial Phones</h2>
            <a href="#"
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
                    class="group relative bg-white rounded-xl border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200 block">
                    <div class="relative aspect-square bg-slate-50/50 p-1 group-hover:bg-slate-50 transition-colors">
                        @if ($phone->image)
                            <img src="{{ asset('storage/' . $phone->image) }}"
                                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3
                            class="font-bold text-slate-900 text-sm mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $phone->title }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">৳{{ number_format($phone->base_price) }}</span>
                            <div class="text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Upcoming Phones --}}
    <div class="mb-0">
        <div class="flex items-center justify-between mb-6 px-1">
            <h2 class="text-md lg:text-lg font-bold text-slate-900 tracking-tight">Coming Soon</h2>
            <a href="#"
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
                    class="group relative bg-white rounded-xl border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200 block">
                    <div class="absolute top-3 left-3 z-10">
                        <span
                            class="bg-gray-400 text-white text-[8px] md:text-[10px] lg:text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">Upcoming</span>
                    </div>

                    <div class="relative aspect-square bg-slate-50/50 p-4 group-hover:bg-slate-50 transition-colors">
                        @if ($phone->image)
                            <img src="{{ asset('storage/' . $phone->image) }}"
                                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
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
                            <div class="text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

@endsection
