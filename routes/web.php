<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit')->middleware('throttle:5,1');
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout.post');

// Admin Dashboard Routes (Protected by auth middleware)
Route::middleware(['auth', 'admin', 'throttle:30,1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Category Management
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // Product Management
    Route::resource('products', ProductController::class);
    Route::get('/products-export-csv', [ProductController::class, 'exportCsv'])->name('products.exportCsv');
    Route::get('/products-export-pdf', [ProductController::class, 'exportPdf'])->name('products.exportPdf');
    Route::post('/products-import-csv', [ProductController::class, 'importCsv'])->name('products.importCsv');
    Route::post('/products-bulk-status', [ProductController::class, 'bulkStatus'])->name('products.bulkStatus');
    Route::post('/products-bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');

    // Shop Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/banner', [SettingController::class, 'banner'])->name('settings.banner');
    Route::get('/settings/lokasi', [SettingController::class, 'lokasi'])->name('settings.lokasi');
    Route::get('/settings/panduan-ukuran', [SettingController::class, 'panduanUkuran'])->name('settings.panduanUkuran');
    Route::post('/settings/hero-banners', [SettingController::class, 'updateHeroBanners'])->name('settings.updateHeroBanners');
    Route::post('/settings/hero-text', [SettingController::class, 'updateHeroText'])->name('settings.updateHeroText');
    Route::post('/settings/location-text', [SettingController::class, 'updateLocationText'])->name('settings.updateLocationText');
    Route::post('/settings/store-info', [SettingController::class, 'updateStoreInfo'])->name('settings.updateStoreInfo');
    Route::post('/settings/panduan-ukuran', [SettingController::class, 'updatePanduanUkuran'])->name('settings.updatePanduanUkuran');
    Route::get('/settings/password', [SettingController::class, 'password'])->name('settings.password');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.updatePassword');
});
