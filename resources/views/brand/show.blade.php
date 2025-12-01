@extends('layouts.app', [
    'title' => $brand->name . ' Mobile Phones Price in Bangladesh',
    'meta_description' => 'Latest ' . $brand->name . ' mobile phones price in Bangladesh. Check out ' . $brand->name . ' smartphone specifications, reviews, and features.',
])

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
                    <a href="{{ route('brands.index') }}" class="ml-1 text-sm font-medium text-slate-700 hover:text-blue-600 md:ml-2">Brands</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 font-medium text-slate-900 md:ml-2">{{ $brand->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-6 mt-4">
        {{-- Brand Header --}}
        <div class="bg-white rounded-sm border border-slate-200 p-2 mb-5 flex items-center gap-5">
            <div class="w-20 h-20 shrink-0 flex items-center justify-center bg-slate-50 rounded-sm p-2 border border-slate-100">
                @if ($brand->image)
                    <img src="{{ asset('storage/' . $brand->image) }}" class="w-full h-full object-contain" alt="{{ $brand->name }}">
                @else
                    <span class="text-3xl font-bold text-slate-300">{{ substr($brand->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-900 mb-1">{{ $brand->name }}</h1>
                <p class="text-sm text-slate-500">{{ $brand->meta_description ?? 'Explore the latest ' . $brand->name . ' mobile phones, prices, and specifications in Bangladesh.' }}</p>
            </div>
        </div>

        <div class="flex flex-col gap-8">
            {{-- Main Content --}}
            <div class="w-full">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                    <div class="flex items-baseline justify-between w-full sm:w-auto sm:justify-start gap-2">
                        <h2 class="text-xl lg:text-xl font-bold text-slate-900 tracking-tight">{{ $brand->name }} Devices</h2>
                        <span class="text-slate-500 text-sm">{{ $products->total() }} devices found</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
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
                                    <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">
                                        @if ($phone->base_price)
                                            ৳{{ number_format($phone->base_price) }}
                                        @else
                                            Expected Soon
                                        @endif
                                    </span>
                                    <div x-data="favorite({{ $phone->id }})" @click.prevent="toggle()" :class="isFavorite ? 'text-red-500' : 'text-slate-400 hover:text-red-500'" class="transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" :fill="isFavorite ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
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
