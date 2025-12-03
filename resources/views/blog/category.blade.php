@extends('layouts.app')

@section('title', 'Blog - ' . $category->name)
@section('meta_description', 'Read the latest articles and reviews in ' . $category->name . ' category on PhoneBD Blog.')
@section('og_image', asset('images/og-default.jpg'))
@section('og_type', 'website')

@section('content')
    <div class="py-4 md:py-6">
        <div class="text-center mb-8">
            <div class="inline-block mb-4">
                <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full font-medium text-sm">
                    Category
                </span>
            </div>
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">
                {{ $category->name }}
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-3">
                @if($posts->count() > 0)
                    <div class="space-y-4">
                        @foreach($posts as $post)
                            <article class="bg-white rounded-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row group h-full md:h-44 transition-colors hover:border-blue-200">
                                <a href="{{ route('blog.show', $post->slug) }}" class="md:w-56 relative overflow-hidden h-48 md:h-auto shrink-0">
                                    <img 
                                        src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://placehold.co/600x400/e2e8f0/1e293b?text=No+Image' }}" 
                                        alt="{{ $post->title }}" 
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                    >
                                </a>
                                
                                <div class="p-5 flex-1 flex flex-col justify-center">
                                    <div class="flex items-center gap-2 text-xs font-medium text-gray-500 mb-2">
                                        <span class="text-blue-600 uppercase tracking-wide">
                                            {{ $category->name }}
                                        </span>
                                        <span class="text-gray-300">&bull;</span>
                                        <time datetime="{{ $post->created_at->toIso8601String() }}">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </time>
                                    </div>
                                    
                                    <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">
                                        <a href="{{ route('blog.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                    
                                    <p class="text-gray-600 text-sm line-clamp-2 mb-0 leading-relaxed">
                                        {{ Str::limit(strip_tags($post->content), 140) }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-sm border border-gray-200">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
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

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Categories</h3>
                    </div>
                    <div class="p-2">
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.category', $cat->slug) }}" class="flex items-center justify-between group p-3 rounded-sm hover:bg-gray-50 transition-colors">
                                <span class="text-sm font-medium text-gray-600 group-hover:text-blue-600 transition-colors">{{ $cat->name }}</span>
                                <span class="text-xs font-medium text-gray-400 group-hover:text-blue-600 transition-colors">
                                    {{ $cat->posts_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
