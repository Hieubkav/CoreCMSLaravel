<x-filament-panels::page class="fi-dashboard-page">
    <!-- Modern Dashboard Header -->
    <div class="dashboard-header-modern">
        <div class="flex items-center justify-between flex-wrap gap-8">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-6 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center shadow-soft">
                        <span class="text-3xl">🎓</span>
                    </div>
                    <div>
                        <h1 class="text-4xl font-light text-slate-800 dark:text-slate-100 tracking-tight">Core Framework</h1>
                        <p class="text-slate-600 dark:text-slate-400 text-lg font-medium">Admin Management System</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 text-sm text-slate-500 dark:text-slate-400">
                    <span class="flex items-center gap-2 bg-slate-100 dark:bg-slate-700 px-3 py-2 rounded-xl">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        Chào mừng, {{ auth()->user()->name }}
                    </span>
                    <span class="flex items-center gap-2 bg-slate-100 dark:bg-slate-700 px-3 py-2 rounded-xl">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <div class="glass-card rounded-2xl p-6 shadow-soft border border-slate-100 dark:border-slate-700">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-2 font-medium">Thời gian hiện tại</div>
                    <div class="text-3xl font-semibold text-slate-800 dark:text-slate-100" id="current-time">{{ now()->format('H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Stats Cards -->
    <div class="stats-grid mb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Users -->
            <div class="modern-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold mb-3">Total Users</p>
                        <p class="text-4xl font-light text-slate-800 dark:text-slate-100 mb-2">{{ \App\Models\User::count() }}</p>
                        <div class="inline-flex items-center px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-medium rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></div>
                            Registered users
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-soft">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="modern-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold mb-3">Settings</p>
                        <p class="text-4xl font-light text-slate-800 dark:text-slate-100 mb-2">{{ \App\Models\Setting::count() }}</p>
                        <div class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></div>
                            System configured
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-soft">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="modern-stat-card group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold mb-3">System Status</p>
                        <p class="text-4xl font-light text-slate-800 dark:text-slate-100 mb-2">Online</p>
                        <div class="inline-flex items-center px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-medium rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></div>
                            All systems operational
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-all duration-300 shadow-soft">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Welcome Message -->
    <div class="glass-card rounded-3xl shadow-soft p-10 text-center border border-slate-100 dark:border-slate-700">
        <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 dark:bg-blue-900/30 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-soft">
            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h3 class="text-2xl font-light text-slate-800 dark:text-slate-100 mb-4 tracking-tight">
            Welcome to Core Framework Admin
        </h3>
        <p class="text-lg text-slate-600 dark:text-slate-400 mb-8 leading-relaxed max-w-2xl mx-auto">
            Your admin dashboard is ready. Use the sidebar navigation to manage your application.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/admin/users" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-2xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-hover transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                Manage Users
            </a>
            <a href="{{ route('setup.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-2xl hover:bg-slate-200 transition-all duration-200 border border-slate-200 hover:border-slate-300">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                Setup Wizard
            </a>
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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            /* Modern Dashboard Styles with Inter Font */
            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }

            .glass-card {
                backdrop-filter: blur(20px);
                background: rgba(255, 255, 255, 0.8);
            }

            .shadow-soft {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }

            .shadow-hover {
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            }

            .dashboard-header-modern {
                @apply glass-card;
                @apply shadow-soft border border-slate-100 dark:border-slate-700;
                @apply rounded-3xl p-8 mb-12;
                transition: all 0.3s ease;
            }

            .modern-stat-card {
                @apply glass-card;
                @apply border border-slate-100 dark:border-slate-700;
                @apply rounded-3xl p-8 shadow-soft;
                transition: all 0.3s ease;
            }

            .modern-stat-card:hover {
                @apply shadow-hover border-slate-200 dark:border-slate-600;
                transform: translateY(-4px);
            }

            .quick-action-btn {
                @apply flex flex-col items-center p-6 rounded-2xl border border-slate-100 dark:border-slate-700;
                @apply glass-card hover:bg-slate-50 dark:hover:bg-slate-700;
                @apply transition-all duration-200 ease-in-out shadow-soft;
                text-decoration: none;
            }

            .quick-action-btn:hover {
                @apply shadow-hover border-slate-200 dark:border-slate-600;
                transform: translateY(-2px);
            }

            .recent-posts-section .bg-white {
                @apply rounded-3xl shadow-soft border border-slate-100 dark:border-slate-700;
            }

            .dark .recent-posts-section .bg-white {
                @apply bg-slate-800;
            }

            /* Dark mode glass effect */
            .dark .glass-card {
                backdrop-filter: blur(20px);
                background: rgba(30, 41, 59, 0.8);
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .dashboard-header-modern {
                    @apply p-6 rounded-2xl;
                }

                .modern-stat-card {
                    @apply p-6 rounded-2xl;
                }
            }
        </style>
    @endpush
</x-filament-panels::page>
