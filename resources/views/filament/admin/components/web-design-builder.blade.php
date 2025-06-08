{{-- Web Design Builder Component với Drag & Drop --}}
<div x-data="webDesignBuilder()" class="space-y-6">
    {{-- Header với thống kê --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Danh sách phần</h3>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span x-text="enabledSections"></span>/<span x-text="totalSections"></span> phần đang hiển thị
                </div>
                <button
                    @click="resetToDefault()"
                    class="px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                >
                    Reset mặc định
                </button>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="flex flex-wrap gap-2 mb-4">
            <button
                @click="toggleAllSections(true)"
                class="px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-900/50 rounded-lg transition-colors"
            >
                Hiện tất cả
            </button>
            <button
                @click="toggleAllSections(false)"
                class="px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition-colors"
            >
                Ẩn tất cả
            </button>
            <button
                @click="autoReorder()"
                class="px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 rounded-lg transition-colors"
            >
                Tự động sắp xếp
            </button>
        </div>
    </div>

    {{-- Sortable Sections List --}}
    <div 
        x-ref="sortableContainer"
        class="space-y-4"
    >
        <template x-for="(section, index) in sections" :key="section.key">
            <div 
                :class="{
                    'opacity-50': !section.enabled,
                    'ring-2 ring-blue-500 dark:ring-blue-400': section.enabled,
                    'ring-1 ring-gray-200 dark:ring-gray-700': !section.enabled
                }"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-all duration-200 hover:shadow-md cursor-move"
                :data-section="section.key"
            >
                {{-- Section Header --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        {{-- Drag Handle --}}
                        <div class="flex flex-col space-y-1 cursor-grab active:cursor-grabbing">
                            <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                            <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                            <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                            <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                            <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                            <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                        </div>
                        
                        {{-- Section Info --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="section.label"></h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400" x-text="section.description"></p>
                        </div>
                    </div>
                    
                    {{-- Controls --}}
                    <div class="flex items-center space-x-3">
                        {{-- Order Badge --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Thứ tự:</span>
                            <input 
                                type="number" 
                                x-model="section.order"
                                @change="updateOrder(section.key, $event.target.value)"
                                min="1" 
                                max="10"
                                class="w-16 px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                            >
                        </div>
                        
                        {{-- Toggle Switch --}}
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                x-model="section.enabled"
                                @change="toggleSection(section.key)"
                                class="sr-only peer"
                            >
                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 dark:peer-checked:bg-blue-500"></div>
                        </label>
                    </div>
                </div>
                
                {{-- Section Content (chỉ hiện khi enabled) --}}
                <div x-show="section.enabled" x-transition class="space-y-4">
                    <template x-if="section.hasContent">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề</label>
                                <input 
                                    type="text" 
                                    x-model="section.title"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                                >
                            </div>
                            
                            {{-- Background Color --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Màu nền</label>
                                <select
                                    x-model="section.bgColor"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                                >
                                    <option value="bg-white">Trắng</option>
                                    <option value="bg-gray-25">Xám nhạt</option>
                                    <option value="bg-red-25">Đỏ nhạt</option>
                                    <option value="bg-red-50">Đỏ rất nhạt</option>
                                </select>
                            </div>
                            
                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô tả</label>
                                <textarea 
                                    x-model="section.description"
                                    rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                                ></textarea>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
    
    {{-- Save Button --}}
    <div class="flex justify-end">
        <button 
            @click="saveSettings()"
            :disabled="saving"
            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors flex items-center space-x-2"
        >
            <svg x-show="!saving" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="saving ? 'Đang lưu...' : 'Lưu cài đặt giao diện'"></span>
        </button>
    </div>
</div>

<script>
function webDesignBuilder() {
    return {
        sections: @json($this->getSectionsData()),
        saving: false,
        
        get enabledSections() {
            return this.sections.filter(s => s.enabled).length;
        },
        
        get totalSections() {
            return this.sections.length;
        },
        
        init() {
            this.initSortable();
        },
        
        initSortable() {
            // Implement drag & drop functionality
            // This would require SortableJS library
        },
        
        toggleSection(key) {
            const section = this.sections.find(s => s.key === key);
            if (section) {
                section.enabled = !section.enabled;
            }
        },
        
        updateOrder(key, newOrder) {
            const section = this.sections.find(s => s.key === key);
            if (section) {
                section.order = parseInt(newOrder);
            }
        },
        
        toggleAllSections(enabled) {
            this.sections.forEach(section => {
                section.enabled = enabled;
            });
        },
        
        autoReorder() {
            this.sections.forEach((section, index) => {
                section.order = index + 1;
            });
        },
        
        resetToDefault() {
            if (confirm('Bạn có chắc chắn muốn reset về cài đặt mặc định?')) {
                // Reset logic here
                location.reload();
            }
        },
        
        async saveSettings() {
            this.saving = true;
            try {
                // Save logic here
                await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API call
                
                // Show success notification
                this.$dispatch('notify', {
                    type: 'success',
                    message: 'Cài đặt giao diện đã được lưu thành công!'
                });
            } catch (error) {
                this.$dispatch('notify', {
                    type: 'error',
                    message: 'Có lỗi xảy ra khi lưu cài đặt!'
                });
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
