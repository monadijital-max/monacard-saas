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
     * Show Unified Interactive Application View (with multi-tenant role switching & live dynamic database data)
     */
    public function index(Request $request)
    {
        $companyId = $request->query('company_id');
        $slug = $request->route('any') ?: $request->query('slug');
        $currentUser = Auth::user();
        $card = null;
        $company = null;

        // 1. Slug based resolution (e.g. /muhiddin-oktem or /monacard or /cards/slug)
        if ($slug && !in_array($slug, ['admin', 'staff', 'super-admin', 'login', 'register'])) {
            $card = BusinessCard::with(['user', 'company'])->where('slug', $slug)->first();
            if ($card && $card->company) {
                $company = $card->company;
            } else {
                $compBySlug = Company::with(['products', 'users.businessCard'])->where('slug', $slug)->first();
                if ($compBySlug) {
                    $company = $compBySlug;
                }
            }
        }

        // 2. User / Company ID based resolution
        if (!$company) {
            if ($currentUser && $currentUser->company_id) {
                $company = Company::with(['products', 'users.businessCard'])->find($currentUser->company_id);
            } elseif ($companyId) {
                $company = Company::with(['products', 'users.businessCard'])->find($companyId);
            } else {
                // Ziyaretçi (Oturum kapalıyken varsayılan tanıtım kartviziti)
                $company = Company::with(['products', 'users.businessCard'])->first();
            }
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

        // 3. Active Card resolution
        if (!$card) {
            if ($currentUser) {
                $card = $currentUser->businessCard ?: BusinessCard::where('user_id', $currentUser->id)->first();
                if (! $card && $currentUser->company_id) {
                    $card = BusinessCard::where('company_id', $currentUser->company_id)->first();
                }
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
        }

        if (! $card) {
            $card = BusinessCard::with(['user', 'company'])->first();
        }

        // 4. Multi-Tenant Dynamic Scoped Collections (STRICTLY FOR CURRENT COMPANY)
        $products = $company ? Product::where('company_id', $company->id)->orderBy('sort_order')->get() : collect();

        // Team members (Both staff and company admin users for this specific company)
        $staffMembers = $company ? User::where('company_id', $company->id)
            ->whereIn('role', ['staff', 'company_admin'])
            ->with(['businessCard', 'staffTargets'])
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

        // Customers with notes & staff relation for current company
        $customers = $company ? Customer::where('company_id', $company->id)
            ->with(['notes', 'staff'])
            ->latest()
            ->get() : collect();

        // Meetings for current company
        $meetings = $company ? Meeting::where('company_id', $company->id)->orderBy('start_time')->get() : collect();

        // Reminders for current company
        $reminders = $company ? Reminder::where('company_id', $company->id)->get() : collect();

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
