<x-filament-panels::page>
    <div x-data="{
        processing: false,
        total: 100,
        processed: 0,
        
        async startScraping() {
            this.processing = true;
            this.processed = 0;
            this.total = 100; // Placeholder until initialized
            
            // Initialize backend
            const count = await $wire.initScraping();
            
            if (count > 0) {
                 this.total = count;
                 await this.loop();
            } else {
                 this.processing = false;
            }
        },

        async loop() {
            while (this.processing) {
                // Call processNext and wait for it
                // We check if queue is empty on backend by the return value
                const hasMore = await $wire.processNext();
                
                // Update local processed count based on entangle or manual increment
                // But since we are waiting for backend, $wire.processed should be updated by livewire response
                
                if (!hasMore) {
                    this.processing = false;
                    // Finalize called in backend already
                    break;
                }
            }
        }
    }">

        <!-- Progress Bar Overlay -->
        <div x-cloak x-show="processing" style="margin-bottom: 1.5rem; padding: 1rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb;">
            <div style="display: flex; justify-content: space-between; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
                <span style="display: flex; align-items: center; gap: 0.5rem;">
                     <!-- Spinner icon with inline styles and attributes -->
                     <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation: spin 1s linear infinite; color: #2563eb;">
                        <style>
                            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
                        </style>
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity: 0.25;"></circle>
                        <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" style="opacity: 0.75;"></path>
                     </svg>
                     <span>Processing: <span x-text="$wire.currentUrl || 'Initializing...'" style="max-width: 300px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: bottom;"></span></span>
                </span>
                <span>
                    <span x-text="$wire.processed"></span> / <span x-text="$wire.total"></span>
                </span>
            </div>
            
            <div style="width: 100%; background-color: #e5e7eb; border-radius: 9999px; height: 1rem; overflow: hidden;">
                <div style="background-color: #2563eb; height: 1rem; border-radius: 9999px; transition: width 300ms ease-out;"
                     :style="'width: ' + (($wire.processed / ($wire.total || 1)) * 100) + '%'">
                </div>
            </div>
             <div style="text-align: right; font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;" x-text="Math.round(($wire.processed / ($wire.total || 1)) * 100) + '%'"></div>
        </div>

        <form wire:submit.prevent="startScraping">
            {{ $this->form }}
            <br>
            <div class="mt-4">
                <button type="button" 
                        x-on:click="startScraping"
                        class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-color-primary gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-500 text-white dark:bg-custom-500 hover:bg-custom-600 dark:hover:bg-custom-400 focus-visible:ring-custom-500/50 dark:focus-visible:ring-custom-400/50 fi-ac-btn-action"
                        style="background-color: rgb(234 179 8); color: black;"
                        x-bind:disabled="processing"
                        :class="{'opacity-50 cursor-not-allowed': processing}">
                    
                    <span x-show="!processing">Start Scraping</span>
                    <span x-show="processing">Scraping in progress...</span>
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
