{{-- Generated Footer Component --}}
<footer class="bg-gray-900 text-white">
    {{-- Main Footer Content --}}
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Company Info --}}
            <div class="lg:col-span-1">
                <div class="mb-6">
                    @if(isset($logo) && $logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}" class="h-10 w-auto mb-4">
                    @else
                        <div class="text-2xl font-bold text-white mb-4">
                            {{ config('app.name') }}
                        </div>
                    @endif
                    
                    @if(isset($companyDescription))
                        <p class="text-gray-300 text-sm leading-relaxed">
                            {{ $companyDescription }}
                        </p>
                    @endif
                </div>

                {{-- Contact Info --}}
                @if(isset($contactInfo) && is_array($contactInfo))
                    <div class="space-y-3">
                        @if(isset($contactInfo['address']))
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-map-marker-alt text-red-500 mt-1"></i>
                                <span class="text-gray-300 text-sm">{{ $contactInfo['address'] }}</span>
                            </div>
                        @endif
                        
                        @if(isset($contactInfo['phone']))
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-phone text-red-500"></i>
                                <a href="tel:{{ $contactInfo['phone'] }}" class="text-gray-300 text-sm hover:text-white transition-colors">
                                    {{ $contactInfo['phone'] }}
                                </a>
                            </div>
                        @endif
                        
                        @if(isset($contactInfo['email']))
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-red-500"></i>
                                <a href="mailto:{{ $contactInfo['email'] }}" class="text-gray-300 text-sm hover:text-white transition-colors">
                                    {{ $contactInfo['email'] }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-lg font-semibold mb-6">Liên kết nhanh</h3>
                @livewire('generated.dynamic-menu', [
                    'location' => 'footer',
                    'menuClass' => 'space-y-3',
                    'itemClass' => 'block',
                    'linkClass' => 'text-gray-300 text-sm hover:text-white transition-colors',
                    'showIcons' => false,
                    'showDropdown' => false,
                    'maxDepth' => 1
                ])
            </div>

            {{-- Services/Categories (if available) --}}
            <div>
                <h3 class="text-lg font-semibold mb-6">
                    @if(class_exists('\App\Generated\Models\Product'))
                        Danh mục sản phẩm
                    @elseif(class_exists('\App\Generated\Models\Service'))
                        Dịch vụ
                    @else
                        Thông tin
                    @endif
                </h3>
                
                <div class="space-y-3">
                    @if(class_exists('\App\Generated\Models\ProductCategory'))
                        @php
                            $categories = \App\Generated\Models\ProductCategory::where('is_active', true)
                                ->orderBy('order')
                                ->limit(6)
                                ->get();
                        @endphp
                        @foreach($categories as $category)
                            <a href="{{ route('products.category', $category->slug) }}" class="block text-gray-300 text-sm hover:text-white transition-colors">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    @elseif(class_exists('\App\Generated\Models\Service'))
                        @php
                            $services = \App\Generated\Models\Service::where('is_active', true)
                                ->orderBy('order')
                                ->limit(6)
                                ->get();
                        @endphp
                        @foreach($services as $service)
                            <a href="{{ route('services.show', $service->slug) }}" class="block text-gray-300 text-sm hover:text-white transition-colors">
                                {{ $service->title }}
                            </a>
                        @endforeach
                    @else
                        <a href="{{ route('about') }}" class="block text-gray-300 text-sm hover:text-white transition-colors">Giới thiệu</a>
                        <a href="{{ route('contact') }}" class="block text-gray-300 text-sm hover:text-white transition-colors">Liên hệ</a>
                        <a href="{{ route('privacy') }}" class="block text-gray-300 text-sm hover:text-white transition-colors">Chính sách bảo mật</a>
                        <a href="{{ route('terms') }}" class="block text-gray-300 text-sm hover:text-white transition-colors">Điều khoản sử dụng</a>
                    @endif
                </div>
            </div>

            {{-- Newsletter & Social --}}
            <div>
                <h3 class="text-lg font-semibold mb-6">Kết nối với chúng tôi</h3>
                
                {{-- Newsletter Signup --}}
                <div class="mb-6">
                    <p class="text-gray-300 text-sm mb-4">Đăng ký nhận tin tức và ưu đãi mới nhất</p>
                    <form class="flex">
                        <input type="email" placeholder="Email của bạn" 
                               class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-md text-white placeholder-gray-400 focus:outline-none focus:border-red-500">
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-r-md transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

                {{-- Social Links --}}
                @if(isset($socialLinks) && is_array($socialLinks))
                    <div>
                        <p class="text-gray-300 text-sm mb-4">Theo dõi chúng tôi</p>
                        <div class="flex space-x-4">
                            @foreach($socialLinks as $platform => $url)
                                @if($url)
                                    <a href="{{ $url }}" target="_blank" 
                                       class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-300 hover:bg-red-600 hover:text-white transition-colors">
                                        <i class="fab fa-{{ $platform }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Bottom Footer --}}
    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-gray-400 text-sm mb-4 md:mb-0">
                    © {{ date('Y') }} {{ config('app.name') }}. Tất cả quyền được bảo lưu.
                </div>
                
                <div class="flex items-center space-x-6">
                    {{-- Payment Methods (if ecommerce) --}}
                    @if(class_exists('\App\Generated\Models\Product'))
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-400 text-sm">Thanh toán:</span>
                            <div class="flex space-x-2">
                                <i class="fab fa-cc-visa text-2xl text-gray-400"></i>
                                <i class="fab fa-cc-mastercard text-2xl text-gray-400"></i>
                                <i class="fas fa-mobile-alt text-xl text-gray-400"></i>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Additional Links --}}
                    <div class="flex items-center space-x-4 text-sm">
                        <a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white transition-colors">
                            Chính sách bảo mật
                        </a>
                        <a href="{{ route('terms') }}" class="text-gray-400 hover:text-white transition-colors">
                            Điều khoản
                        </a>
                        <a href="{{ route('sitemap') }}" class="text-gray-400 hover:text-white transition-colors">
                            Sitemap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- Back to Top Button --}}
<button id="back-to-top" 
        class="fixed bottom-6 right-6 w-12 h-12 bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 transition-all duration-300 opacity-0 invisible">
    <i class="fas fa-chevron-up"></i>
</button>

{{-- Back to Top Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('back-to-top');
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
        } else {
            backToTopButton.classList.add('opacity-0', 'invisible');
        }
    });
    
    // Smooth scroll to top
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>
