@extends('layouts.app')

@section('title', 'Blog')
@section('meta_description', 'Stay up to date with the latest mobile technology trends, in-depth reviews, and industry insights on PhoneBD Blog.')
@section('og_image', asset('images/og-default.jpg'))
@section('og_type', 'website')

@section('content')
    <div class="py-2 md:py-4">
        <div class="text-center mb-4 md:mb-6">
            <h1 class="text-2xl md:text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 mb-1 md:mb-2">
                Latest News & Reviews
            </h1>
            <p class="text-sm md:text-base text-gray-600 max-w-2xl mx-auto px-4">
                Stay up to date with the latest mobile technology trends, in-depth reviews, and industry insights.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-3">
                @if($posts->count() > 0)
                    <div class="space-y-3">
                        @foreach($posts as $post)
                            <article class="bg-white rounded-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row group h-full md:h-36 transition-colors hover:border-blue-200">
                                <a href="{{ route('blog.show', $post->slug) }}" class="md:w-48 relative overflow-hidden h-40 md:h-auto shrink-0">
                                    <img 
                                        src="{{ $post->getImageUrl('featured_image') ?? 'https://placehold.co/600x400/e2e8f0/1e293b?text=No+Image' }}" 
                                        alt="{{ $post->title }}" 
                                        class="w-full h-full object-cover"
                                    >
                                </a>
                                
                                <div class="p-3 md:p-4 flex-1 flex flex-col justify-center">
                                    <div class="flex items-center gap-2 text-xs font-medium text-gray-500 mb-1">
                                        @if($post->category)
                                            <span class="text-blue-600 uppercase tracking-wide">
                                                {{ $post->category->name }}
                                            </span>
                                            <span class="text-gray-300">&bull;</span>
                                        @endif
                                        <time datetime="{{ $post->created_at->toIso8601String() }}">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </time>
                                    </div>
                                    
                                    <h2 class="text-base md:text-lg font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">
                                        <a href="{{ route('blog.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                    
                                    <p class="text-gray-600 text-xs md:text-sm line-clamp-2 mb-0 leading-relaxed">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-sm border border-gray-200">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No posts found</h3>
                        <p class="text-gray-500 mt-1">Check back later for new updates.</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Categories</h3>
                    </div>
                    <div class="p-2">
                        @forelse($categories as $category)
                            <a href="{{ route('blog.category', $category->slug) }}" class="flex items-center justify-between group p-3 rounded-sm hover:bg-gray-50 transition-colors">
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
