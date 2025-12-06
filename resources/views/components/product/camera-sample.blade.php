@php
    $galleryData = collect([]);
    if (isset($cameraSamples)) {
        foreach ($cameraSamples as $sample) {
            if (is_array($sample->images)) {
                foreach ($sample->images as $image) {
                    $galleryData->push([
                        'url' => $image,
                        'name' => $sample->name,
                        'variant' => $sample->variant
                    ]);
                }
            }
        }
    }
@endphp

<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden mb-4" 
    x-data="{ 
        isOpen: false, 
        activeImage: 0, 
        images: {{ $galleryData->toJson() }},
        openLightbox(index) {
            this.activeImage = index;
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },
        nextImage() {
            this.activeImage = (this.activeImage + 1) % this.images.length;
        },
        prevImage() {
            this.activeImage = (this.activeImage - 1 + this.images.length) % this.images.length;
        }
    }"
    @keydown.escape.window="closeLightbox()"
    @keydown.arrow-right.window="if(isOpen) nextImage()"
    @keydown.arrow-left.window="if(isOpen) prevImage()">
    
    <div class="p-3 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <h3 class="font-bold text-slate-900">Camera Samples</h3>
        @if($totalCameraSamples < $product->sample_count_max && $product->is_sample)
            <button @click="$dispatch('open-sample-drawer')" class="text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add Samples
            </button>
        @endif
    </div>
    
    <div class="p-3">
        <template x-if="images.length === 0">
            @if($totalCameraSamples < $product->sample_count_max && $product->is_sample)
                <div @click="$dispatch('open-sample-drawer')" class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-slate-200 rounded-lg bg-slate-50 hover:bg-slate-100 hover:border-blue-300 cursor-pointer transition-all group">
                    <div class="w-12 h-12 rounded-full bg-white border border-slate-200 flex items-center justify-center mb-3 group-hover:border-blue-200 group-hover:text-blue-500 text-slate-400 transition-colors shadow-sm">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900 mb-1">No samples yet</h4>
                    <p class="text-xs text-slate-500 text-center">Be the first to upload camera samples taken with this device.</p>
                    <button class="mt-4 px-4 py-2 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-700 shadow-sm group-hover:text-blue-600 group-hover:border-blue-200 transition-colors">
                        Upload Photos
                    </button>
                </div>
            @else
                <div class="flex flex-col items-center justify-center p-8 border border-slate-100 rounded-lg bg-slate-50">
                    <div class="text-slate-400 mb-2">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm text-slate-500">No camera samples available.</p>
                </div>
            @endif
        </template>

        <template x-if="images.length > 0">
            <div class="grid grid-cols-3 gap-2">
                {{-- Thumbnails --}}
                <template x-for="(image, index) in images.slice(0, 6)" :key="index">
                    <div @click="openLightbox(index)" class="aspect-square bg-slate-100 rounded-sm overflow-hidden group relative cursor-pointer">
                        <img :src="image.url" :alt="image.name ? image.name + ' Camera Sample' : 'Camera Sample'" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        
                        {{-- Overlay for the last visible item if there are more images --}}
                        <div x-show="index === 5 && images.length > 6" 
                             class="absolute inset-0 bg-black/50 flex items-center justify-center text-white font-bold text-lg backdrop-blur-[2px] transition-colors hover:bg-black/60">
                            <span x-text="'+' + (images.length - 6)"></span>
                        </div>
                        
                        {{-- Hover effect for other items --}}
                        <div x-show="!(index === 5 && images.length > 6)" class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                </template>

                {{-- Add Button Tile (only if less than 6 images) --}}
                @if($totalCameraSamples < $product->sample_count_max)
                    <template x-if="images.length < 6">
                        <div @click="$dispatch('open-sample-drawer')" class="aspect-square bg-slate-50 rounded-sm border border-dashed border-slate-300 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-100 hover:border-blue-400 transition-all group">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-[10px] font-medium text-slate-500 mt-1 group-hover:text-blue-600">Add</span>
                        </div>
                    </template>
                @endif
            </div>
        </template>
    </div>

    {{-- Lightbox Modal --}}
    <div x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm"
        style="display: none;">
        
        {{-- Close Button --}}
        <button @click="closeLightbox()" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors z-50 p-2">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        {{-- Previous Button --}}
        <button @click.stop="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors z-50 p-2 bg-black/20 hover:bg-black/40 rounded-full">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>

        {{-- Next Button --}}
        <button @click.stop="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors z-50 p-2 bg-black/20 hover:bg-black/40 rounded-full">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>

        {{-- Main Image --}}
        <div class="relative w-full h-full flex items-center justify-center p-4 md:p-12" @click.outside="closeLightbox()">
            <img :src="images[activeImage]?.url" class="max-w-full max-h-full object-contain shadow-2xl rounded-sm" :alt="images[activeImage]?.name || 'Full view'">
            
            {{-- Info Overlay --}}
            <div class="absolute bottom-4 left-4 md:bottom-8 md:left-8 text-white z-50">
                <div class="bg-black/60 backdrop-blur-md rounded-lg p-4 max-w-xs md:max-w-md border border-white/10">
                    <p class="font-bold text-sm md:text-base" x-text="images[activeImage]?.name"></p>
                    <template x-if="images[activeImage]?.variant">
                        <p class="text-xs md:text-sm text-white/70 mt-0.5">Variant: <span x-text="images[activeImage]?.variant"></span></p>
                    </template>
                </div>
            </div>

            {{-- Counter --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/80 text-sm font-medium bg-black/40 px-3 py-1 rounded-full">
                <span x-text="activeImage + 1"></span> / <span x-text="images.length"></span>
            </div>
        </div>
    </div>
</div>
