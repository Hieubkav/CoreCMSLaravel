{{--
    Course Categories Sections Component - Minimalist Design
    - Thiết kế minimalist với tone đỏ-trắng
    - Layout thông minh, gọn gàng
    - Responsive hoàn hảo
    - Tối ưu hiệu suất
--}}

@if(isset($courseCategoriesGrid) && $courseCategoriesGrid->isNotEmpty())
    <div class="bg-white">
        @foreach($courseCategoriesGrid as $category)
            @php
                // Kiểm tra danh mục có khóa học không
                $hasActiveCourses = $category->courses()
                    ->where('status', 'active')
                    ->exists();
            @endphp

            @if($hasActiveCourses)
                <!-- Minimalist Category Section -->
                <section class="py-8 md:py-12 {{ $loop->odd ? 'bg-white' : 'bg-red-50/30' }}">
                    @include('components.storefront.course-category-section', [
                        'category' => $category,
                        'limit' => 8
                    ])
                </section>

                @if(!$loop->last)
                    <!-- Subtle Divider -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="h-px bg-gradient-to-r from-transparent via-red-100 to-transparent"></div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
@else
    <!-- Minimalist Empty State -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-red-50 rounded-full flex items-center justify-center">
                <i class="fas fa-graduation-cap text-2xl text-red-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Chưa có khóa học nào</h3>
            <p class="text-gray-600 max-w-md mx-auto">Các khóa học sẽ được cập nhật sớm. Vui lòng quay lại sau.</p>
        </div>
    </section>
@endif
