<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden mt-6">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-800">Reviews (0)</h2>
    </div>
    
    <div class="divide-y divide-slate-100">
        {{-- Placeholder for when there are no reviews --}}
        <div class="p-8 text-center text-slate-500 text-sm">
            No reviews yet. Be the first to review this product!
        </div>

        {{-- Example Review Item --}}
        <div class="p-4 md:p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-sm shrink-0">
                    JD
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-bold text-slate-900 text-sm">John Doe</h3>
                        <span class="text-xs text-slate-400">2 days ago</span>
                    </div>
                    
                    <div class="flex items-center gap-1 mb-2">
                        <div class="flex text-amber-400">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-xs font-medium text-slate-600 ml-1">5.0</span>
                    </div>

                    <div class="text-xs text-slate-500 mb-3">
                        Variant: <span class="font-medium text-slate-700">8GB/128GB Blue</span>
                    </div>

                    <p class="text-sm text-slate-600 mb-4 leading-relaxed">
                        Great phone! The camera is amazing and the battery lasts all day.
                    </p>

                    <div class="flex flex-wrap gap-4 mb-4">
                        <div>
                            <span class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1 block">Pros</span>
                            <div class="flex flex-wrap gap-1">
                                <span class="px-2 py-0.5 rounded bg-green-50 text-green-700 text-xs border border-green-100">Great Camera</span>
                                <span class="px-2 py-0.5 rounded bg-green-50 text-green-700 text-xs border border-green-100">Fast Charging</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1 block">Cons</span>
                            <div class="flex flex-wrap gap-1">
                                <span class="px-2 py-0.5 rounded bg-red-50 text-red-700 text-xs border border-red-100">No Headphone Jack</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Load More --}}
    <div class="p-4 border-t border-slate-100 text-center">
        <button class="px-4 py-2 rounded-sm border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors">
            Load More Reviews
        </button>
    </div>
</div>
