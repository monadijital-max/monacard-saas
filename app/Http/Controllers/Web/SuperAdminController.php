<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Company;
use App\Models\DemoRequest;
use App\Models\PricingTier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    /**
     * SaaS Super Admin Dashboard
     */
    public function index()
    {
        $companies = Company::withCount(['users', 'businessCards'])->latest()->get();
        $demoRequests = DemoRequest::latest()->get();
        $pricingTiers = PricingTier::where('is_active', true)->get();

        $totalCompanies = $companies->count();
        $totalCards = BusinessCard::count();
        $totalUsers = User::count();
        $pendingDemos = $demoRequests->where('status', 'pending')->count();

        // Estimated MRR calculation
        $estimatedMrr = $companies->sum('user_quota') * 45; // ~₺45/user/month

        return view('superadmin.dashboard', compact(
            'companies',
            'demoRequests',
            'pricingTiers',
            'totalCompanies',
            'totalCards',
            'totalUsers',
            'pendingDemos',
            'estimatedMrr'
        ));
    }

    /**
     * Store new SaaS customer company
     */
    public function storeCompany(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:6'],
            'user_quota' => ['required', 'integer', 'min:1'],
            'plan' => ['required', 'string'],
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.Str::random(3),
            'sector' => $validated['sector'] ?? 'Genel',
            'user_quota' => $validated['user_quota'],
            'plan' => $validated['plan'],
            'subscription_status' => 'active',
            'brand_color' => '#00A86B',
        ]);

        $admin = User::create([
            'company_id' => $company->id,
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'role' => 'company_admin',
            'status' => 'active',
            'password' => Hash::make($validated['admin_password']),
        ]);

        BusinessCard::create([
            'user_id' => $admin->id,
            'company_id' => $company->id,
            'slug' => Str::slug($admin->name).'-'.Str::random(3),
            'bio' => $company->name.' kurumsal profil.',
            'theme_color' => '#00A86B',
            'is_active' => true,
        ]);

        return back()->with('success', "Şirket '{$company->name}' ve yönetici hesabı başarıyla tanımlandı.");
    }

    /**
     * Update Company Quota and Status
     */
    public function updateQuota(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'user_quota' => ['required', 'integer', 'min:1'],
            'subscription_status' => ['required', 'in:active,trial,suspended,cancelled'],
            'plan' => ['required', 'string'],
        ]);

        $company->user_quota = $validated['user_quota'];
        $company->subscription_status = $validated['subscription_status'];
        $company->plan = $validated['plan'];
        $company->save();

        return back()->with('success', "'{$company->name}' kotaları ve abonelik durumu güncellendi.");
    }

    /**
     * Update Demo Request Status
     */
    public function updateDemoStatus(Request $request, $id)
    {
        $demo = DemoRequest::findOrFail($id);
        $validated = $request->validate([
            'status' => ['required', 'in:pending,contacted,converted,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $demo->status = $validated['status'];
        if (isset($validated['notes'])) {
            $demo->notes = $validated['notes'];
        }
        $demo->save();

        return back()->with('success', 'Demo talebi durumu güncellendi.');
    }
}
