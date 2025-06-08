<nav class="bg-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <a href="{{ route('storeFront') }}" class="flex items-center">
                @if(isset($settings) && $settings && $settings->logo_link)
                    <img src="{{ asset('storage/' . $settings->logo_link) }}" alt="{{ $settings->site_name ?? 'Core Framework' }}" class="h-10 w-auto">
                @else
                    <div class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold">
                        Core Framework
                    </div>
                @endif
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('storeFront') }}" class="text-gray-700 hover:text-blue-600 transition">Home</a>
                <a href="{{ route('posts.index') }}" class="text-gray-700 hover:text-blue-600 transition">Posts</a>
                @if(isset($postCategories) && $postCategories->isNotEmpty())
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600 transition flex items-center">
                            Categories
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            @foreach($postCategories as $category)
                            <a href="{{ route('posts.category', $category->slug) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                                {{ $category->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <a href="/admin" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Admin</a>
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden p-2" id="mobile-menu-btn">
                <i class="fas fa-bars text-gray-700"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="py-4 border-t">
                <a href="{{ route('storeFront') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition">Home</a>
                <a href="{{ route('posts.index') }}" class="block py-2 text-gray-700 hover:text-blue-600 transition">Posts</a>
                @if(isset($postCategories) && $postCategories->isNotEmpty())
                    @foreach($postCategories as $category)
                    <a href="{{ route('posts.category', $category->slug) }}" class="block py-2 pl-4 text-gray-600 hover:text-blue-600 transition">
                        {{ $category->name }}
                    </a>
                    @endforeach
                @endif
                <a href="/admin" class="block py-2 text-blue-600 font-semibold">Admin</a>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>
