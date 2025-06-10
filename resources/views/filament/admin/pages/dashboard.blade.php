<x-filament-panels::page class="fi-dashboard-page">
    <!-- Clean Dashboard Header -->
    <div class="dashboard-header-clean">
        <div class="flex items-center justify-between flex-wrap gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Core Framework</h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Hệ thống quản lý khóa học chuyên nghiệp</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        Chào mừng, {{ auth()->user()->name }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Thời gian hiện tại</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="current-time">{{ now()->format('H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Framework Stats Cards -->
    <div class="stats-grid mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Users -->
            <div class="clean-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ \App\Models\User::count() }}</p>
                        <p class="text-green-600 dark:text-green-400 text-xs font-medium">
                            {{ \App\Models\User::where('status', 'active')->count() }} active
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Posts -->
            <div class="clean-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Total Posts</p>
                        @php
                            $postCount = 0;
                            $publishedCount = 0;
                            // Kiểm tra file tồn tại thay vì class_exists để tránh autoload
                            if (file_exists(app_path('Models/Post.php'))) {
                                try {
                                    $postCount = \App\Models\Post::count();
                                    $publishedCount = \App\Models\Post::where('status', 'active')->count();
                                } catch (\Exception $e) {
                                    // Model exists but table might not exist
                                }
                            }
                        @endphp
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $postCount }}</p>
                        <p class="text-purple-600 dark:text-purple-400 text-xs font-medium">
                            {{ $publishedCount }} published
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="clean-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Categories</p>
                        @php
                            $categoryCount = 0;
                            $activeCategoryCount = 0;
                            // Kiểm tra file tồn tại thay vì class_exists để tránh autoload
                            if (file_exists(app_path('Models/CatPost.php'))) {
                                try {
                                    $categoryCount = \App\Models\CatPost::count();
                                    $activeCategoryCount = \App\Models\CatPost::where('status', 'active')->count();
                                } catch (\Exception $e) {
                                    // Model exists but table might not exist
                                }
                            }
                        @endphp
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $categoryCount }}</p>
                        <p class="text-blue-600 dark:text-blue-400 text-xs font-medium">
                            {{ $activeCategoryCount }} active
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="clean-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Settings</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ \App\Models\Setting::count() }}</p>
                        <p class="text-orange-600 dark:text-orange-400 text-xs font-medium">
                            System configured
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="quick-actions-section mb-8">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            Quick Actions
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Create New Post -->
            @if(file_exists(app_path('Models/Post.php')))
            <a href="/admin/posts/create" class="quick-action-btn">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">New Post</span>
            </a>
            @else
            <div class="quick-action-btn opacity-50 cursor-not-allowed">
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-500">New Post (Not Available)</span>
            </div>
            @endif

            <!-- Manage Users -->
            <a href="/admin/users" class="quick-action-btn">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Users</span>
            </a>

            <!-- Categories -->
            @if(file_exists(app_path('Models/CatPost.php')))
            <a href="/admin/cat-posts" class="quick-action-btn">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Categories</span>
            </a>
            @else
            <div class="quick-action-btn opacity-50 cursor-not-allowed">
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Categories (Not Available)</span>
            </div>
            @endif

            <!-- Settings -->
            <a href="/admin" class="quick-action-btn">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Recent Posts Section -->
    <div class="recent-posts-section mb-8">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
            </svg>
            Recent Posts
        </h2>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            @php
                $recentPosts = collect();
                // Kiểm tra file tồn tại thay vì class_exists để tránh autoload
                if (file_exists(app_path('Models/Post.php'))) {
                    try {
                        $recentPosts = \App\Models\Post::where('status', 'active')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    } catch (\Exception $e) {
                        // Model exists but table might not exist
                        $recentPosts = collect();
                    }
                }
            @endphp

            @if($recentPosts->isNotEmpty())
                <div class="space-y-4">
                    @foreach($recentPosts as $post)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <!-- Post Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                    {{ $post->title }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Created {{ $post->created_at->diffForHumans() }}
                                    @if($post->category)
                                        • {{ $post->category->name }}
                                    @endif
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex-shrink-0 flex gap-2">
                                <a href="/admin/posts/{{ $post->id }}/edit"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    Edit
                                </a>
                                <a href="{{ route('posts.show', $post->slug) }}"
                                   target="_blank"
                                   class="text-green-600 hover:text-green-800 text-xs font-medium">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        No posts yet
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        Create your first post to get started
                    </p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Simple animations for stat cards
                const statCards = document.querySelectorAll('.clean-stat-card');
                statCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        card.style.transition = 'all 0.4s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                // Update current time
                function updateCurrentTime() {
                    const now = new Date();
                    const timeString = now.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    const timeElement = document.getElementById('current-time');
                    if (timeElement) {
                        timeElement.textContent = timeString;
                    }
                }

                updateCurrentTime();
                setInterval(updateCurrentTime, 1000);
            });
        </script>
    @endpush

    @push('styles')
        <style>
            /* Core Framework Dashboard Styles */
            .dashboard-header-clean {
                @apply bg-white dark:bg-gray-800;
                @apply shadow-lg border border-gray-200 dark:border-gray-700;
                @apply rounded-2xl p-6 mb-8;
                transition: all 0.3s ease;
            }

            .clean-stat-card {
                @apply bg-white dark:bg-gray-800;
                @apply border border-gray-200 dark:border-gray-700;
                @apply rounded-2xl p-6 shadow-lg;
                transition: all 0.3s ease;
            }

            .clean-stat-card:hover {
                @apply shadow-xl border-gray-300 dark:border-gray-600;
                transform: translateY(-2px);
            }

            .quick-action-btn {
                @apply flex flex-col items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700;
                @apply bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700;
                @apply transition-all duration-200 ease-in-out;
                text-decoration: none;
            }

            .quick-action-btn:hover {
                @apply shadow-md border-gray-300 dark:border-gray-600;
                transform: translateY(-1px);
            }

            .recent-posts-section .bg-white {
                @apply rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700;
            }

            .dark .recent-posts-section .bg-white {
                @apply bg-gray-800;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .dashboard-header-clean {
                    @apply p-4 rounded-xl;
                }

                .clean-stat-card {
                    @apply p-4 rounded-xl;
                }
            }
        </style>
    @endpush
</x-filament-panels::page>
