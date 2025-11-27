@php
    $approvedReviews = $product->reviews()->where('is_approve', true)->latest()->get();
    $reviewCount = $approvedReviews->count();
@endphp

<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden mt-6" x-data="{ lightboxOpen: false, lightboxImage: '' }">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-800">Reviews ({{ $reviewCount }})</h2>
    </div>
    
    <div class="divide-y divide-slate-100">
        @forelse($approvedReviews as $review)
            <div class="p-4 md:p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-sm shrink-0">
                        {{ strtoupper(substr($review->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-bold text-slate-900 text-sm">{{ $review->name }}</h3>
                            <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        
                        @php
                            $avgRating = collect([
                                $review->rating_design,
                                $review->rating_performance,
                                $review->rating_camera,
                                $review->rating_battery
                            ])->filter()->avg();
                        @endphp

                        @if($avgRating)
                            <div class="flex items-center gap-1 mb-2">
                                <div class="flex text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'fill-current' : 'fill-slate-200' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs font-medium text-slate-600 ml-1">{{ number_format($avgRating, 1) }}</span>
                            </div>
                        @endif

                        @if($review->variant)
                            <div class="text-xs text-slate-500 mb-3">
                                Variant: <span class="font-medium text-slate-700">{{ $review->variant }}</span>
                            </div>
                        @endif

                        <p class="text-sm text-slate-600 mb-4 leading-relaxed">
                            {{ $review->review }}
                        </p>

                        @if($review->pros || $review->cons)
                            <div class="flex flex-wrap gap-4 mb-4">
                                @if($review->pros && count($review->pros) > 0)
                                    <div>
                                        <span class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1 block">Pros</span>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($review->pros as $pro)
                                                <span class="px-2 py-0.5 rounded bg-green-50 text-green-700 text-xs border border-green-100">{{ $pro }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if($review->cons && count($review->cons) > 0)
                                    <div>
                                        <span class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1 block">Cons</span>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($review->cons as $con)
                                                <span class="px-2 py-0.5 rounded bg-red-50 text-red-700 text-xs border border-red-100">{{ $con }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($review->images && count($review->images) > 0)
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach($review->images as $image)
                                    <img src="{{ $image }}" 
                                         alt="Review image" 
                                         @click="lightboxImage = '{{ $image }}'; lightboxOpen = true"
                                         class="h-32 object-contain rounded border border-slate-200 cursor-pointer hover:opacity-90 transition-opacity bg-slate-50">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-slate-500 text-sm">
                No reviews yet. Be the first to review this product!
            </div>
        @endforelse
    </div>

    {{-- Image Lightbox --}}
    <div x-show="lightboxOpen" 
         x-cloak
         @click="lightboxOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4"
         style="display: none;">
        <button @click="lightboxOpen = false" 
                class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img :src="lightboxImage" 
             @click.stop
             class="max-w-full max-h-full object-contain rounded shadow-2xl">
    </div>
</div>
