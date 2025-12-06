@props(['brands'])

<div class="mb-6">
    <div class="flex items-center justify-between mb-3 px-1">
        <h2 class="text-md lg:text-lg font-semibold text-slate-900 tracking-tight">Popular Brands</h2>
        <a href="{{ route('brands.index') }}"
            class="text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
            View All
            <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-1 md:gap-4">
        @foreach ($brands as $brand)
            <a href="{{ route('brands.show', $brand) }}"
                class="group flex flex-col items-center justify-center bg-white border border-slate-100 rounded-sm p-3 hover:border-slate-300 transition-all duration-200">
                <div
                    class="w-10 h-10 mb-2 relative grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                    @if ($brand->image)
                        <img loading="lazy" src="{{ $brand->getImageUrl('image') }}" class="w-full h-full object-contain"
                            alt="{{ $brand->name }}">
                    @else
                        {{-- Fallback Text/Icon if no image --}}
                        <span
                            class="text-xl font-bold text-slate-300 group-hover:text-slate-500">{{ substr($brand->name, 0, 1) }}</span>
                    @endif
                </div>
                <span
                    class="text-[11px] font-semibold text-slate-500 group-hover:text-slate-900 transition-colors uppercase tracking-wide text-center">{{ $brand->name }}</span>
            </a>
        @endforeach
    </div>
</div>
