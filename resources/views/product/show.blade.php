@extends('layouts.app')

@section('title', $product->title)
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
    {{-- Breadcrumb --}}
    <nav class="flex mb-6 text-sm text-slate-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 font-medium text-slate-900">{{ $product->brand->name }}</span>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 font-medium text-slate-500 truncate max-w-[150px] md:max-w-xs">{{ $product->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-8 space-y-4">
            
            {{-- Product Hero --}}
            <div class="bg-white rounded-sm p-4 md:p-4 shadow-sm border border-slate-100">
                <div class="grid grid-cols-2 gap-3 md:gap-8 items-stretch">
                    {{-- Image --}}
                    <div class="relative group h-full">
                        <div class="h-full bg-slate-50 rounded-sm overflow-hidden flex items-center justify-center p-2 md:p-4 w-full md:max-w-sm md:mx-auto">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="text-slate-300">
                                    <svg class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- Info --}}
                    <div>
                        <h1 class="text-base md:text-2xl font-bold text-slate-900 tracking-tight mb-1 md:mb-2">{{ $product->title }}</h1>
                        <div class="flex items-center gap-2 mb-2 md:mb-4">
                            <span class="text-xs md:text-sm font-medium text-slate-500">Brand:</span>
                            <a href="#" class="text-xs md:text-sm font-semibold text-blue-600 hover:underline">{{ $product->brand->name }}</a>
                        </div>

                        {{-- Ratings --}}
                        <div class="flex items-center gap-2 mb-3 md:mb-6">
                            <div class="flex items-center text-amber-400">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs md:text-sm font-medium text-slate-500">(4.8/5)</span>
                        </div>

                        <div class="mb-3 md:mb-6">
                            <span class="text-xs md:text-sm text-slate-500 block mb-1">Price in Bangladesh</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-lg md:text-2xl font-bold text-slate-900">
                                    @if ($product->base_price)
                                        ৳{{ number_format($product->base_price) }}
                                    @else
                                        TBA
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        {{-- Quick Actions --}}
                        <div class="flex gap-3 mt-3 md:mt-6">
                            <button class="flex-1 px-3 py-2 md:px-6 md:py-3 rounded-sm border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-colors text-xs md:text-base flex items-center justify-center gap-1.5 md:gap-2">
                                <svg class="w-3.5 h-3.5 md:w-5 md:h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                <span><span class="hidden md:inline">Add</span> Rating</span>
                            </button>
                            <button class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-sm bg-slate-100 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Variant Prices --}}
                @if($product->variantPrices->count() > 0)
                    <div class="mt-6 md:mt-8 pt-6 border-t border-slate-100">
                        <h3 class="text-sm font-medium text-slate-500 mb-3">Available Variants</h3>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($product->variantPrices as $variant)
                                <div class="group flex items-center justify-between p-4 rounded-sm bg-slate-50 border border-slate-100 hover:border-blue-500 hover:bg-white transition-all duration-300 cursor-default">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-xs md:text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">{{ $variant->ram }} / {{ $variant->storage }}</span>
                                            <span class="block text-[10px] md:text-xs font-medium text-slate-500">Official</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-base md:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">৳{{ number_format($variant->amount) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Specifications --}}
            <div class="bg-white rounded-sm border border-slate-100 overflow-hidden">
                <div class="p-3 border-b border-slate-100">
                    <h2 class="text-[15px] md:text-xl font-bold text-slate-900">Full Specifications</h2>
                </div>
                
                <div class="space-y-2 md:space-y-2 p-2 md:p-2">
                    @forelse($product->specGroups as $group)
                        <div class="bg-white rounded-sm border border-slate-150 overflow-hidden">
                            <div class="px-2 py-2 md:px-6 md:py-3 border-b border-slate-100 flex items-center gap-2">
                                <h3 class="text-xs md:text-sm font-bold text-slate-800 uppercase tracking-wide">
                                    {{ $group->name }}
                                </h3>
                            </div>
                            <div class="">
                                <table class="w-full text-left">
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach($group->items as $item)
                                            <tr class="group/row hover:bg-slate-50/50 transition-colors">
                                                <td class="py-2 px-2 md:py-3 md:pr-4 align-top w-[35%] md:w-[30%] text-xs md:text-sm font-medium text-slate-500 bg-slate-100">
                                                    {{ $item->key }}
                                                </td>
                                                <td class="py-2 px-2 md:py-3 md:pr-4 align-top text-xs md:text-md font-semibold text-slate-900 break-words group-hover/row:text-blue-700 transition-colors">
                                                    {{ $item->value }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500">
                            No specifications available yet.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- FAQs --}}
            @if($product->faqs->count() > 0)
                <div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-3 border-b border-slate-100">
                        <h2 class="text-[15px] md:text-xl font-bold text-slate-900">Frequently Asked Questions</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($product->faqs as $faq)
                            <div x-data="{ open: false }" class="bg-white">
                                <button @click="open = !open" class="w-full text-left px-6 py-4 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                    <span class="font-semibold text-[13px] md:text-sm text-slate-900">{{ $faq->question }}</span>
                                    <span class="ml-6 flex-shrink-0 text-slate-400">
                                        <svg x-show="!open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                        <svg x-show="open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </span>
                                </button>
                                <div x-show="open" class="px-6 pb-4 pt-2 text-slate-600 text-sm" style="display: none;">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Description --}}
            @if($product->short_description || $product->raw_html)
                <div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-3 border-b border-slate-100">
                        <h2 class="text-[15px] md:text-xl font-bold text-slate-900">Description</h2>
                    </div>
                    <div class="p-3 prose prose-sm text-slate-600 max-w-none text-[14px] md:text-[15px]">
                        @if($product->is_raw_html && $product->raw_html)
                            {!! $product->raw_html !!}
                        @else
                            {!! nl2br(e($product->short_description)) !!}
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar (Right Column) --}}
        <div class="lg:col-span-4 space-y-4">
            {{-- Disclaimer --}}
            <div class="bg-blue-50 rounded-sm p-5 border border-blue-100">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-xs text-blue-800 leading-relaxed">
                        <strong>Disclaimer:</strong> The price and specifications shown may be different from the actual product. We cannot guarantee that the information on this page is 100% correct. Please check with the retailer before purchasing.
                    </div>
                </div>
            </div>
            {{-- Similar Price Products --}}
            @if(isset($similarPriceProducts) && $similarPriceProducts->count() > 0)
                <div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-900">Similar Price</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($similarPriceProducts as $similar)
                            <a href="{{ route('product.show', ['category_slug' => $similar->category->slug, 'product' => $similar->slug]) }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors group">
                                <div class="w-16 h-16 bg-slate-50 rounded-lg flex items-center justify-center p-2 shrink-0 border border-slate-100">
                                    @if($similar->image)
                                        <img src="{{ asset('storage/' . $similar->image) }}" alt="{{ $similar->title }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2">{{ $similar->title }}</h4>
                                    <div class="mt-1 font-bold text-blue-600 text-sm">
                                        @if($similar->base_price)
                                            ৳{{ number_format($similar->base_price) }}
                                        @else
                                            TBA
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Similar Products --}}
            @if(isset($similarProducts) && $similarProducts->count() > 0)
                <div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-900">Similar Items</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($similarProducts as $similar)
                            <a href="{{ route('product.show', ['category_slug' => $similar->category->slug, 'product' => $similar->slug]) }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors group">
                                <div class="w-16 h-16 bg-slate-50 rounded-lg flex items-center justify-center p-2 shrink-0 border border-slate-100">
                                    @if($similar->image)
                                        <img src="{{ asset('storage/' . $similar->image) }}" alt="{{ $similar->title }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2">{{ $similar->title }}</h4>
                                    <div class="mt-1 font-bold text-blue-600 text-sm">
                                        @if($similar->base_price)
                                            ৳{{ number_format($similar->base_price) }}
                                        @else
                                            TBA
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>


@endsection
