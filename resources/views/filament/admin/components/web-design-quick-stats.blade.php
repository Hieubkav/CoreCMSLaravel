<div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-700 rounded-xl p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Sections Enabled --}}
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $enabledCount }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Sections đang hiển thị</div>
            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">Trên tổng {{ $totalCount }} sections</div>
        </div>

        {{-- Performance Score --}}
        <div class="text-center">
            @php
                $performanceScore = round(($enabledCount / $totalCount) * 100);
                $scoreColor = $performanceScore >= 80 ? 'green' : ($performanceScore >= 60 ? 'yellow' : 'red');
            @endphp
            <div class="w-16 h-16 bg-gradient-to-br from-{{ $scoreColor }}-500 to-{{ $scoreColor }}-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $performanceScore }}%</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Điểm hiệu suất</div>
            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                @if($performanceScore >= 80)
                    Tuyệt vời
                @elseif($performanceScore >= 60)
                    Tốt
                @else
                    Cần cải thiện
                @endif
            </div>
        </div>

        {{-- Last Updated --}}
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                @if($webDesign->last_updated_at)
                    {{ $webDesign->last_updated_at->diffForHumans() }}
                @else
                    Chưa cập nhật
                @endif
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Cập nhật cuối</div>
            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                @if($webDesign->updatedBy)
                    Bởi {{ $webDesign->updatedBy->name }}
                @else
                    Hệ thống
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-6 pt-6 border-t border-red-200 dark:border-red-700">
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="/" target="_blank" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-red-300 dark:border-red-600 rounded-lg text-sm font-medium text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Xem trước trang chủ
            </a>
            
            <button type="button" onclick="clearWebDesignCache()" 
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-yellow-300 dark:border-yellow-600 rounded-lg text-sm font-medium text-yellow-700 dark:text-yellow-300 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Xóa cache
            </button>
        </div>
    </div>

    {{-- Sections Status Grid --}}
    <div class="mt-6 pt-6 border-t border-red-200 dark:border-red-700">
        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Trạng thái các sections:</h4>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            @php
                $sections = [
                    'hero_banner' => ['name' => 'Hero Banner', 'enabled' => $webDesign->hero_banner_enabled],
                    'courses_overview' => ['name' => 'Khóa học', 'enabled' => $webDesign->courses_overview_enabled],
                    'album_timeline' => ['name' => 'Album', 'enabled' => $webDesign->album_timeline_enabled],
                    'testimonials' => ['name' => 'Đánh giá', 'enabled' => $webDesign->testimonials_enabled],
                    'faq' => ['name' => 'FAQ', 'enabled' => $webDesign->faq_enabled],
                    'partners' => ['name' => 'Đối tác', 'enabled' => $webDesign->partners_enabled],
                    'blog_posts' => ['name' => 'Blog', 'enabled' => $webDesign->blog_posts_enabled],
                    'homepage_cta' => ['name' => 'CTA', 'enabled' => $webDesign->homepage_cta_enabled],
                ];
            @endphp
            
            @foreach($sections as $key => $section)
                <div class="flex items-center space-x-2 p-2 rounded-lg {{ $section['enabled'] ? 'bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-gray-800' }}">
                    <div class="w-2 h-2 rounded-full {{ $section['enabled'] ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                    <span class="text-xs font-medium {{ $section['enabled'] ? 'text-green-700 dark:text-green-300' : 'text-gray-600 dark:text-gray-400' }}">
                        {{ $section['name'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function clearWebDesignCache() {
    // This would be handled by the Filament action
    // Just for UI feedback
    alert('Cache sẽ được xóa khi bạn lưu cài đặt!');
}
</script>
