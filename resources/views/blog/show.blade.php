@extends('layouts.app')

@section('title', $post->title)
@section('og_image', $post->featured_image ? $post->getImageUrl('featured_image') : asset('images/fallback-img.png'))
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
    "name": "Blogs",
    "item": "{{ route('blogs.index') }}"
  }@if($post->category),{
    "@type": "ListItem",
    "position": 3,
    "name": "{{ $post->category->name }}",
    "item": "{{ route('blogs.category', $post->category->slug) }}"
  },{
    "@type": "ListItem",
    "position": 4,
    "name": "{{ $post->title }}"
  }@else,{
    "@type": "ListItem",
    "position": 3,
    "name": "{{ $post->title }}"
  }@endif]
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "image": "{{ $post->featured_image ? $post->getImageUrl('featured_image') : asset('images/fallback-img.png') }}",
  "datePublished": "{{ $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "PhoneBD Editor"
  }
}
</script>
@endpush

@section('content')
    <div class="py-4 md:py-6">
        {{-- Breadcrumb --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
                        <svg class="w-3 h-3 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('blogs.index') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors md:ml-2">Blogs</a>
                    </div>
                </li>
                @if($post->category)
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('blogs.category', $post->category->slug) }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors md:ml-2">{{ $post->category->name }}</a>
                    </div>
                </li>
                @endif
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2 line-clamp-1">{{ $post->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-3">
                <article class="bg-white rounded-sm border border-gray-200 overflow-hidden">
                    @if($post->featured_image)
                        <div class="relative h-64 md:h-96 w-full">
                            <img 
                                src="{{ $post->getImageUrl('featured_image') }}" 
                                alt="{{ $post->title }}" 
                                class="w-full h-full object-cover"
                            >
                        </div>
                    @endif

                    <div class="p-6 md:p-8 lg:p-10">
                        <header class="mb-8 border-b border-gray-100 pb-8">
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-4">
                                @if($post->category)
                                    <a href="{{ route('blogs.category', $post->category->slug) }}" class="text-blue-600 font-semibold hover:underline">
                                        {{ $post->category->name }}
                                    </a>
                                @else
                                    <span class="flex items-center text-slate-500 font-medium">
                                        <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        No Category Found
                                    </span>
                                @endif
                                <span class="text-gray-300">&bull;</span>
                                <span class="flex items-center">
                                    {{ $post->created_at->format('F d, Y') }}
                                </span>
                            </div>

                            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">
                                {{ $post->title }}
                            </h1>
                        </header>

                        <div class="prose prose-lg prose-slate max-w-none">
                            {!! $post->content !!}
                        </div>
                        
                        {{-- Share / Tags section --}}
                        <div class="mt-10 pt-8 border-t border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Share this post</h3>
                            <div class="flex gap-3">
                                <button class="w-9 h-9 rounded-sm bg-[#1877F2] text-white flex items-center justify-center hover:opacity-90 transition-opacity">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                </button>
                                <button class="w-9 h-9 rounded-sm bg-[#0A66C2] text-white flex items-center justify-center hover:opacity-90 transition-opacity">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Categories</h3>
                    </div>
                    <div class="p-2">
                        @forelse($categories as $category)
                            <a href="{{ route('blogs.category', $category->slug) }}" class="flex items-center justify-between group p-3 rounded-sm hover:bg-gray-50 transition-colors">
                                <span class="text-sm font-medium text-gray-600 group-hover:text-blue-600 transition-colors">{{ $category->name }}</span>
                                <span class="text-xs font-medium text-gray-400 group-hover:text-blue-600 transition-colors">
                                    {{ $category->posts_count }}
                                </span>
                            </a>
                        @empty
                            <div class="p-4 text-center text-slate-500 text-sm">
                                No categories found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
