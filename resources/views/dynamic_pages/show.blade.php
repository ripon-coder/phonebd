@extends('layouts.app', [
    'title' => $dynamicPage->title . ' - Specs & Buying Guide',
    'meta_description' => $dynamicPage->meta_description ?? 'Check out our ' . $dynamicPage->title . ' buying guide. Best deals on ' . $dynamicPage->title . ' smartphones in Bangladesh.',
])

@section('og_image', asset('images/og-default.jpg'))
@section('og_type', 'article')

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
    "name": "{{ $dynamicPage->title }}"
  }]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ $dynamicPage->title }}",
  "description": "{{ $dynamicPage->meta_description ?? '' }}",
  "image": "{{ asset('images/og-default.jpg') }}",
  "datePublished": "{{ $dynamicPage->created_at->toIso8601String() }}",
  "dateModified": "{{ $dynamicPage->updated_at->toIso8601String() }}"
}
</script>
@endpush

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
                    <span class="ml-1 text-slate-500 md:ml-2">Buying Guide</span>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 font-medium text-slate-900 md:ml-2">{{ $dynamicPage->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-4 mt-2">
        {{-- Header --}}
        <div class="bg-white rounded-sm border border-slate-200 p-2 mb-2">
            <h1 class="text-md md:text-2xl font-bold text-slate-900 mb-1">{{ $dynamicPage->title }}</h1>
            <p class="text-sm text-slate-500">Explore our {{ $dynamicPage->title }} buying guide for mobile phones, prices, and specifications in Bangladesh.</p>
        </div>

        <div class="flex flex-col gap-3">
            @if($dynamicPage->youtube_link)
                <div class="bg-white rounded-sm border border-slate-200 p-2 lg:p-2 mb-0">
                    <div class="flex flex-col lg:flex-row gap-6">
                        {{-- Video Column --}}
                        <div class="w-full lg:w-80 shrink-0" x-data="{ videoModalOpen: false }">
                            <div class="aspect-w-1 aspect-h-1 relative group cursor-pointer" @click="videoModalOpen = true">
                                <img src="https://img.youtube.com/vi/{{ $dynamicPage->youtube_link }}/maxresdefault.jpg" 
                                     alt="Video Thumbnail" 
                                     class="w-full h-full object-cover rounded-sm">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/30 transition-colors rounded-sm">
                                    <div class="w-12 h-12 rounded-full bg-white/90 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-red-600 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Video Modal --}}
                            <template x-teleport="body">
                                <div x-show="videoModalOpen" 
                                     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     style="display: none;">
                                    
                                    <div class="relative w-full max-w-6xl aspect-video bg-black rounded-lg shadow-2xl overflow-hidden" @click.away="videoModalOpen = false">
                                        <button @click="videoModalOpen = false" class="absolute top-4 right-4 z-10 text-white/70 hover:text-white bg-black/50 hover:bg-black/70 rounded-full p-2 transition-colors">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <template x-if="videoModalOpen">
                                            <iframe src="https://www.youtube.com/embed/{{ $dynamicPage->youtube_link }}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Content Column --}}
                        @if($dynamicPage->content)
                            <div class="prose prose-sm md:prose-base prose-slate max-w-none flex-1 text-sm md:text-base">
                                {!! $dynamicPage->content !!}
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($dynamicPage->content)
                <div class="bg-white rounded-sm border border-slate-200 p-3 lg:p-4 prose prose-sm md:prose-base prose-slate max-w-none mb-0 text-sm md:text-base">
                    {!! $dynamicPage->content !!}
                </div>
            @endif
            {{-- Main Content --}}
            <div class="w-full">


                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-3">
                    @foreach ($dynamicPage->products as $phone)
                        <a href="{{ route('product.show', ['category_slug' => $phone->category->slug, 'product' => $phone->slug]) }}"
                            class="group relative bg-white rounded-sm border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200 block">
                            
                            <div class="relative aspect-square bg-slate-50/50 p-1 group-hover:bg-slate-50 transition-colors">
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
            </div>
        </div>
    </div>
@endsection
