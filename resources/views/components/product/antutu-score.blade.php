<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden mb-4">
    <div class="p-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
        <h3 class="font-bold text-slate-900">Antutu Benchmark</h3>
        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-600">v10</span>
    </div>
    
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-3xl font-black text-slate-900 tracking-tight">1,250,000</div>
                <div class="text-xs text-slate-500 font-medium mt-1">Total Score</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>

        {{-- Breakdown --}}
        <div class="space-y-3">
            {{-- CPU --}}
            <div class="space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="font-medium text-slate-600">CPU</span>
                    <span class="font-bold text-slate-900">350,000</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-orange-500 h-1.5 rounded-full" style="width: 80%"></div>
                </div>
            </div>

            {{-- GPU --}}
            <div class="space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="font-medium text-slate-600">GPU</span>
                    <span class="font-bold text-slate-900">450,000</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-orange-500 h-1.5 rounded-full" style="width: 90%"></div>
                </div>
            </div>

            {{-- MEM --}}
            <div class="space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="font-medium text-slate-600">MEM</span>
                    <span class="font-bold text-slate-900">250,000</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-orange-500 h-1.5 rounded-full" style="width: 60%"></div>
                </div>
            </div>

            {{-- UX --}}
            <div class="space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="font-medium text-slate-600">UX</span>
                    <span class="font-bold text-slate-900">200,000</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-orange-500 h-1.5 rounded-full" style="width: 50%"></div>
                </div>
            </div>
        </div>

        {{-- Disclaimer --}}
        <div class="mt-4 pt-3 border-t border-slate-100">
            <p class="text-[11px] text-slate-400 leading-relaxed text-center">
                * Scores are based on lab tests and may vary by device condition and version.
            </p>
        </div>
    </div>
</div>
