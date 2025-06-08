<footer class="bg-gray-800 text-white">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Core Framework</h3>
                <p class="text-gray-300 mb-4">
                    A powerful, flexible Laravel-based framework for building modern web applications.
                </p>
                @if(isset($settings) && $settings)
                    @if($settings->email)
                    <p class="text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-2"></i> {{ $settings->email }}
                    </p>
                    @endif
                    @if($settings->hotline)
                    <p class="text-gray-300">
                        <i class="fas fa-phone mr-2"></i> {{ $settings->hotline }}
                    </p>
                    @endif
                @endif
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('storeFront') }}" class="text-gray-300 hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('posts.index') }}" class="text-gray-300 hover:text-white transition">Posts</a></li>
                    @if(isset($postCategories) && $postCategories->isNotEmpty())
                        @foreach($postCategories->take(3) as $category)
                        <li><a href="{{ route('posts.category', $category->slug) }}" class="text-gray-300 hover:text-white transition">{{ $category->name }}</a></li>
                        @endforeach
                    @endif
                    <li><a href="/admin" class="text-gray-300 hover:text-white transition">Admin Panel</a></li>
                </ul>
            </div>

            <!-- Recent Posts -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Recent Posts</h3>
                @if(isset($recentPosts) && $recentPosts->isNotEmpty())
                    <ul class="space-y-3">
                        @foreach($recentPosts as $post)
                        <li>
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-gray-300 hover:text-white transition block">
                                <div class="text-sm font-medium">{{ Str::limit($post->title, 40) }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ $post->created_at->format('M d, Y') }}</div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400">No posts available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="bg-gray-900 py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Core Framework. All rights reserved.
                </p>
                <p class="text-gray-400 text-sm mt-2 md:mt-0">
                    Built with Laravel & Tailwind CSS
                </p>
            </div>
        </div>
    </div>
</footer>