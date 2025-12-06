@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)
@section('og_image', $page->featured_image ? Storage::url($page->featured_image) : asset('images/fallback-img.png'))
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
    "name": "{{ $page->title }}"
  }]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "{{ $page->title }}",
  "description": "{{ $page->meta_description ?? '' }}",
  "datePublished": "{{ $page->created_at->toIso8601String() }}",
  "dateModified": "{{ $page->updated_at->toIso8601String() }}"
}
</script>
@endpush

@section('content')
    <div class="bg-white">
        {{-- Breadcrumb --}}
        <div class="bg-slate-50 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-1 sm:px-1 lg:px-1 py-1.5">
                <nav class="flex text-sm text-slate-500">
                    <a href="{{ route('home') }}" class="flex items-center hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Home
                    </a>
                    <span class="mx-2">/</span>
                    <span class="text-slate-900 font-medium">{{ $page->title }}</span>
                </nav>
            </div>
        </div>

        {{-- Page Content --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">{{ $page->title }}</h1>
            
            @if($page->featured_image)
                <div class="mb-2 rounded-sm overflow-hidden shadow-lg">
                    <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-auto object-cover">
                </div>
            @endif

            <div class="prose prose-sm md:prose-base prose-slate max-w-none prose-headings:font-bold prose-a:text-blue-600 hover:prose-a:text-blue-700 prose-img:rounded-xl">
                {!! $page->content !!}
            </div>
        </div>
    </div>
@endsection
