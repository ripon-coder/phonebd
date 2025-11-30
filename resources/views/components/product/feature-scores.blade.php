@props(['performance'])

@php
    $gamingWidth = 0;
    if ($performance->gaming_fps) {
        preg_match('/(\d+)/', $performance->gaming_fps, $matches);
        $val = isset($matches[1]) ? floatval($matches[1]) : 0;
        $gamingWidth = min(100, ($val / 120) * 100);
    }

    $batteryWidth = 0;
    if ($performance->battery_sot) {
        $minutes = 0;
        if (preg_match('/(\d+)h/', $performance->battery_sot, $h)) {
             $minutes += intval($h[1]) * 60;
        }
        if (preg_match('/(\d+)m/', $performance->battery_sot, $m)) {
             $minutes += intval($m[1]);
        }
        
        if ($minutes > 0) {
            $batteryWidth = min(100, ($minutes / (12 * 60)) * 100);
        } else {
             // Fallback if just a number
             preg_match('/(\d+)/', $performance->battery_sot, $matches);
             $val = isset($matches[1]) ? floatval($matches[1]) : 0;
             // If value is small (<24), assume hours
             if ($val < 24) $batteryWidth = min(100, ($val / 12) * 100);
             else $batteryWidth = 50; // Unknown format
        }
    }

    $cameraWidth = 0;
    if ($performance->camera_score) {
        if (strpos($performance->camera_score, '/') !== false) {
            $parts = explode('/', $performance->camera_score);
            if (count($parts) == 2 && is_numeric($parts[1]) && $parts[1] > 0) {
                $cameraWidth = min(100, (floatval($parts[0]) / floatval($parts[1])) * 100);
            }
        } else {
            $val = floatval($performance->camera_score);
            if ($val <= 10) $cameraWidth = ($val / 10) * 100;
            else $cameraWidth = min(100, $val);
        }
    }
@endphp

<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden mb-4">
    <div class="p-3 border-b border-slate-100 bg-slate-50/50">
        <h3 class="font-bold text-slate-900">Performance & Features</h3>
    </div>
    
    <div class="p-4 space-y-6">
        {{-- Gaming FPS Calculator --}}
        @if($performance->gaming_fps)
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 rounded-md bg-indigo-50 text-indigo-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-slate-700 text-sm">Gaming FPS</span>
                    </div>
                    <span class="font-bold text-indigo-600 text-sm">{{ $performance->gaming_fps }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $gamingWidth }}%"></div>
                </div>
                <p class="text-xs text-slate-500">Estimated average on high settings</p>
            </div>
        @endif

        {{-- Battery SOT Estimator --}}
        @if($performance->battery_sot)
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 rounded-md bg-emerald-50 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-slate-700 text-sm">Battery SOT</span>
                    </div>
                    <span class="font-bold text-emerald-600 text-sm">{{ $performance->battery_sot }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $batteryWidth }}%"></div>
                </div>
                <p class="text-xs text-slate-500">Screen-on time (mixed usage)</p>
            </div>
        @endif

        {{-- Camera Score --}}
        @if($performance->camera_score)
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 rounded-md bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-slate-700 text-sm">Camera Score</span>
                    </div>
                    <span class="font-bold text-blue-600 text-sm">{{ $performance->camera_score }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $cameraWidth }}%"></div>
                </div>
                <p class="text-xs text-slate-500">Based on AI analysis</p>
            </div>
        @endif

        {{-- Disclaimer --}}
        <div class="pt-4 border-t border-slate-100">
            <p class="text-[11px] text-slate-400 leading-relaxed text-center">
                * These values are calculated automatically by our system and may not be 100% accurate.
            </p>
        </div>
    </div>
</div>
