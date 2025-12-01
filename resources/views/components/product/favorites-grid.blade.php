@foreach ($products as $phone)
    <a href="{{ route('product.show', ['category_slug' => $phone->category->slug, 'product' => $phone->slug]) }}"
        class="group relative bg-white rounded-sm border border-slate-100 p-0 overflow-hidden hover:border-slate-300 transition-all duration-200 block">
        
        <div class="relative aspect-square bg-slate-50/50 p-1 group-hover:bg-slate-50 transition-colors">
            @if ($phone->image)
                <img src="{{ asset('storage/' . $phone->image) }}"
                    class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-300">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
        </div>

        <div class="p-4">
            <h3
                class="font-bold text-slate-900 text-sm mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                {{ $phone->title }}</h3>
            <div class="flex items-center justify-between">
                <span class="md:text-base lg:text-md text-sm font-bold text-blue-600">
                    @if ($phone->base_price)
                        ৳{{ number_format($phone->base_price) }}
                    @else
                        Expected Soon
                    @endif
                </span>
                <div x-data="favorite({{ $phone->id }})" @click.prevent="toggle()" :class="isFavorite ? 'text-red-500' : 'text-slate-400 hover:text-red-500'" class="transition-colors cursor-pointer">
                    <svg class="w-5 h-5" :fill="isFavorite ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </a>
@endforeach
