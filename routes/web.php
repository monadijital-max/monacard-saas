<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    $path = public_path('login.html');
    if (file_exists($path)) {
        return response()->file($path);
    }
    return redirect('/');
});

Route::get('/register', function () {
    $path = public_path('register.html');
    if (file_exists($path)) {
        return response()->file($path);
    }
    return redirect('/');
});

Route::get('/{any?}', function () {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return response()->file($indexPath);
    }
    return view('welcome');
})->where('any', '^(?!api|sanctum|_boost).*$');
