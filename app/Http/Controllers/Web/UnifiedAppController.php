<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Company;
use App\Models\Customer;
use App\Models\DemoRequest;
use App\Models\Meeting;
use App\Models\PricingTier;
use App\Models\Product;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedAppController extends Controller
{
    /**
     * Show Unified Interactive Application View (with role switching & live demo data)
     */
    public function index(Request $request)
    {
        $companyId = $request->query('company_id');
        $currentUser = Auth::user();

        if ($currentUser && $currentUser->company_id) {
            $company = Company::with(['products', 'users.businessCard'])->find($currentUser->company_id);
        } elseif ($companyId) {
            $company = Company::with(['products', 'users.businessCard'])->find($companyId);
        } else {
            // Ziyaretçi (Oturum kapalıyken varsayılan tanıtım kartviziti)
            $company = Company::with(['products', 'users.businessCard'])->first();
        }

        if (! $company) {
            $company = Company::firstOrCreate([
                'name' => 'MonaCard Dijital',
                'slug' => 'monacard',
            ], [
                'sector' => 'Yazılım & Teknoloji',
                'brand_color' => '#00A86B',
                'theme_mode' => 'light',
                'website' => 'https://monacard.com',
                'ceo_name' => 'Firma Yöneticisi',
                'ceo_title' => 'Yönetici',
                'email' => 'info@monacard.com',
                'phone' => '+90 555 000 00 00',
                'user_quota' => 10,
                'plan' => 'pro',
            ]);
        }

        // Active Card
        $card = null;
        if ($currentUser) {
            $card = $currentUser->businessCard ?: BusinessCard::where('company_id', $currentUser->company_id)->first();
            if (! $card && $company) {
                $card = BusinessCard::create([
                    'user_id' => $currentUser->id,
                    'company_id' => $company->id,
                    'slug' => \Illuminate\Support\Str::slug($currentUser->name).'-'.\Illuminate\Support\Str::random(4),
                    'bio' => $company->name.' bünyesinde dijital kartvizit profilim.',
                    'direct_phone' => $currentUser->phone,
                    'work_email' => $currentUser->email,
                    'theme_color' => $company->brand_color ?? '#00A86B',
                    'is_active' => true,
                ]);
            }
        } elseif ($company) {
            $card = BusinessCard::where('company_id', $company->id)->first();
        }

        if (! $card) {
            $card = BusinessCard::with(['user', 'company'])->first();
        }

        // Products
        $products = $company ? Product::where('company_id', $company->id)->orderBy('sort_order')->get() : collect();

        // Staff members with business cards & counts (Only real staff of this company)
        $staffMembers = $company ? User::where('company_id', $company->id)
            ->where('role', 'staff')
            ->with('businessCard')
            ->withCount([
                'customers as total_customers',
                'customers as hot_customers' => function ($q) {
                    $q->where('stage', 'hot');
                },
                'customers as warm_customers' => function ($q) {
                    $q->where('stage', 'warm');
                },
                'customers as cold_customers' => function ($q) {
                    $q->where('stage', 'cold');
                },
                'meetings as total_meetings',
            ])
            ->get() : collect();

        // Customers with notes & staff relation
        $customers = Customer::where('company_id', $company->id)
            ->with(['notes', 'staff'])
            ->latest()
            ->get();

        // Meetings
        $meetings = Meeting::where('company_id', $company->id)->orderBy('start_time')->get();

        // Reminders
        $reminders = Reminder::where('company_id', $company->id)->get();

        // SaaS SuperAdmin metrics
        $allCompanies = Company::withCount(['users', 'businessCards'])->get();
        $demoRequests = DemoRequest::latest()->get();
        $pricingTiers = PricingTier::where('is_active', true)->get();

        return view('app', compact(
            'currentUser',
            'company',
            'card',
            'products',
            'staffMembers',
            'customers',
            'meetings',
            'reminders',
            'allCompanies',
            'demoRequests',
            'pricingTiers'
        ));
    }
}
