<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <a href="{{ route('storeFront') }}" class="flex items-center">
                @if(isset($settings) && $settings && $settings->logo_link)
                    <img src="{{ asset('storage/' . $settings->logo_link) }}" alt="{{ $settings->site_name ?? 'Core Framework' }}" class="h-10 w-auto">
                @else
                    <div class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold">
                        Core Framework
                    </div>
                @endif
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:block flex-1 max-w-md mx-8">
                <div class="relative">
                    <input type="text"
                           placeholder="Tìm kiếm..."
                           class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('storeFront') }}" class="text-gray-700 hover:text-red-600 transition-colors">
                    Trang chủ
                </a>
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="text-gray-700 hover:text-red-600 transition-colors">
                    Admin Panel
                </a>
                @if(app()->environment('local'))
                <a href="{{ route('setup.index') }}" class="text-gray-700 hover:text-red-600 transition-colors">
                    Setup
                </a>
                @endif

                <a href="{{ route('filament.admin.pages.dashboard') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-cog mr-2"></i>Admin
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden p-2" x-data @click="$dispatch('toggle-mobile-menu')">
                <i class="fas fa-bars text-gray-700"></i>
            </button>
        </div>

        <!-- Mobile Search -->
        <div class="md:hidden pb-4">
            <div class="relative">
                <input type="text"
                       placeholder="Tìm kiếm..."
                       class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden" x-data="{ open: false }" @toggle-mobile-menu.window="open = !open">
            <div x-show="open" x-transition class="py-4 border-t">
                <div class="space-y-2">
                    <a href="{{ route('storeFront') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-home mr-2"></i> Trang chủ
                    </a>
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-user-shield mr-2"></i> Admin Panel
                    </a>
                    @if(app()->environment('local'))
                    <a href="{{ route('setup.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        <i class="fas fa-cogs mr-2"></i> Setup
                    </a>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center py-2 text-red-600 font-semibold">
                        <i class="fas fa-cog mr-2"></i>Admin Panel
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
