<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden mb-4" 
    x-data="{ 
        isOpen: false, 
        activeImage: 0, 
        images: [
            'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1551355738-13f686a22198?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512054502232-10a0a035d672?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1592434134753-a70baf7979d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1533228100845-08145b01de14?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
        ],
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
        <button class="text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Add Samples
        </button>
    </div>
    
    <div class="p-3">
        <div class="grid grid-cols-3 gap-2">
            {{-- Thumbnails --}}
            <template x-for="(image, index) in images.slice(0, 6)" :key="index">
                <div @click="openLightbox(index)" class="aspect-square bg-slate-100 rounded-sm overflow-hidden group relative cursor-pointer">
                    <img :src="image" alt="Camera Sample" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    {{-- Overlay for the last visible item if there are more images --}}
                    <div x-show="index === 5 && images.length > 6" 
                         class="absolute inset-0 bg-black/50 flex items-center justify-center text-white font-bold text-lg backdrop-blur-[2px] transition-colors hover:bg-black/60">
                        <span x-text="'+' + (images.length - 6)"></span>
                    </div>
                    
                    {{-- Hover effect for other items --}}
                    <div x-show="!(index === 5 && images.length > 6)" class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                </div>
            </template>
        </div>
    </div>

    {{-- Lightbox Modal --}}
    <div x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm"
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
            <img :src="images[activeImage]" class="max-w-full max-h-full object-contain shadow-2xl rounded-sm" alt="Full view">
            
            {{-- Counter --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/80 text-sm font-medium bg-black/40 px-3 py-1 rounded-full">
                <span x-text="activeImage + 1"></span> / <span x-text="images.length"></span>
            </div>
        </div>
    </div>
</div>
