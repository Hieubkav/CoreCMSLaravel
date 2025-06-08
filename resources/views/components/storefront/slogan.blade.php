@php
    // Sử dụng dữ liệu từ ViewServiceProvider (đã được preload và cached)
    $settingsData = $globalSettings ?? $settings ?? null;

    // Kiểm tra dữ liệu slogan có tồn tại và không rỗng
    $hasSlogan = isset($settingsData) &&
                 !empty($settingsData) &&
                 isset($settingsData->slogan) &&
                 !empty(trim($settingsData->slogan));

    // Kiểm tra footer description
    $hasDescription = isset($settingsData) &&
                      !empty($settingsData) &&
                      isset($settingsData->footer_description) &&
                      !empty(trim($settingsData->footer_description));
@endphp

{{-- Chỉ hiển thị section nếu có slogan --}}
@if($hasSlogan)
<section class="py-8 sm:py-12 lg:py-16" aria-labelledby="slogan-heading">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Slogan Section -->
        <div class="bg-gradient-to-r from-red-700 to-red-800 text-white rounded-xl lg:rounded-2xl overflow-hidden shadow-2xl relative transform transition-all duration-300 hover:shadow-3xl hover:scale-[1.02]">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-pattern opacity-10" aria-hidden="true"></div>

            <!-- Content -->
            <div class="relative p-6 sm:p-10 md:p-12 lg:p-16 text-center">
                <!-- Icon -->
                <div class="inline-block mb-4 sm:mb-6" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16 text-white opacity-80 transition-transform duration-300 hover:scale-110"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         role="img"
                         aria-label="Biểu tượng ngôi sao">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1"
                              d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>

                <!-- Slogan -->
                <h2 id="slogan-heading"
                    class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold mb-4 sm:mb-6 leading-tight font-montserrat text-white">
                    {{ trim($settingsData->slogan) }}
                </h2>

                <!-- Description -->
                @if($hasDescription)
                    <div class="max-w-2xl lg:max-w-3xl xl:max-w-4xl mx-auto">
                        <p class="text-white text-opacity-90 text-base sm:text-lg lg:text-xl font-open-sans leading-relaxed">
                            {{ trim($settingsData->footer_description) }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
