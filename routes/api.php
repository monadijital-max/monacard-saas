<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyAdminController;
use App\Http\Controllers\Api\PublicCardController;
use App\Http\Controllers\Api\StaffPortalController;
use App\Http\Controllers\Api\SuperAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MonaCard 2.0 RESTful API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC CARD ENDPOINTS (Rate Limited & Cached)
// ==========================================
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/cards/{slug}', [PublicCardController::class, 'show']);
    Route::get('/cards/{slug}/vcard', [PublicCardController::class, 'downloadVcard']);
});

// ==========================================
// 2. AUTHENTICATION
// ==========================================
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum,web');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum,web');
});

// ==========================================
// 3. STAFF PORTAL (Role: Staff & Admin)
// ==========================================
Route::middleware(['auth:sanctum,web'])->prefix('staff')->group(function () {
    Route::get('/card', [StaffPortalController::class, 'getMyCard']);
    Route::put('/card', [StaffPortalController::class, 'updateMyCard']);
    
    Route::get('/crm', [StaffPortalController::class, 'getCrmCustomers']);
    Route::post('/crm', [StaffPortalController::class, 'storeCustomer']);
    Route::post('/crm/{id}/note', [StaffPortalController::class, 'addInteractionNote']);
    
    Route::get('/meetings', [StaffPortalController::class, 'getMeetings']);
    Route::post('/meetings', [StaffPortalController::class, 'storeMeeting']);
    
    Route::get('/reminders', [StaffPortalController::class, 'getReminders']);
    Route::post('/reminders', [StaffPortalController::class, 'storeReminder']);
    Route::put('/reminders/{id}/toggle', [StaffPortalController::class, 'toggleReminder']);
});

// ==========================================
// 4. COMPANY ADMIN (Role: Company Admin)
// ==========================================
Route::middleware(['auth:sanctum,web'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [CompanyAdminController::class, 'dashboard']);
    
    Route::get('/staff', [CompanyAdminController::class, 'getStaffList']);
    Route::post('/staff', [CompanyAdminController::class, 'storeStaff']);
    Route::put('/staff/{id}/toggle-status', [CompanyAdminController::class, 'toggleStaffStatus']);
    Route::post('/staff/{id}/target', [CompanyAdminController::class, 'setStaffTarget']);
    Route::post('/staff/transfer-clients', [CompanyAdminController::class, 'transferClients']);
    
    Route::get('/crm', [CompanyAdminController::class, 'getAllCrm']);
    Route::post('/crm', [CompanyAdminController::class, 'storeCustomer']);
    Route::put('/settings', [CompanyAdminController::class, 'updateSettings']);
    Route::put('/profile', [CompanyAdminController::class, 'updateProfile']);
    
    Route::get('/products', [CompanyAdminController::class, 'getProducts']);
    Route::post('/products', [CompanyAdminController::class, 'storeProduct']);
    Route::delete('/products/{id}', [CompanyAdminController::class, 'deleteProduct']);
});

// ==========================================
// 5. SUPER ADMIN (Role: SaaS Owner)
// ==========================================
Route::middleware(['auth:sanctum,web'])->prefix('super')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard']);
    Route::post('/companies', [SuperAdminController::class, 'storeCompany']);
    Route::put('/companies/{id}/quota', [SuperAdminController::class, 'updateCompanyQuota']);
});
