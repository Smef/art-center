<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OAuthCallbackController;
use App\Http\Controllers\OAuthRedirectController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserColorSchemeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherParserController;
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
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('companies', CompanyController::class)->middleware(HandlePrecognitiveRequests::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('vouchers', VoucherController::class)->middleware(HandlePrecognitiveRequests::class);
    Route::post('voucher-parse', [VoucherParserController::class, 'store'])->name('voucher-parse');
    Route::resource('projects', ProjectController::class)->middleware(HandlePrecognitiveRequests::class);
    Route::resource('project-documents', ProjectDocumentController::class)->middleware(HandlePrecognitiveRequests::class)->only(['store', 'destroy']);

    Route::middleware('hasPermission:access admin')->prefix('settings')->name('settings.')->group(function () {
        Route::resource('users', UserController::class)->middleware(HandlePrecognitiveRequests::class);
        Route::resource('roles', RoleController::class)->middleware(HandlePrecognitiveRequests::class);
    });

    Route::get(config('filesystems.private_url').'{path}', [App\Http\Controllers\PrivateFileController::class, 'show'])->where('path', '.*')->name('images.private');

    Route::put('user/color-scheme', [UserColorSchemeController::class, 'update'])->name('user.color-scheme.update');

});
