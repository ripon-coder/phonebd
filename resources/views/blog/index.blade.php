@extends('layouts.app')

@section('title', 'Blog')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold">Blog</h1>
        <p class="text-gray-600">News, reviews, and updates from the mobile world.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($posts as $post)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <a href="{{ route('blog.show', $post->slug) }}">
                    <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://via.placeholder.com/300x200' }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="font-bold text-lg">{{ $post->title }}</h2>
                        <p class="text-gray-600 text-sm mt-1">{{ $post->created_at->format('M d, Y') }}</p>
                        <p class="text-gray-700 mt-2">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
@endsection
