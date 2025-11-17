@extends('layouts.app')

@section('title', $product->title)

@section('content')
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <img src="{{ $product->meta_image ? asset('storage/' . $product->meta_image) : 'https://via.placeholder.com/600x400' }}" alt="{{ $product->title }}" class="w-full rounded-lg">
            </div>
            <div>
                <h1 class="text-4xl font-bold">{{ $product->title }}</h1>
                <p class="text-gray-600 text-lg mt-2">{{ $product->brand->name }}</p>
                <p class="text-blue-600 font-bold text-3xl mt-4">{{ number_format($product->base_price) }} BDT</p>
                <div class="mt-4 text-sm text-gray-600">
                    <span class="font-bold">Status:</span> {{ ucfirst($product->status) }}
                </div>
                <div class="mt-4">
                    <p class="text-gray-700">{{ $product->short_description }}</p>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Specifications</h2>
            @foreach($product->specGroups as $group)
                <div class="mb-4">
                    <h3 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-2">{{ $group->name }}</h3>
                    <table class="w-full text-sm text-left text-gray-500">
                        <tbody>
                        @foreach($group->items as $item)
                            <tr class="bg-white border-b">
                                <th scope="row" class="py-2 px-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $item->key }}
                                </th>
                                <td class="py-2 px-4">
                                    {{ $item->value }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        @if($product->variantPrices->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Prices in Bangladesh</h2>
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3 px-6">Variant</th>
                        <th scope="col" class="py-3 px-6">Price</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($product->variantPrices as $variant)
                    <tr class="bg-white border-b">
                        <td class="py-4 px-6">{{ $variant->ram }} / {{ $variant->storage }}</td>
                        <td class="py-4 px-6">{{ number_format($variant->amount) }} {{ $variant->currency }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($product->faqs->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Frequently Asked Questions</h2>
            <div class="space-y-4">
                @foreach($product->faqs as $faq)
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full text-left flex justify-between items-center p-4 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        <span class="font-semibold">{{ $faq->question }}</span>
                        <span x-show="!open">+</span>
                        <span x-show="open">-</span>
                    </button>
                    <div x-show="open" class="p-4 bg-white border rounded-b-lg">
                        {{ $faq->answer }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection
