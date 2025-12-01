@props(['dynamicPages'])

@if($dynamicPages->isNotEmpty())
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5 px-1">
            <h2 class="text-md lg:text-lg font-bold text-slate-900 tracking-tight">Buying Guide</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($dynamicPages as $page)
                <a href="{{ route('dynamic_pages.show', $page->slug) }}" 
                   class="group flex items-center justify-between px-4 py-3 bg-white border border-slate-200 rounded-sm hover:border-slate-800 hover:text-slate-900 transition-all duration-200">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors">
                            {{ $page->title }}
                        </span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-900 transition-colors ml-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endforeach
        </div>
    </div>
@endif
