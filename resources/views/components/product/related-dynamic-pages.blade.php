@props(['brand'])

@if($brand->dynamicPages->where('is_active', true)->count() > 0)
    <div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-900">More from {{ $brand->name }}</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($brand->dynamicPages->where('is_active', true)->sortBy('sort_order') as $page)
                <a href="{{ route('dynamic_pages.show', $page->slug) }}" class="block p-4 hover:bg-slate-50 transition-colors group">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $page->title }}</span>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
