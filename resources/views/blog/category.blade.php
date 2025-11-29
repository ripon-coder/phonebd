@extends('layouts.app')

@section('title', 'Blog - ' . $category->name)

@section('content')
    <div class="py-8 md:py-12">
        <div class="text-center mb-12">
            <div class="inline-block mb-4">
                <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full font-medium text-sm">
                    Category
                </span>
            </div>
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">
                {{ $category->name }}
            </h1>
            @if($category->meta_description)
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $category->meta_description }}
                </p>
            @endif
        </div>

        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <article class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full group">
                        <a href="{{ route('blog.show', $post->slug) }}" class="block relative overflow-hidden h-56">
                            <img 
                                src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://placehold.co/600x400/e2e8f0/1e293b?text=No+Image' }}" 
                                alt="{{ $post->title }}" 
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                <span class="bg-blue-50 text-blue-600 px-2.5 py-0.5 rounded-full font-medium text-xs">
                                    {{ $category->name }}
                                </span>
                                <span>&bull;</span>
                                <time datetime="{{ $post->created_at->toIso8601String() }}">
                                    {{ $post->created_at->format('M d, Y') }}
                                </time>
                            </div>
                            
                            <h2 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3 flex-1">
                                {{ Str::limit(strip_tags($post->content), 120) }}
                            </p>
                            
                            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 font-medium text-sm hover:text-blue-700 flex items-center gap-1 group/link">
                                    Read Article
                                    <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No posts found in this category</h3>
                <p class="text-gray-500 mt-1">Check back later for new updates.</p>
                <div class="mt-6">
                    <a href="{{ route('blog.index') }}" class="text-blue-600 font-medium hover:underline">View all posts</a>
                </div>
            </div>
        @endif
    </div>
@endsection
