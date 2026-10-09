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

        if ($currentUser && $currentUser->company) {
            $company = $currentUser->company()->with(['products', 'users.businessCard'])->first();
        } elseif ($companyId) {
            $company = Company::with(['products', 'users.businessCard'])->find($companyId);
        } else {
            // Ziyaretçi (Oturum kapalıyken varsayılan tanıtım kartviziti)
            $company = Company::with(['products', 'users.businessCard'])->first();
        }

        if (! $company) {
            $company = Company::firstOrCreate([
                'name' => 'Vedubox Bilişim & Eğitim Teknolojileri',
                'slug' => 'vedubox',
            ], [
                'sector' => 'Eğitim Teknolojileri & SaaS Yazılım',
                'logo_url' => 'vedubox.png',
                'brand_color' => '#00A86B',
                'theme_mode' => 'light',
                'website' => 'https://vedubox.com',
                'ceo_name' => 'Muhiddin Öktem',
                'ceo_title' => 'Genel Müdür / CEO',
                'email' => 'muhiddinoktem@vedubox.com',
                'phone' => '+90 536 255 64 24',
                'user_quota' => 25,
                'plan' => 'enterprise',
            ]);
        }

        // Active Card
        $card = ($currentUser && $currentUser->businessCard) 
            ?: BusinessCard::where('company_id', $company->id)->first();
        if (! $card) {
            $card = BusinessCard::with(['user', 'company'])->first();
        }

        // Products
        $products = Product::where('company_id', $company->id)->orderBy('sort_order')->get();

        // Staff members with business cards & counts
        $staffMembers = User::where('company_id', $company->id)
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
            ->get();

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
