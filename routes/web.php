<?php

use App\Http\Controllers\Api\PublicCardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - MonaCard 2.0 (PHP Blade Engine & REST API Entegrasyonu)
|--------------------------------------------------------------------------
*/

// vCard Download Endpoint
Route::get('/cards/{slug}/vcard', [PublicCardController::class, 'downloadVcard'])->name('card.vcard');

// Login Sayfası (PHP Blade)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Register Sayfası (PHP Blade)
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Direct Role Shortcuts
Route::redirect('/admin', '/?role=admin');
Route::redirect('/staff', '/?role=staff');
Route::redirect('/super-admin', '/?role=superadmin');

// Tüm Uygulama Ekranları (PHP Blade Engine)
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!api|sanctum|_boost).*$')->name('home');
