@php
    // Format reviews to match AJAX response
    $formattedReviews = $approvedReviews->map(function($review) {
        $avgRating = collect([
            $review->rating_design,
            $review->rating_performance,
            $review->rating_camera,
            $review->rating_battery
        ])->filter()->avg();
        
        return [
            'id' => $review->id,
            'name' => $review->name,
            'review' => $review->review,
            'variant' => $review->variant,
            'pros' => $review->pros ?? [],
            'cons' => $review->cons ?? [],
            'images' => $review->images ?? [],
            'avg_rating' => $avgRating,
            'created_at' => $review->created_at->diffForHumans(),
            'no_spam_rating' => $review->no_spam_rating,
        ];
    })->values()->toArray();
@endphp

{{-- Data Injection --}}
<script>
    window.initialReviews = @json($formattedReviews);
</script>

<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden mt-3" 
     x-data="{ 
        lightboxOpen: false, 
        lightboxImage: '',
        reviews: window.initialReviews || [],
        page: 1,
        hasMore: {{ $totalReviews > 5 ? 'true' : 'false' }},
        loading: false,
        productId: {{ $product->id }},
        
        async loadMore() {
            if (this.loading) return;
            
            this.loading = true;
            this.page++;
            
            try {
                const response = await fetch(`/products/${this.productId}/reviews?page=${this.page}&t=${new Date().getTime()}`);
                const data = await response.json();
                
                // Filter out duplicates based on ID
                const newReviews = data.reviews.filter(newReview => 
                    !this.reviews.some(existing => existing.id === newReview.id)
                );
                
                this.reviews = [...this.reviews, ...newReviews];
                this.hasMore = data.hasMore;
            } catch (error) {
                console.error('Failed to load reviews:', error);
            } finally {
                this.loading = false;
            }
        },
        
        getInitials(name) {
            return name ? name.substring(0, 2).toUpperCase() : '??';
        },

        getSpamBadge(score) {
            if (score === null || score === undefined) return null;
            if (score >= 6) return { 
                title: 'No Spam', 
                class: 'bg-emerald-50 text-emerald-700 border-emerald-200', 
                icon: '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' />' 
            };
            if (score >= 3) return { 
                title: 'Low Spam', 
                class: 'bg-amber-50 text-amber-700 border-amber-200',
                icon: '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z\' />'
            };
            return { 
                title: 'High Spam', 
                class: 'bg-rose-50 text-rose-700 border-rose-200',
                icon: '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0z\' />'
            };
        }
     }">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-800">Reviews ({{ $totalReviews }})</h2>
    </div>
    
    <div class="divide-y divide-slate-100">
        <template x-for="(review, index) in reviews" :key="index">
            <div class="p-4 md:p-6">
                <div class="flex items-start gap-4">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-sm shrink-0" x-text="getInitials(review.name)">
                        </div>
                        <template x-if="review.no_spam_rating !== null && review.no_spam_rating !== undefined">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white flex items-center justify-center shadow-sm"
                                 :class="getSpamBadge(review.no_spam_rating).class"
                                 :title="getSpamBadge(review.no_spam_rating).title">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-html="getSpamBadge(review.no_spam_rating).icon"></svg>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-bold text-slate-900 text-sm" x-text="review.name"></h3>
                            <span class="text-xs text-slate-400" x-text="review.created_at"></span>
                        </div>
                        
                        <template x-if="review.avg_rating">
                            <div class="flex items-center gap-1 mb-2">
                                <div class="flex text-amber-400">
                                    <template x-for="i in 5" :key="'star-' + index + '-' + i">
                                        <svg class="w-4 h-4" :class="i <= Math.round(review.avg_rating) ? 'fill-current' : 'fill-slate-200'" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </template>
                                </div>
                                <span class="text-xs font-medium text-slate-600 ml-1" x-text="review.avg_rating.toFixed(1)"></span>
                            </div>
                        </template>

                        <template x-if="review.variant">
                            <div class="text-xs text-slate-500 mb-3">
                                Variant: <span class="font-medium text-slate-700" x-text="review.variant"></span>
                            </div>
                        </template>

                        <p class="text-sm text-slate-600 mb-4 leading-relaxed" x-text="review.review"></p>

                        <template x-if="(review.pros && review.pros.length > 0) || (review.cons && review.cons.length > 0)">
                            <div class="flex flex-wrap gap-4 mb-4">
                                <template x-if="review.pros && review.pros.length > 0">
                                    <div>
                                        <span class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1 block">Pros</span>
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="(pro, i) in review.pros" :key="'pro-' + index + '-' + i">
                                                <span class="px-2 py-0.5 rounded bg-green-50 text-green-700 text-xs border border-green-100" x-text="pro"></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="review.cons && review.cons.length > 0">
                                    <div>
                                        <span class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1 block">Cons</span>
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="(con, i) in review.cons" :key="'con-' + index + '-' + i">
                                                <span class="px-2 py-0.5 rounded bg-red-50 text-red-700 text-xs border border-red-100" x-text="con"></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="review.images && review.images.length > 0">
                            <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
                                <template x-for="(image, i) in review.images" :key="'img-' + index + '-' + i">
                                    <img :src="image" 
                                         :alt="'Review by ' + review.name" 
                                         loading="lazy"
                                         @click="lightboxImage = image; lightboxOpen = true"
                                         class="h-16 flex-shrink-0 object-contain rounded border border-slate-200 cursor-pointer hover:opacity-90 transition-opacity bg-slate-50">
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
        
        <template x-if="reviews.length === 0">
            <div class="p-8 text-center text-slate-500 text-sm">
                No reviews yet. Be the first to review this product!
            </div>
        </template>
    </div>

    {{-- Load More Button --}}
    <template x-if="hasMore">
        <div class="p-4 border-t border-slate-100 text-center">
            <button @click="loadMore()" 
                    :disabled="loading"
                    class="px-4 py-2 rounded-sm border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!loading">Load More Reviews</span>
                <span x-show="loading" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading...
                </span>
            </button>
        </div>
    </template>

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
             loading="lazy"
             class="max-w-full max-h-full object-contain rounded shadow-2xl">
    </div>
</div>
