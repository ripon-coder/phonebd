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

    {{-- Popular Brands --}}
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6 px-1">
            <h2 class="text-md lg:text-lg  font-bold text-slate-900 tracking-tight">Popular Brands</h2>
            <a href="#"
                class="text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
                View All
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-4">
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

    {{-- Latest Phones --}}
    <div class="mb-16">
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
                <div
                    class="group relative bg-white rounded-xl border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200">
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
                            <button class="text-slate-400 hover:text-slate-900 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Upcoming Phones --}}
    <div class="mb-20">
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
                <div
                    class="group relative bg-white rounded-xl border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200">
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
                            <button class="text-slate-400 hover:text-slate-900 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
