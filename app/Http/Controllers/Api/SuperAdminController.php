<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Company;
use App\Models\DemoRequest;
use App\Models\PricingTier;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    /**
     * Super Admin Dashboard (SaaS Master Overview)
     */
    public function dashboard(Request $request): JsonResponse
    {
        $companiesCount = Company::count();
        $totalUsers = User::where('role', '!=', 'superadmin')->count();
        $pendingDemos = DemoRequest::where('status', 'pending')->count();
        
        // Calculate estimated MRR based on company quotas & active tiers
        $mrr = Company::where('subscription_status', 'active')->sum('user_quota') * 45; // ~45 TL/user monthly avg

        $companies = Company::withCount('users')
            ->orderBy('created_at', 'desc')
            ->get();

        $demoRequests = DemoRequest::orderBy('created_at', 'desc')->take(10)->get();
        $pricingTiers = PricingTier::where('is_active', true)->orderBy('min_users', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'metrics' => [
                    'active_companies' => $companiesCount,
                    'total_users' => $totalUsers,
                    'pending_demos' => $pendingDemos,
                    'mrr' => $mrr,
                ],
                'companies' => $companies,
                'demo_requests' => $demoRequests,
                'pricing_tiers' => $pricingTiers,
            ],
        ]);
    }

    /**
     * Create New Corporate Company & Administrator
     */
    public function storeCompany(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'sector' => 'nullable|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6',
            'admin_phone' => 'nullable|string|max:50',
            'user_quota' => 'required|integer|min:1',
            'plan' => 'required|string',
        ]);

        $company = Company::create([
            'name' => $validated['company_name'],
            'slug' => str($validated['company_name'])->slug() . '-' . rand(10, 99),
            'sector' => $validated['sector'] ?? null,
            'user_quota' => $validated['user_quota'],
            'plan' => $validated['plan'],
            'subscription_status' => 'active',
            'brand_color' => '#00A86B',
        ]);

        $admin = User::create([
            'company_id' => $company->id,
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'phone' => $validated['admin_phone'] ?? null,
            'role' => 'company_admin',
            'title' => 'Firma Yöneticisi',
            'department' => 'Yönetim',
            'status' => 'active',
        ]);

        // Generate Business Card for Admin
        BusinessCard::create([
            'user_id' => $admin->id,
            'company_id' => $company->id,
            'slug' => str($admin->name)->slug() . '-' . rand(100, 999),
            'direct_phone' => $admin->phone,
            'work_email' => $admin->email,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Firma ve Yönetici hesabı başarıyla oluşturuldu.',
            'data' => [
                'company' => $company,
                'admin' => $admin,
            ],
        ], 201);
    }

    /**
     * Update Company Quota
     */
    public function updateCompanyQuota(Request $request, int $id): JsonResponse
    {
        $company = Company::findOrFail($id);
        $validated = $request->validate([
            'user_quota' => 'required|integer|min:1',
            'plan' => 'nullable|string',
            'subscription_status' => 'nullable|string',
        ]);

        $company->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Firma lisans kotası güncellendi.',
            'data' => $company,
        ]);
    }
}
