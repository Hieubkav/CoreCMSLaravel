{{--
    Course Groups Component - Hiển thị nhóm khóa học Facebook/Zalo
    - Đơn giản hóa theo nguyên tắc KISS
    - Layout grid responsive minimalist
--}}

@php
    // Lấy dữ liệu nhóm khóa học từ ViewServiceProvider (tối đa 6 nhóm)
    $courseGroups = $courseGroups ?? collect();
    // Giới hạn hiển thị tối đa 6 nhóm để đảm bảo layout đẹp
    $courseGroups = $courseGroups->take(6);
@endphp

@if($courseGroups->isNotEmpty())
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- <!-- Header Section - Đơn giản -->
    <div class="text-center mb-10">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
            Nhóm học tập
        </h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Tham gia các nhóm Facebook/Zalo để học hỏi và trao đổi kinh nghiệm
        </p>
    </div> --}}

    <!-- Course Groups Grid - Responsive 2-3 cột -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courseGroups as $group)
            <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="p-6">
                    <!-- Group Type Badge -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                            {{ $group->formatted_group_type ?? ucfirst($group->group_type ?? 'Nhóm') }}
                        </span>
                    </div>

                    <!-- Group Name -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-red-600 transition-colors duration-300">
                        {{ $group->name }}
                    </h3>

                    <!-- Group Description -->
                    @if($group->description)
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">
                            {{ Str::limit($group->description, 100) }}
                        </p>
                    @endif

                    <!-- Members Count - Đơn giản -->
                    @if(isset($group->current_members) && $group->current_members > 0)
                        <div class="flex items-center text-sm mb-4 text-gray-600">
                            <i class="fas fa-users mr-2 text-xs"></i>
                            <span>{{ $group->current_members }}</span>
                            @if($group->max_members)
                                <span class="text-gray-400">/{{ $group->max_members }}</span>
                            @endif
                            <span class="ml-1">thành viên</span>
                        </div>
                    @endif

                    <!-- Action Button -->
                    @if($group->group_link)
                        <a href="{{ $group->group_link }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-300">
                            <i class="{{ $group->group_type_icon ?? 'fas fa-external-link-alt' }} mr-2 text-xs"></i>
                            @if(isset($group->max_members) && $group->current_members >= $group->max_members)
                                <span>Nhóm đã đầy</span>
                            @else
                                <span>Tham gia nhóm</span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Call to Action - Đơn giản -->
    <div class="text-center mt-10">
        <a href="{{ route('course-groups.index') }}"
           class="inline-flex items-center px-6 py-2.5 text-red-600 font-medium rounded-lg border border-red-200 hover:bg-red-50 transition-colors duration-300">
            <i class="fas fa-users mr-2"></i>
            Xem tất cả nhóm học tập
        </a>
    </div>
</div>

<!-- Fallback UI đơn giản khi không có dữ liệu -->
@else
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center py-12">
        <div class="w-16 h-16 mx-auto mb-4 bg-red-50 rounded-lg flex items-center justify-center">
            <i class="fas fa-users text-xl text-red-600 opacity-60"></i>
        </div>
    </div>
</div>
@endif
