@extends('layouts.app')

@section('title', $product->title)
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
    {{-- Breadcrumb --}}
    <nav class="flex mb-3 text-sm text-slate-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center hover:text-slate-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('brands.show', $product->brand) }}" class="ml-1 md:ml-2 font-medium text-slate-500 hover:text-slate-900 transition-colors truncate max-w-[80px] md:max-w-none">{{ $product->brand->name }}</a>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 lg:gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-8 space-y-3">
            
            {{-- Product Hero --}}
            <div class="bg-white rounded-sm p-3 md:p-3 shadow-sm border border-slate-100">
                <div class="grid grid-cols-2 gap-3 md:gap-6 items-stretch">
                    {{-- Image --}}
                    <div class="relative group h-full">
                        <div class="h-full bg-slate-50 rounded-sm overflow-hidden flex items-center justify-center w-full md:max-w-sm md:mx-auto">
                            @if ($product->image)
                                <img src="{{ $product->getImageUrl('image') }}" alt="{{ $product->title }}" class="w-full h-full object-contain">
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
                        <h1 class="text-base md:text-2xl font-bold text-slate-900 tracking-tight mb-1">{{ $product->title }}</h1>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs md:text-sm font-medium text-slate-500">Brand:</span>
                            <a href="{{ route('brands.show', $product->brand) }}" class="text-xs md:text-sm font-semibold text-blue-600 hover:underline">{{ $product->brand->name }}</a>
                        </div>

                        {{-- Ratings --}}
                        <div class="flex items-center gap-2 mb-2 md:mb-4">
                            <div class="flex items-center text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 {{ $i <= round($averageRating) ? 'fill-current' : 'text-slate-300 fill-current' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs md:text-sm font-medium text-slate-500">({{ $averageRating }}/5)</span>
                        </div>

                        <div class="mb-2 md:mb-4">
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
                        <div class="flex gap-3 mt-2 md:mt-4">
                            <a href="#reviews" class="hidden lg:flex flex-1 px-3 py-1.5 md:px-5 md:py-2 rounded-sm border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-colors text-xs md:text-base items-center justify-center gap-1.5 md:gap-2">
                                <svg class="w-3.5 h-3.5 md:w-5 md:h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <span>Write a Review</span>
                            </a>
                            <a href="#reviews-mobile" class="lg:hidden flex flex-1 px-3 py-1.5 md:px-5 md:py-2 rounded-sm border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-colors text-xs md:text-base items-center justify-center gap-1.5 md:gap-2">
                                <svg class="w-3.5 h-3.5 md:w-5 md:h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <span>Review</span>
                            </a>
                            <button x-data="favorite({{ $product->id }})" @click="toggle()" :class="isFavorite ? 'bg-red-50 text-red-500' : 'bg-slate-100 text-slate-400 md:hover:bg-red-50 md:hover:text-red-500'" class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-sm transition-colors">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Variant Prices --}}
                @if($product->variantPrices->count() > 0)
                    <div class="mt-4 md:mt-6 pt-4 border-t border-slate-100">
                        <h3 class="text-sm font-medium text-slate-500 mb-3">Available Variants</h3>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($product->variantPrices as $variant)
                                <div class="group flex items-center justify-between p-3 rounded-sm bg-slate-50 border border-slate-100 hover:border-blue-500 hover:bg-white transition-all duration-300 cursor-default">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="block text-sm md:text-base font-bold text-slate-900 group-hover:text-blue-700 transition-colors">{{ $variant->ram }} / {{ $variant->storage }}</span>
                                            <div class="flex flex-wrap gap-1.5 mt-1">
                                                @if($variant->market_status)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] md:text-[11px] font-medium border {{ $variant->market_status === 'official' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                                        {{ ucfirst($variant->market_status) }}
                                                    </span>
                                                @endif
                                                
                                                @if($variant->variant_type)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] md:text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                        {{ $variant->variant_type }}
                                                    </span>
                                                @endif

                                                @if(!$variant->market_status && !$variant->variant_type)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] md:text-[11px] font-medium bg-blue-50 text-blue-600 border border-blue-100">
                                                        Official
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($variant->is_expected)
                                            <span class="block text-[10px] md:text-xs font-bold text-amber-500 uppercase tracking-wider mb-0.5">Expected</span>
                                        @endif
                                        <span class="block text-lg md:text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors">৳{{ number_format($variant->amount) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Specifications --}}
            <div class="bg-white rounded-sm border border-slate-100 overflow-hidden">
                <div class="p-2 border-b border-slate-100">
                    <h2 class="text-[15px] md:text-xl font-bold text-slate-900">Full Specifications</h2>
                </div>
                
                <div class="space-y-1 md:space-y-1 p-1 md:p-1">
                    @forelse($product->specGroups as $group)
                        <div class="bg-white rounded-sm border border-slate-150 overflow-hidden">
                            <div class="px-2 py-1.5 md:px-4 md:py-2 border-b border-slate-100 flex items-center gap-2">
                                <h3 class="text-sm md:text-sm font-bold text-slate-800 uppercase tracking-wide">
                                    {{ $group->name }}
                                </h3>
                            </div>
                            <div class="">
                                <table class="w-full text-left">
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach($group->items as $item)
                                            <tr class="group/row hover:bg-slate-50/50 transition-colors">
                                                <td class="py-1.5 px-2 md:py-2 md:pr-4 align-top w-[35%] md:w-[30%] text-sm md:text-sm font-medium text-slate-500 bg-slate-100">
                                                    {{ $item->key }}
                                                </td>
                                                <td class="py-1.5 px-2 md:py-2 md:pr-4 align-top text-sm text-slate-900 break-words group-hover/row:text-blue-700 transition-colors">
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
            
            {{-- Disclaimer --}}
            <div class="bg-blue-50 rounded-sm p-3 border border-blue-100 mb-6">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-xs text-blue-800 leading-relaxed">
                        <strong>Disclaimer:</strong> The price and specifications shown may be different from the actual product. We cannot guarantee that the information on this page is 100% correct. Please check with the retailer before purchasing.
                    </div>
                </div>
            </div>

            {{-- FAQs --}}
            @if($product->faqs->count() > 0)
                <div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-2 border-b border-slate-100">
                        <h2 class="text-[15px] md:text-xl font-bold text-slate-900">Frequently Asked Questions</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($product->faqs as $faq)
                            <div x-data="{ open: false }" class="bg-white">
                                <button @click="open = !open" class="w-full text-left px-4 py-3 flex justify-between items-center hover:bg-slate-50 transition-colors">
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
                                <div x-show="open" class="px-4 pb-4 pt-0 text-slate-600 text-sm" style="display: none;">
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
                    <div class="p-2 border-b border-slate-100">
                        <h2 class="text-[15px] md:text-xl font-bold text-slate-900">Description</h2>
                    </div>
                    <div class="p-2 prose prose-sm text-slate-600 max-w-none text-[14px] md:text-[15px]">
                        @if($product->is_raw_html && $product->raw_html)
                            {!! $product->raw_html !!}
                        @else
                            {!! nl2br(e($product->short_description)) !!}
                        @endif
                    </div>
                </div>
            @endif


            {{-- Write a Review (Desktop Only) --}}
            <div id="reviews" class="hidden lg:block">
                @if($totalReviews < $product->review_count_max)
                    @include('components.product.review-form')
                @else
                    <div class="bg-white rounded-sm shadow-sm border border-slate-100 p-4 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-50 mb-4">
                            <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Review Limit Reached</h3>
                        <p class="text-slate-600 max-w-md mx-auto">
                            This product has reached its maximum limit of {{ $product->review_count_max }} reviews. Thank you for your interest!
                        </p>
                    </div>
                @endif

                {{-- Reviews List --}}
                @include('components.product.review-list')
            </div>
        </div>

        {{-- Sidebar (Right Column) --}}
        <div class="lg:col-span-4 space-y-3">

            {{-- Camera Samples --}}
            @include('components.product.camera-sample')
            @include('components.product.camera-sample-form')

            {{-- Feature Scores --}}
            @if($product->productPerformance && ($product->productPerformance->gaming_fps || $product->productPerformance->battery_sot || $product->productPerformance->camera_score))
                @include('components.product.feature-scores', ['performance' => $product->productPerformance])
            @endif

            {{-- Antutu Score --}}
            @if($product->antutuScore && $product->antutuScore->total_score)
                @include('components.product.antutu-score', ['score' => $product->antutuScore])
            @endif

            {{-- Write a Review (Mobile Only) --}}
            <div id="reviews-mobile" class="lg:hidden">
                @if($totalReviews < $product->review_count_max)
                    @include('components.product.review-form')
                @else
                    <div class="bg-white rounded-sm shadow-sm border border-slate-100 p-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-50 mb-4">
                            <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Review Limit Reached</h3>
                        <p class="text-slate-600 max-w-md mx-auto">
                            This product has reached its maximum limit of {{ $product->review_count_max }} reviews. Thank you for your interest!
                        </p>
                    </div>
                @endif

                {{-- Reviews List --}}
                @include('components.product.review-list')
            </div>

            {{-- Similar Price Products --}}
            @if(isset($similarPriceProducts) && $similarPriceProducts->count() > 0)
                <div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-2 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-900">Similar Price</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($similarPriceProducts as $similar)
                            <a href="{{ route('product.show', ['category_slug' => $similar->category->slug, 'product' => $similar->slug]) }}" class="flex items-center gap-4 p-3 hover:bg-slate-50 transition-colors group">
                                <div class="w-20 h-20 bg-slate-50 rounded-sm flex items-center justify-center shrink-0 border border-slate-100">
                                    @if($similar->image)
                                        <img src="{{ $similar->getImageUrl('image') }}" alt="{{ $similar->title }}" class="w-full h-full object-contain mix-blend-multiply">
                                    @else
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm  text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2">{{ $similar->title }}</h4>
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

            {{-- Related Dynamic Pages --}}
            <x-product.related-dynamic-pages :brand="$product->brand" />

            {{-- Similar Products --}}
            @if(isset($similarProducts) && $similarProducts->count() > 0)
                <div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-2 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-900">Similar Items</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($similarProducts as $similar)
                            <a href="{{ route('product.show', ['category_slug' => $similar->category->slug, 'product' => $similar->slug]) }}" class="flex items-center gap-4 p-3 hover:bg-slate-50 transition-colors group">
                                <div class="w-20 h-20 bg-slate-50 rounded-sm flex items-center justify-center shrink-0 border border-slate-100">
                                    @if($similar->image)
                                        <img src="{{ $similar->getImageUrl('image') }}" alt="{{ $similar->title }}" class="w-full h-full object-contain mix-blend-multiply">
                                    @else
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2">{{ $similar->title }}</h4>
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
