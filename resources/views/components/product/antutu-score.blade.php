@props(['score'])

@php
    $maxTotal = 3000000;
    $maxCpu = 900000;
    $maxGpu = 1300000;
    $maxMem = 600000;
    $maxUx = 500000;

    $totalWidth = $score->total_score ? min(100, ($score->total_score / $maxTotal) * 100) : 0;
    $cpuWidth = $score->cpu_score ? min(100, ($score->cpu_score / $maxCpu) * 100) : 0;
    $gpuWidth = $score->gpu_score ? min(100, ($score->gpu_score / $maxGpu) * 100) : 0;
    $memWidth = $score->mem_score ? min(100, ($score->mem_score / $maxMem) * 100) : 0;
    $uxWidth = $score->ux_score ? min(100, ($score->ux_score / $maxUx) * 100) : 0;
@endphp

<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden mb-4">
    <div class="p-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
        <h3 class="font-bold text-slate-900">Antutu Benchmark</h3>
        @if($score->version)
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-600">{{ $score->version }}</span>
        @endif
    </div>
    
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($score->total_score) }}</div>
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
            @if($score->cpu_score)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-slate-600">CPU</span>
                        <span class="font-bold text-slate-900">{{ number_format($score->cpu_score) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ $cpuWidth }}%"></div>
                    </div>
                </div>
            @endif

            {{-- GPU --}}
            @if($score->gpu_score)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-slate-600">GPU</span>
                        <span class="font-bold text-slate-900">{{ number_format($score->gpu_score) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ $gpuWidth }}%"></div>
                    </div>
                </div>
            @endif

            {{-- MEM --}}
            @if($score->mem_score)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-slate-600">MEM</span>
                        <span class="font-bold text-slate-900">{{ number_format($score->mem_score) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ $memWidth }}%"></div>
                    </div>
                </div>
            @endif

            {{-- UX --}}
            @if($score->ux_score)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="font-medium text-slate-600">UX</span>
                        <span class="font-bold text-slate-900">{{ number_format($score->ux_score) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ $uxWidth }}%"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Disclaimer --}}
        <div class="mt-4 pt-3 border-t border-slate-100">
            <p class="text-[11px] text-slate-400 leading-relaxed text-center">
                * Scores are based on lab tests and may vary by device condition and version.
            </p>
        </div>
    </div>
</div>
