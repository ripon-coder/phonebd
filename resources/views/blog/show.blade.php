@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-4xl font-bold">{{ $post->title }}</h1>
        <p class="text-gray-600 mt-2">
            Posted in <a href="{{ route('blog.category', $post->category->slug) }}" class="text-blue-600 hover:underline">{{ $post->category->name }}</a>
            on {{ $post->created_at->format('M d, Y') }}
        </p>

        @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full rounded-lg my-8">
        @endif

        <div class="prose max-w-none mt-8">
            {!! $post->content !!}
        </div>
    </div>
@endsection
