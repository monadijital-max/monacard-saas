<?php

use App\Http\Controllers\Api\PublicCardController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UnifiedAppController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - MonaCard 2.0 (PHP Blade Engine & REST API Entegrasyonu)
|--------------------------------------------------------------------------
*/

// vCard Download Endpoint
Route::get('/cards/{slug}/vcard', [PublicCardController::class, 'downloadVcard'])->name('card.vcard');

// Authentication Routes (PHP Blade & REST)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');

// Direct Role Shortcuts
Route::redirect('/admin', '/?role=admin');
Route::redirect('/staff', '/?role=staff');
Route::redirect('/super-admin', '/?role=superadmin');

// Tüm Uygulama Ekranları (PHP Blade Engine & Dinamik Veritabanı Entegrasyonu)
Route::get('/{any?}', [UnifiedAppController::class, 'index'])->where('any', '^(?!api|sanctum|_boost).*$')->name('home');
