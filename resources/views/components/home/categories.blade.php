@props(['categories'])

<div class="mb-6 mt-2">
    <div class="flex items-center justify-between mb-3 px-1">
        <h2 class="text-md lg:text-lg  font-semibold text-slate-900 tracking-tight">Browse Categories</h2>
        <a href="{{ route('categories.index') }}"
            class="md:hidden text-slate-500 hover:text-slate-900 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 group">
            View All
            <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    {{-- Mobile: Horizontal Scroll, Desktop: Flex Wrap --}}
    <div
        class="flex flex-nowrap md:flex-wrap gap-1 overflow-x-auto md:overflow-visible pb-1 md:pb-0 snap-x snap-mandatory hide-scrollbar">
        @foreach ($categories as $category)
            <a href="{{ route('products.index', ['categories[]' => $category->id]) }}"
                class="snap-start shrink-0 px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-600 text-sm font-medium hover:border-slate-900 hover:text-slate-900 transition-all duration-200 shadow-sm">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</div>
