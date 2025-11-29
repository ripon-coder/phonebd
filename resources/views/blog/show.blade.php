@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="py-8 md:py-12 max-w-4xl mx-auto">
        {{-- Breadcrumb / Back --}}
        <div class="mb-8">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center text-gray-500 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Blog
            </a>
        </div>

        <article class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @if($post->featured_image)
                <div class="relative h-64 md:h-96 w-full">
                    <img 
                        src="{{ asset('storage/' . $post->featured_image) }}" 
                        alt="{{ $post->title }}" 
                        class="w-full h-full object-cover"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
            @endif

            <div class="p-6 md:p-10 lg:p-12">
                <header class="mb-8">
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-4">
                        @if($post->category)
                            <a href="{{ route('blog.category', $post->category->slug) }}" class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-medium hover:bg-blue-100 transition-colors">
                                {{ $post->category->name }}
                            </a>
                        @endif
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $post->created_at->format('F d, Y') }}
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
                        {{ $post->title }}
                    </h1>
                </header>

                <div class="prose prose-lg prose-blue max-w-none text-gray-700">
                    {!! $post->content !!}
                </div>
                
                {{-- Share / Tags section could go here --}}
                <div class="mt-12 pt-8 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Share this post</h3>
                    <div class="flex gap-4">
                        {{-- Social share buttons placeholder --}}
                        <button class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </button>
                        <button class="w-10 h-10 rounded-full bg-blue-800 text-white flex items-center justify-center hover:bg-blue-900 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection
