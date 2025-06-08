<div class="relative group" x-data="{ open: false }">
    @if(!$isLoggedIn)
        <!-- Chưa đăng nhập -->
        <a href="#" class="relative flex items-center justify-center w-10 h-10 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-lg transition-all duration-300 group" aria-label="Đăng nhập">
            <!-- User Icon -->
            <svg class="h-5 w-5 text-gray-600 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2.5"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>

            <!-- Hover Effect Ring -->
            <div class="absolute inset-0 rounded-full bg-blue-500 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        </a>

        <!-- Enhanced Tooltip -->
        <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none z-50">
            <div class="bg-gray-900 dark:bg-gray-700 text-white text-sm rounded-lg px-3 py-2 shadow-xl">
                <div class="font-medium">Đăng nhập</div>
                <div class="text-xs text-gray-300">Tài khoản</div>
            </div>
            <!-- Tooltip Arrow -->
            <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
        </div>
    @else
        <!-- Đã đăng nhập -->
        <div class="relative">
            <button @click="open = !open"
                    class="relative flex items-center justify-center w-10 h-10 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 hover:border-green-300 dark:hover:border-green-500 hover:shadow-lg transition-all duration-300 group"
                    aria-label="Tài khoản">

                @if($user && isset($user->avatar))
                    <img src="{{ $user->avatar }}"
                         alt="{{ $user->name }}"
                         class="h-8 w-8 rounded-full object-cover border-2 border-white dark:border-gray-800">
                @else
                    <!-- User Icon for logged in state -->
                    <svg class="h-5 w-5 text-green-600 dark:text-green-400 transition-colors duration-300"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2.5"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif

                <!-- Online Status Indicator -->
                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>

                <!-- Hover Effect Ring -->
                <div class="absolute inset-0 rounded-full bg-green-500 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
            </button>

            <!-- Enhanced Dropdown Menu -->
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="absolute right-0 mt-3 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-600 z-50 overflow-hidden">

                <!-- User Info Header -->
                @if($user)
                    <div class="px-4 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center space-x-3">
                            @if(isset($user->avatar))
                                <img src="{{ $user->avatar }}"
                                     alt="{{ $user->name }}"
                                     class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm">
                            @else
                                <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Menu Items -->
                <div class="py-2">
                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-gray-700 dark:hover:to-gray-600 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 group">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Thông tin tài khoản
                    </a>

                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 dark:hover:from-gray-700 dark:hover:to-gray-600 hover:text-green-600 dark:hover:text-green-400 transition-all duration-200 group">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-green-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Đơn hàng của tôi
                    </a>

                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 dark:hover:from-gray-700 dark:hover:to-gray-600 hover:text-pink-600 dark:hover:text-pink-400 transition-all duration-200 group">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-pink-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Yêu thích
                    </a>

                    <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>

                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 dark:hover:from-gray-700 dark:hover:to-gray-600 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 group">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-red-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
