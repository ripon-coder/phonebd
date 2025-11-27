<div x-data="{ 
    isOpen: false,
    images: [],
    handleFileChange(event) {
        const files = Array.from(event.target.files);
        
        // Validate max files
        if (files.length > 4) {
            alert('You can upload a maximum of 4 photos.');
            event.target.value = ''; // Clear input
            return;
        }

        // Validate file size (7MB = 7 * 1024 * 1024 bytes)
        const maxSize = 7 * 1024 * 1024;
        const oversizedFiles = files.filter(file => file.size > maxSize);
        
        if (oversizedFiles.length > 0) {
            alert('Each photo must be less than 7MB.');
            event.target.value = ''; // Clear input
            return;
        }

        this.images = [];
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.images.push(e.target.result);
            };
            reader.readAsDataURL(file);
        });
    },
    clearImages() {
        this.images = [];
        document.getElementById('sample-photos').value = '';
    }
}"
@open-sample-drawer.window="isOpen = true"
@keydown.escape.window="isOpen = false">

    {{-- Drawer Overlay --}}
    <div x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm"
        @click="isOpen = false"
        style="display: none;">
    </div>

    {{-- Drawer Content --}}
    <div x-show="isOpen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 z-[101] h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto"
        style="display: none;">
        
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50 sticky top-0 z-10 backdrop-blur-md">
            <h3 class="font-bold text-slate-900 text-lg">Add Camera Samples</h3>
            <button @click="isOpen = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        {{-- Form --}}
        <div class="p-6 space-y-6">
            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- User Info --}}
                <div>
                    <label for="sample_name" class="block text-sm font-medium text-slate-700 mb-2">Your Name <span class="text-red-500">*</span></label>
                    <input type="text" id="sample_name" name="name" required class="w-full rounded-sm border-slate-200 focus:border-blue-500 focus:ring-blue-500 focus:outline-none text-sm bg-slate-50 p-3" placeholder="Enter your name">
                </div>

                {{-- Device Variant --}}
                <div>
                    <label for="sample_variant" class="block text-sm font-medium text-slate-700 mb-2">Device Variant (Optional)</label>
                    <input type="text" id="sample_variant" name="variant" class="w-full rounded-sm border-slate-200 focus:border-blue-500 focus:ring-blue-500 focus:outline-none text-sm bg-slate-50 p-3" placeholder="e.g. 8GB/128GB">
                </div>

                {{-- Photo Upload --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Upload Photos <span class="text-red-500">*</span></label>
                    
                    {{-- Dropzone --}}
                    <div class="flex items-center justify-center w-full" x-show="images.length === 0">
                        <label for="sample-photos" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-blue-50 hover:border-blue-400 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3 group-hover:bg-blue-100 transition-colors">
                                    <svg class="w-6 h-6 text-slate-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="mb-2 text-sm text-slate-500"><span class="font-semibold text-slate-700">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-slate-400">JPG, PNG or GIF (Max 7MB, up to 4 photos)</p>
                            </div>
                            <input id="sample-photos" type="file" name="photos[]" multiple accept="image/*" class="hidden" @change="handleFileChange" required />
                        </label>
                    </div>

                    {{-- Image Previews --}}
                    <div class="grid grid-cols-2 gap-4" x-show="images.length > 0" style="display: none;">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="relative group h-32 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center">
                                <img :src="image" class="max-w-full max-h-full object-contain">
                            </div>
                        </template>
                        
                        {{-- Change Photos Button --}}
                        <div class="h-32 flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50 text-slate-500 hover:text-blue-500 transition-colors" @click="clearImages(); document.getElementById('sample-photos').click()">
                            <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            <span class="text-xs font-medium">Change</span>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        Upload Samples
                    </button>
                    <p class="text-xs text-slate-400 text-center mt-3">By uploading, you agree to our terms and conditions.</p>
                </div>
            </form>
        </div>
    </div>
</div>
