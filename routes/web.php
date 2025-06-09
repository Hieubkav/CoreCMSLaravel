<?php

/*
|--------------------------------------------------------------------------
| Web Routes - Core Framework
|--------------------------------------------------------------------------
|
| Core Framework routes - Completely generic and customizable
| 
| 🎯 CUSTOMIZATION GUIDE FOR NEW PROJECTS:
| 
| 1. Change URL slugs according to your project language:
|    - English: /products, /articles, /services
|    - French: /produits, /articles, /services  
|    - German: /produkte, /artikel, /dienstleistungen
| 
| 2. Customize controller names and methods as needed
| 
| 3. Add/remove routes based on your project requirements
| 
| 4. Add middleware and authentication as needed
|
| 📚 See docs/ROUTES_CUSTOMIZATION.md for detailed examples
|
*/

use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SystemConfigController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Homepage Routes
|--------------------------------------------------------------------------
| Main landing page - customize controller method as needed
*/
Route::controller(MainController::class)->group(function () {
    Route::get('/', 'storeFront')->name('storeFront');
});

/*
|--------------------------------------------------------------------------
| Setup System Routes
|--------------------------------------------------------------------------
| Setup wizard for new projects - can be removed after setup is complete
*/
Route::prefix('setup')->name('setup.')->group(function () {
    Route::get('/', [SetupController::class, 'index'])->name('index');
    Route::get('/step/{step}', [SetupController::class, 'step'])->name('step');
    Route::post('/process/{step}', [SetupController::class, 'process'])->name('process');
    Route::post('/complete', [SetupController::class, 'complete'])->name('complete');
    Route::post('/reset', [SetupController::class, 'reset'])->name('reset');
});

/*
|--------------------------------------------------------------------------
| System Configuration Routes (Local Environment Only)
|--------------------------------------------------------------------------
| System configuration interface - only accessible in local environment
*/
Route::prefix('system-config')->name('system-config.')->group(function () {
    Route::get('/', [SystemConfigController::class, 'index'])->name('index');
    Route::post('/', [SystemConfigController::class, 'store'])->name('store');
    Route::post('/reset', [SystemConfigController::class, 'reset'])->name('reset');
    Route::get('/css-variables', [SystemConfigController::class, 'getCssVariables'])->name('css-variables');
});

/*
|--------------------------------------------------------------------------
| Content Routes - Customizable for any project
|--------------------------------------------------------------------------
| These routes can be customized based on your project needs:
| 
| E-commerce Example:
| Route::get('/products', [ProductController::class, 'index'])->name('products.index');
| Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
| Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
| 
| Blog Example:
| Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
| Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
| Route::get('/authors/{slug}', [AuthorController::class, 'show'])->name('authors.show');
| 
| Portfolio Example:
| Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
| Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
| Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
|
| Education Example:
| Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
| Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
| Route::get('/instructors/{slug}', [InstructorController::class, 'show'])->name('instructors.show');
*/

/*
|--------------------------------------------------------------------------
| Blog/Posts Routes
|--------------------------------------------------------------------------
| Generic blog functionality - customize as needed
*/
Route::controller(PostController::class)->group(function () {
    Route::get('/posts', 'index')->name('posts.index');
    Route::get('/posts/category/{slug}', 'category')->name('posts.category');
    Route::get('/posts/{slug}', 'show')->name('posts.show');
});

/*
|--------------------------------------------------------------------------
| Search Routes
|--------------------------------------------------------------------------
| Global search functionality
*/
Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index')->name('search.index');
    Route::get('/api/search/suggestions', 'suggestions')->name('search.suggestions');
});

/*
|--------------------------------------------------------------------------
| SEO & Utility Routes
|--------------------------------------------------------------------------
| Sitemap, robots.txt, and other SEO utilities
*/
Route::controller(SitemapController::class)->group(function () {
    Route::get('/sitemap.xml', 'index')->name('sitemap.index');
    Route::get('/sitemap-posts.xml', 'posts')->name('sitemap.posts');
});

/*
|--------------------------------------------------------------------------
| API Routes for Frontend
|--------------------------------------------------------------------------
| AJAX endpoints for dynamic functionality
*/
Route::prefix('api')->name('api.')->group(function () {
    // Realtime stats for dashboard auto-refresh
    Route::get('/realtime-stats', function () {
        try {
            $stats = \App\Actions\GetVisitorStats::run();

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch stats: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    })->middleware('auth')->name('realtime-stats');
});

/*
|--------------------------------------------------------------------------
| Development Utilities
|--------------------------------------------------------------------------
| Utility routes for development - remove in production
*/
if (app()->environment(['local', 'staging'])) {
    // Storage link utility
    Route::get('/run-storage-link', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            return response()->json(['message' => 'Storage linked successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    })->name('dev.storage-link');

    // Clear cache utility
    Route::get('/run-clear-cache', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            return response()->json(['message' => 'Cache cleared successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    })->name('dev.clear-cache');

    // System Configuration (Local only)
    Route::get('/system-config', function () {
        $activeConfig = \App\Generated\Models\SystemConfiguration::getActive();

        return response()->json([
            'active_config' => $activeConfig,
            'css_variables' => $activeConfig ? $activeConfig->getCssVariables() : [],
            'analytics' => $activeConfig ? $activeConfig->getAnalyticsConfig() : [],
            'error_pages' => $activeConfig ? $activeConfig->getErrorPagesConfig() : [],
        ]);
    })->name('dev.system-config');

    // Website Settings (Local only) - Temporarily disabled until table is created
    Route::get('/website-settings', function () {
        // $activeSettings = \App\Generated\Models\WebsiteSettings::getActive();

        return response()->json([
            'message' => 'Website settings module created but table not yet migrated',
            'status' => 'pending_migration',
            'active_settings' => null,
            'contact_info' => [],
            'social_links' => [],
            'analytics_config' => [],
            'seo_config' => [],
            'localization' => [],
            'performance' => [],
        ]);
    })->name('dev.website-settings');

    // Web Design (Local only) - Temporarily disabled until table is created
    Route::get('/web-design', function () {
        // $activeDesign = \App\Generated\Models\WebDesign::getActive();

        return response()->json([
            'message' => 'Web design module created but table not yet migrated',
            'status' => 'pending_migration',
            'active_design' => null,
            'css_variables' => [],
            'theme_info' => [],
            'component_styles' => [],
        ]);
    })->name('dev.web-design');

    // Design Preview (Local only)
    Route::get('/design/preview/{id}', function ($id) {
        return response()->json([
            'message' => 'Design preview will be available after migration',
            'design_id' => $id,
            'status' => 'pending_migration',
        ]);
    })->name('design.preview');

    // Advanced Features (Local only) - Temporarily disabled until tables are created
    Route::prefix('advanced')->group(function () {
        // Multi-language API
        Route::get('/languages', function () {
            return response()->json([
                'message' => 'Multi-language module created but table not yet migrated',
                'status' => 'pending_migration',
                'supported_languages' => [
                    'vi' => ['name' => 'Tiếng Việt', 'flag' => '🇻🇳'],
                    'en' => ['name' => 'English', 'flag' => '🇺🇸'],
                ],
                'current_language' => 'vi',
                'translations' => [],
            ]);
        })->name('dev.languages');

        // Advanced Search API
        Route::get('/search', function () {
            return response()->json([
                'message' => 'Advanced search module created but table not yet migrated',
                'status' => 'pending_migration',
                'search_types' => ['general', 'posts', 'products'],
                'analytics' => [],
                'suggestions' => [],
            ]);
        })->name('dev.search');

        // Analytics API
        Route::get('/analytics', function () {
            return response()->json([
                'message' => 'Analytics module created but table not yet migrated',
                'status' => 'pending_migration',
                'dashboard_data' => [],
                'metrics' => [],
                'real_time' => [],
            ]);
        })->name('dev.analytics');

        // Automation API
        Route::get('/automation', function () {
            return response()->json([
                'message' => 'Automation module created but table not yet migrated',
                'status' => 'pending_migration',
                'workflows' => [],
                'statistics' => [],
                'ready_for_execution' => [],
            ]);
        })->name('dev.automation');
    });




}

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Uncomment if you need authentication
*/
// Auth::routes();

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
| Filament admin panel routes are automatically registered
| Access at: /admin
*/
