@extends('layouts.app')

@section('title', 'Search Results for ' . $query)
@section('meta_description', 'Search results for ' . $query . ' on PhoneBD.')
@section('og_image', asset('images/fallback-img.png'))
@section('og_type', 'website')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Home",
    "item": "{{ route('home') }}"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "Search Results"
  }]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SearchResultsPage",
  "mainEntity": [{
    "@type": "ItemList",
    "itemListElement": [
      @foreach($products as $index => $phone)
      {
        "@type": "ListItem",
        "position": {{ $index + 1 }},
        "url": "{{ route('product.show', ['category_slug' => $phone->category->slug, 'product' => $phone->slug]) }}",
        "name": "{{ $phone->title }}"
      }@if(!$loop->last),@endif
      @endforeach
    ]
  }]
}
</script>
@endpush

@section('content')
    <div class="py-3">
        {{-- Breadcrumb --}}
        <nav class="flex mb-2 text-sm text-slate-500" aria-label="Breadcrumb">
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
                        <span class="ml-1 font-medium text-slate-900 md:ml-2">Search Results</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="mb-4">
            <h1 class="text-md md:text-2xl font-bold text-slate-900">Search Results for "{{ $query }}"</h1>
        </div>

        @if($brands->count() > 0)
            <div class="mb-4">
                <h2 class="text-lg md:text-xl font-semibold text-slate-900 mb-2">Brands</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach($brands as $brand)
                        <a href="{{ route('brands.show', $brand->slug) }}" class="bg-white rounded-sm border border-slate-200 p-4 flex items-center justify-center hover:border-blue-500 transition-colors">
                            @if($brand->image)
                                <img src="{{ $brand->getImageUrl('image') }}" alt="{{ $brand->name }}" class="max-h-12 w-auto" loading="lazy">
                            @else
                                <span class="text-slate-900 font-medium">{{ $brand->name }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($products->count() > 0)
            <div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($products as $phone)
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
                                        alt="{{ $phone->title }}"
                                        class="w-full h-full object-cover" loading="lazy">
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
                <div class="mt-6">
                    {{ $products->appends(['q' => $query])->links() }}
                </div>
            </div>
        @elseif($brands->count() === 0)
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-slate-900 mb-2">No results found</h2>
                <p class="text-slate-500">We couldn't find any devices or brands matching "{{ $query }}"</p>
            </div>
        @endif
    </div>
@endsection
