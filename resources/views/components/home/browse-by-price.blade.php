<div class="mb-6">
    <div class="flex items-center justify-between mb-3 px-1">
        <h2 class="text-md lg:text-lg font-semibold text-slate-900 tracking-tight">Browse by Price</h2>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @php
            $priceRanges = [
                '1-10000' => '৳1 - ৳10,000',
                '10000-15000' => '৳10,000 - ৳15,000',
                '15000-20000' => '৳15,000 - ৳20,000',
                '20000-30000' => '৳20,000 - ৳30,000',
                '30000-50000' => '৳30,000 - ৳50,000',
                '50000-1000000' => '৳50,000+',
            ];
        @endphp
        @foreach($priceRanges as $rangeKey => $label)
            @php
                [$min, $max] = explode('-', $rangeKey);
            @endphp
            <a href="{{ route('products.index', ['min_price' => $min, 'max_price' => $max]) }}" class="group flex items-center justify-between px-3 py-2 bg-white border border-slate-200 rounded-sm hover:border-slate-800 hover:text-slate-900 transition-all duration-200">
                <div class="flex flex-col">
                    <span class="text-xs text-slate-500 font-medium uppercase tracking-wider">Budget</span>
                    <span class="text-sm text-slate-900 transition-colors whitespace-nowrap">{{ $label }}</span>
                </div>
                <div class="hidden md:flex w-8 h-8 rounded-sm bg-slate-50 items-center justify-center group-hover text-slate-900 transition-colors">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-900 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
</div>
