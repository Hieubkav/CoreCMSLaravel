<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SetupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
*/

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Routes accessible to all users without authentication
*/
Route::get('/', [HomeController::class, 'index'])->name('storeFront');

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
    Route::post('/reset', [SetupController::class, 'reset'])->name('reset')->withoutMiddleware(['csrf']);
    Route::post('/reset/{step}', [SetupController::class, 'resetStep'])->name('reset.step')->withoutMiddleware(['csrf']);
});

/*
|--------------------------------------------------------------------------
| Development Routes
|--------------------------------------------------------------------------
| Routes for development and testing - only available in local environment
*/
if (app()->environment('local')) {
    Route::get('/dev/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return response()->json([
            'message' => 'Cache cleared successfully!'
        ]);
    })->name('dev.clear-cache');
}

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
| Routes for blog functionality with Livewire
*/
Route::get('/blog', function() {
    return view('blog.index');
})->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{slug}', [App\Http\Controllers\BlogController::class, 'category'])->name('blog.category');

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
| Routes for staff functionality with Livewire
*/
Route::get('/staff', function() {
    return view('staff.index');
})->name('staff.index');
Route::get('/staff/{slug}', [App\Http\Controllers\StaffController::class, 'show'])->name('staff.show');
Route::get('/staff/position/{position}', [App\Http\Controllers\StaffController::class, 'position'])->name('staff.position');
Route::get('/api/staff', [App\Http\Controllers\StaffController::class, 'api'])->name('staff.api');

/*
|--------------------------------------------------------------------------
| Service Routes
|--------------------------------------------------------------------------
| Routes for service functionality with Livewire
*/
Route::get('/services', function() {
    return view('service.index');
})->name('services.index');
Route::get('/services/category/{category}', [App\Http\Controllers\ServiceController::class, 'category'])->name('services.category');
Route::get('/services/{slug}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');
Route::get('/services-featured', [App\Http\Controllers\ServiceController::class, 'featured'])->name('service.featured');
Route::get('/api/services', [App\Http\Controllers\ServiceController::class, 'api'])->name('service.api');
Route::get('/api/services/search', [App\Http\Controllers\ServiceController::class, 'search'])->name('service.search');
