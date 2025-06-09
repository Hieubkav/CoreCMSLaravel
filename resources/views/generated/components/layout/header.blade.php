{{-- Generated Header Component --}}
<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4">
        {{-- Top Header Bar --}}
        <div class="hidden md:flex items-center justify-between py-2 text-sm border-b border-gray-100">
            <div class="flex items-center space-x-4 text-gray-600">
                @if(isset($contactInfo['phone']))
                    <a href="tel:{{ $contactInfo['phone'] }}" class="flex items-center hover:text-red-600 transition-colors">
                        <i class="fas fa-phone mr-1"></i>
                        {{ $contactInfo['phone'] }}
                    </a>
                @endif
                @if(isset($contactInfo['email']))
                    <a href="mailto:{{ $contactInfo['email'] }}" class="flex items-center hover:text-red-600 transition-colors">
                        <i class="fas fa-envelope mr-1"></i>
                        {{ $contactInfo['email'] }}
                    </a>
                @endif
            </div>
            
            <div class="flex items-center space-x-4">
                {{-- Social Links --}}
                @if(isset($socialLinks) && is_array($socialLinks))
                    @foreach($socialLinks as $platform => $url)
                        @if($url)
                            <a href="{{ $url }}" target="_blank" class="text-gray-600 hover:text-red-600 transition-colors">
                                <i class="fab fa-{{ $platform }}"></i>
                            </a>
                        @endif
                    @endforeach
                @endif
                
                {{-- Language Switcher (if available) --}}
                @if(isset($languages) && count($languages) > 1)
                    <div class="relative">
                        <select class="text-sm border-0 bg-transparent text-gray-600 focus:ring-0">
                            @foreach($languages as $code => $name)
                                <option value="{{ $code }}" {{ app()->getLocale() === $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        {{-- Main Header --}}
        <div class="flex items-center justify-between py-4">
            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    @if(isset($logo) && $logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
                    @else
                        <div class="text-2xl font-bold text-red-600">
                            {{ config('app.name') }}
                        </div>
                    @endif
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center space-x-8">
                @livewire('generated.dynamic-menu', [
                    'location' => 'header',
                    'menuClass' => 'flex items-center space-x-8',
                    'itemClass' => 'relative group',
                    'linkClass' => 'text-gray-700 hover:text-red-600 font-medium transition-colors py-2',
                    'showIcons' => false,
                    'showDropdown' => true,
                    'maxDepth' => 2
                ])
            </nav>

            {{-- Search & Actions --}}
            <div class="flex items-center space-x-4">
                {{-- Search --}}
                <div class="hidden md:block relative">
                    @livewire('generated.search-suggestions')
                </div>

                {{-- Cart (if ecommerce enabled) --}}
                @if(class_exists('\App\Generated\Models\Product'))
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-700 hover:text-red-600 transition-colors">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ session('cart_count', 0) }}
                        </span>
                    </a>
                @endif

                {{-- User Menu --}}
                @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-red-600 transition-colors">
                            <i class="fas fa-user"></i>
                            <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <div class="py-1">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>
                                    Hồ sơ
                                </a>
                                <a href="{{ route('orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-shopping-bag mr-2"></i>
                                    Đơn hàng
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 transition-colors">
                            Đăng nhập
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                            Đăng ký
                        </a>
                    </div>
                @endauth

                {{-- Mobile Menu Toggle --}}
                <button id="mobile-menu-toggle" class="lg:hidden p-2 text-gray-700 hover:text-red-600 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-gray-200">
        <div class="container mx-auto px-4 py-4">
            {{-- Mobile Search --}}
            <div class="mb-4">
                @livewire('generated.search-suggestions')
            </div>

            {{-- Mobile Navigation --}}
            @livewire('generated.dynamic-menu', [
                'location' => 'mobile',
                'menuClass' => 'space-y-2',
                'itemClass' => 'block',
                'linkClass' => 'block py-2 px-4 text-gray-700 hover:bg-gray-100 hover:text-red-600 rounded-md transition-colors',
                'showIcons' => true,
                'showDropdown' => false,
                'maxDepth' => 1
            ])

            {{-- Mobile User Menu --}}
            @auth
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="space-y-2">
                        <a href="{{ route('profile') }}" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-user mr-2"></i>
                            Hồ sơ
                        </a>
                        <a href="{{ route('orders') }}" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Đơn hàng
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                    <a href="{{ route('login') }}" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md">
                        Đăng nhập
                    </a>
                    <a href="{{ route('register') }}" class="block py-2 px-4 bg-red-600 text-white hover:bg-red-700 rounded-md text-center">
                        Đăng ký
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

{{-- Mobile Menu Toggle Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.className = 'fas fa-bars text-xl';
            } else {
                icon.className = 'fas fa-times text-xl';
            }
        });
    }
});
</script>
