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
                @livewire('search-suggestions')
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
                @livewire('dynamic-menu', [
                    'position' => 'horizontal',
                    'style' => 'navbar',
                    'showIcons' => false,
                    'maxDepth' => 2
                ])

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
            @livewire('search-suggestions')
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden" x-data="{ open: false }" @toggle-mobile-menu.window="open = !open">
            <div x-show="open" x-transition class="py-4 border-t">
                @livewire('dynamic-menu', [
                    'position' => 'vertical',
                    'style' => 'sidebar',
                    'showIcons' => true,
                    'maxDepth' => 2
                ])

                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center py-2 text-red-600 font-semibold">
                        <i class="fas fa-cog mr-2"></i>Admin Panel
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
