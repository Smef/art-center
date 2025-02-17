<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseTeacherController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OAuthCallbackController;
use App\Http\Controllers\OAuthRedirectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserColorSchemeController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});

// Socialite routes
Route::get('oauth/{provider}/redirect', OAuthRedirectController::class)->name('auth.redirect');
Route::get('oauth/{provider}/callback', OAuthCallbackController::class)->name('auth.callback');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    HandlePrecognitiveRequests::class,
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get(config('filesystems.private_url').'{path}', [App\Http\Controllers\PrivateFileController::class, 'show'])->where('path', '.*')->name('images.private');

    Route::put('user/color-scheme', [UserColorSchemeController::class, 'update'])->name('user.color-scheme.update');

    Route::resource('locations', LocationController::class);

    Route::resource('courses', CourseController::class);

    Route::resource('course-teachers', CourseTeacherController::class)->only(['store', 'destroy']);

    Route::resource('enrollments', EnrollmentController::class)->only(['store', 'destroy']);

    Route::middleware('hasPermission:access admin')->prefix('settings')->name('settings.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

});
