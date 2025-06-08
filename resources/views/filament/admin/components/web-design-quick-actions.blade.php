{{-- Quick Actions Component for WebDesign --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    {{-- Enable All Button --}}
    <button 
        type="button"
        wire:click="enableAllSections"
        class="inline-flex items-center justify-center px-4 py-3 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 font-medium rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="text-sm">Hiện tất cả</span>
    </button>

    {{-- Disable All Button --}}
    <button
        type="button"
        wire:click="disableAllSections"
        class="inline-flex items-center justify-center px-4 py-3 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 font-medium rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span class="text-sm">Ẩn tất cả</span>
    </button>

    {{-- Auto Reorder Button --}}
    <button
        type="button"
        wire:click="autoReorderSections"
        class="inline-flex items-center justify-center px-4 py-3 bg-yellow-100 dark:bg-yellow-900/30 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 font-medium rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
        </svg>
        <span class="text-sm">Tự động sắp xếp</span>
    </button>

    {{-- Reset Default Button --}}
    <button
        type="button"
        wire:click="resetToDefault"
        wire:confirm="Bạn có chắc chắn muốn reset về cài đặt mặc định? Tất cả thay đổi hiện tại sẽ bị mất."
        class="inline-flex items-center justify-center px-4 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span class="text-sm">Reset mặc định</span>
    </button>
</div>

{{-- Quick Stats --}}
<div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
    <div class="grid grid-cols-3 gap-4 text-center">
        <div>
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="sections-enabled">
                {{ $webDesign ? collect([
                    $webDesign->hero_banner_enabled,
                    $webDesign->courses_overview_enabled,
                    $webDesign->album_timeline_enabled,
                    $webDesign->course_groups_enabled,
                    $webDesign->course_categories_enabled,
                    $webDesign->testimonials_enabled,
                    $webDesign->faq_enabled,
                    $webDesign->partners_enabled,
                    $webDesign->blog_posts_enabled,
                    $webDesign->homepage_cta_enabled,
                ])->filter()->count() : 0 }}
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-400">Phần hiển thị</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">10</div>
            <div class="text-xs text-gray-600 dark:text-gray-400">Tổng phần</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a4 4 0 004-4V5z"></path>
                </svg>
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-400">Giao diện tùy chỉnh</div>
        </div>
    </div>
</div>

{{-- Loading Indicator --}}
<div wire:loading class="fixed inset-0 bg-black/20 dark:bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
        <div class="flex items-center space-x-3">
            <svg class="animate-spin h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 dark:text-gray-300 font-medium">Đang xử lý...</span>
        </div>
    </div>
</div>
