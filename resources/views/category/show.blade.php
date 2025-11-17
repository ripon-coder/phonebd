@extends('layouts.app')

@section('title', $category->name . ' Phones')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold">{{ $category->name }}</h1>
        <p class="text-gray-600">{{ $category->meta_description }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <a href="{{ route('product.show', ['category' => $product->category->slug, 'product' => $product->slug]) }}">
                    <img src="{{ $product->meta_image ? asset('storage/' . $product->meta_image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="font-bold text-lg">{{ $product->title }}</h2>
                        <p class="text-gray-600">{{ $product->brand->name }}</p>
                        <p class="text-blue-600 font-bold mt-2">{{ number_format($product->base_price) }} BDT</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endsection
