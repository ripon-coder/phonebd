<div class="bg-white rounded-sm shadow-sm border border-slate-100 overflow-hidden" x-data="{ step: 1, rating: 0, hover: 0 }">
    {{-- Header --}}
    <div class="bg-slate-100 p-2 border-b border-slate-100">
        <h2 class="text-lg font-bold text-center mb-4 text-slate-800">Write a Review</h2>
        
        {{-- Stepper --}}
        <div class="flex items-center justify-between max-w-md mx-auto relative">
            {{-- Connecting Line --}}
            <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-200 -z-0 -translate-y-1/2"></div>
            
            {{-- Step 1 --}}
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300"
                    :class="step >= 1 ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-500'">
                    1
                </div>
                <span class="text-xs font-medium" :class="step >= 1 ? 'text-blue-600' : 'text-slate-500'">Basic</span>
            </div>

            {{-- Step 2 --}}
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300"
                    :class="step >= 2 ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-500'">
                    2
                </div>
                <span class="text-xs font-medium" :class="step >= 2 ? 'text-blue-600' : 'text-slate-500'">Details <span class="text-[10px] opacity-75 font-normal">(Optional)</span></span>
            </div>

            {{-- Step 3 --}}
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300"
                    :class="step >= 3 ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-500'">
                    3
                </div>
                <span class="text-xs font-medium" :class="step >= 3 ? 'text-blue-600' : 'text-slate-500'">Photos <span class="text-[10px] opacity-75 font-normal">(Optional)</span></span>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-8">
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Step 1: Basic (Required) --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="space-y-6">
                    {{-- Detailed Ratings --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6" x-data="{ 
                        ratings: { design: 0, performance: 0, camera: 0, battery: 0 },
                        hovers: { design: 0, performance: 0, camera: 0, battery: 0 }
                    }">
                        {{-- Design Rating --}}
                        <div class="text-center">
                            <label class="block text-xs md:text-sm font-medium text-slate-700 mb-2">Design</label>
                            <div class="flex items-center justify-center gap-0.5 md:gap-1">
                                <input type="hidden" name="rating_design" :value="ratings.design">
                                <template x-for="i in 5">
                                    <button type="button" 
                                        @click="ratings.design = i" 
                                        @mouseenter="hovers.design = i" 
                                        @mouseleave="hovers.design = 0"
                                        class="focus:outline-none transition-transform duration-200 hover:scale-110">
                                        <svg class="w-5 h-5 md:w-6 md:h-6" :class="(hovers.design || ratings.design) >= i ? 'text-amber-400 fill-current' : 'text-slate-200'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.545.044.77.77.326 1.163l-4.304 3.86a.562.562 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.304-3.86a.562.562 0 01.326-1.163l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Performance Rating --}}
                        <div class="text-center">
                            <label class="block text-xs md:text-sm font-medium text-slate-700 mb-2">Performance</label>
                            <div class="flex items-center justify-center gap-0.5 md:gap-1">
                                <input type="hidden" name="rating_performance" :value="ratings.performance">
                                <template x-for="i in 5">
                                    <button type="button" 
                                        @click="ratings.performance = i" 
                                        @mouseenter="hovers.performance = i" 
                                        @mouseleave="hovers.performance = 0"
                                        class="focus:outline-none transition-transform duration-200 hover:scale-110">
                                        <svg class="w-5 h-5 md:w-6 md:h-6" :class="(hovers.performance || ratings.performance) >= i ? 'text-amber-400 fill-current' : 'text-slate-200'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.545.044.77.77.326 1.163l-4.304 3.86a.562.562 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.304-3.86a.562.562 0 01.326-1.163l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Camera Rating --}}
                        <div class="text-center">
                            <label class="block text-xs md:text-sm font-medium text-slate-700 mb-2">Camera</label>
                            <div class="flex items-center justify-center gap-0.5 md:gap-1">
                                <input type="hidden" name="rating_camera" :value="ratings.camera">
                                <template x-for="i in 5">
                                    <button type="button" 
                                        @click="ratings.camera = i" 
                                        @mouseenter="hovers.camera = i" 
                                        @mouseleave="hovers.camera = 0"
                                        class="focus:outline-none transition-transform duration-200 hover:scale-110">
                                        <svg class="w-5 h-5 md:w-6 md:h-6" :class="(hovers.camera || ratings.camera) >= i ? 'text-amber-400 fill-current' : 'text-slate-200'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.545.044.77.77.326 1.163l-4.304 3.86a.562.562 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.304-3.86a.562.562 0 01.326-1.163l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Battery Rating --}}
                        <div class="text-center">
                            <label class="block text-xs md:text-sm font-medium text-slate-700 mb-2">Battery</label>
                            <div class="flex items-center justify-center gap-0.5 md:gap-1">
                                <input type="hidden" name="rating_battery" :value="ratings.battery">
                                <template x-for="i in 5">
                                    <button type="button" 
                                        @click="ratings.battery = i" 
                                        @mouseenter="hovers.battery = i" 
                                        @mouseleave="hovers.battery = 0"
                                        class="focus:outline-none transition-transform duration-200 hover:scale-110">
                                        <svg class="w-5 h-5 md:w-6 md:h-6" :class="(hovers.battery || ratings.battery) >= i ? 'text-amber-400 fill-current' : 'text-slate-200'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.545.044.77.77.326 1.163l-4.304 3.86a.562.562 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.304-3.86a.562.562 0 01.326-1.163l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Name Input --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Your Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required class="w-full rounded-sm border-slate-200 focus:border-blue-500 focus:ring-blue-500 focus:outline-none text-sm bg-slate-50 p-3" placeholder="Enter your name">
                    </div>

                    {{-- Review Text --}}
                    <div>
                        <label for="review" class="block text-sm font-medium text-slate-700 mb-2">Write your review <span class="text-red-500">*</span></label>
                        <textarea id="review" name="review" rows="2" required class="w-full rounded-sm border-slate-200 focus:border-blue-500 focus:ring-blue-500 focus:outline-none text-sm bg-slate-50 p-3" placeholder="What did you like or dislike? What did you use this product for?"></textarea>
                        <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Steps 2 & 3 are <span class="font-bold text-slate-700">optional</span>. You can skip them.</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Step 2: Optional Details --}}
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Pros (Tags) --}}
                        <div x-data="{ tags: [], newTag: '' }">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pros (Optional)</label>
                            <div class="relative">
                                <div class="flex flex-wrap gap-2 p-2 rounded-sm border border-slate-200 bg-slate-50 transition-all">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="tags.splice(index, 1)" class="ml-1.5 inline-flex items-center justify-center w-3.5 h-3.5 rounded-full text-green-600 hover:bg-green-200 focus:outline-none">
                                                <span class="sr-only">Remove</span>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <input type="hidden" name="pros[]" :value="tag">
                                        </span>
                                    </template>
                                    <input type="text" 
                                        x-model="newTag" 
                                        @keydown.enter.prevent="if(newTag.trim() !== '') { tags.push(newTag.trim()); newTag = ''; }" 
                                        @keydown.comma.prevent="if(newTag.trim() !== '') { tags.push(newTag.trim()); newTag = ''; }"
                                        @blur="if(newTag.trim() !== '') { tags.push(newTag.trim()); newTag = ''; }"
                                        class="flex-1 min-w-[120px] bg-transparent border-none focus:ring-0 focus:outline-none outline-none p-0 text-sm placeholder-slate-400" 
                                        placeholder="Type and press Enter...">
                                </div>
                                <p class="mt-1.5 text-xs text-slate-400">Press Enter or Comma to add tags</p>
                            </div>
                        </div>

                        {{-- Cons (Tags) --}}
                        <div x-data="{ tags: [], newTag: '' }">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Cons (Optional)</label>
                            <div class="relative">
                                <div class="flex flex-wrap gap-2 p-2 rounded-sm border border-slate-200 bg-slate-50 transition-all">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="tags.splice(index, 1)" class="ml-1.5 inline-flex items-center justify-center w-3.5 h-3.5 rounded-full text-red-600 hover:bg-red-200 focus:outline-none">
                                                <span class="sr-only">Remove</span>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <input type="hidden" name="cons[]" :value="tag">
                                        </span>
                                    </template>
                                    <input type="text" 
                                        x-model="newTag" 
                                        @keydown.enter.prevent="if(newTag.trim() !== '') { tags.push(newTag.trim()); newTag = ''; }" 
                                        @keydown.comma.prevent="if(newTag.trim() !== '') { tags.push(newTag.trim()); newTag = ''; }"
                                        @blur="if(newTag.trim() !== '') { tags.push(newTag.trim()); newTag = ''; }"
                                        class="flex-1 min-w-[120px] bg-transparent border-none focus:ring-0 focus:outline-none outline-none p-0 text-sm placeholder-slate-400" 
                                        placeholder="Type and press Enter...">
                                </div>
                                <p class="mt-1.5 text-xs text-slate-400">Press Enter or Comma to add tags</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Variant (Text Input) --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Which variant do you own? (Optional)</label>
                        <input type="text" name="variant" class="w-full rounded-sm border-slate-200 focus:border-slate-200 focus:ring-0 focus:outline-none p-3 text-sm bg-slate-50" placeholder="e.g. 8GB/128GB Blue Color">
                    </div>
                </div>
            </div>

            {{-- Step 3: Photos --}}
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <div class="space-y-6" x-data="{ 
                    images: [],
                    handleFileChange(event) {
                        const files = Array.from(event.target.files);
                        if (files.length > 2) {
                            alert('Maximum 2 photos allowed');
                            event.target.value = '';
                            this.images = [];
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
                        document.getElementById('dropzone-file').value = '';
                    }
                }">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Add Photos (Optional, Max 2)</label>
                        
                        {{-- Dropzone --}}
                        <div class="flex items-center justify-center w-full" x-show="images.length === 0">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-blue-50 hover:border-blue-400 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3 group-hover:bg-blue-100 transition-colors">
                                        <svg class="w-6 h-6 text-slate-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="mb-2 text-sm text-slate-500"><span class="font-semibold text-slate-700">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-slate-400">SVG, PNG, JPG or GIF (Max 2 photos)</p>
                                </div>
                                <input id="dropzone-file" type="file" name="photos[]" multiple accept="image/*" class="hidden" @change="handleFileChange" />
                            </label>
                        </div>

                        {{-- Image Previews --}}
                        <div class="grid grid-cols-2 gap-4" x-show="images.length > 0" style="display: none;">
                            <template x-for="(image, index) in images" :key="index">
                                <div class="relative group h-48 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center">
                                    <img :src="image" class="max-w-full max-h-full object-contain">
                                </div>
                            </template>
                            
                            {{-- Change Photos Button --}}
                            <div class="h-48 flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 text-slate-500 hover:text-blue-500 transition-colors" @click="clearImages(); document.getElementById('dropzone-file').click()">
                                <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                <span class="text-sm font-medium">Change Photos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-slate-100">
                {{-- Previous Button --}}
                <button type="button" 
                    x-show="step > 1" 
                    @click="step--"
                    class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition-colors text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Previous
                </button>
                <div x-show="step === 1"></div> {{-- Spacer --}}

                {{-- Next Button --}}
                <button type="button" 
                    x-show="step < 3" 
                    @click="if(step === 1) { 
                        const reviewEl = document.getElementById('review'); 
                        const nameEl = document.getElementById('name');
                        if(!nameEl.checkValidity()) { nameEl.reportValidity(); return; }
                        if(!reviewEl.checkValidity()) { reviewEl.reportValidity(); return; } 
                    } step++"
                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm flex items-center gap-2 shadow-sm shadow-blue-200">
                    Next Step
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>

                {{-- Submit Button --}}
                <button type="submit" 
                    x-show="step === 3" 
                    class="px-4 py-2 bg-slate-900 text-white font-medium rounded-lg hover:bg-slate-800 transition-colors text-sm shadow-lg shadow-slate-200">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>
