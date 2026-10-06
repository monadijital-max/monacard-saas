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
        $currentUser = Auth::user();

        if ($currentUser && $currentUser->company) {
            $company = $currentUser->company()->with(['products', 'users.businessCard'])->first();
            $card = $currentUser->businessCard ?: BusinessCard::where('company_id', $company->id)->first();
        } else {
            // Find default company (Vedubox or first created)
            $company = Company::with(['products', 'users.businessCard'])->first();
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
                    'user_quota' => 10,
                    'plan' => 'enterprise',
                ]);
            }

            // Active Card
            $card = BusinessCard::with(['user', 'company'])->first();
            if (! $card && $company) {
                $user = User::first();
                if ($user) {
                    $card = BusinessCard::create([
                        'user_id' => $user->id,
                        'company_id' => $company->id,
                        'slug' => 'muhiddin-oktem',
                        'bio' => 'Vedubox Senior Product Designer & Creative Technologist',
                        'direct_phone' => '+90 536 255 64 24',
                        'work_email' => 'muhiddinoktem@vedubox.com',
                        'work_address' => 'Ayazağa Mah. Mimar Sinan Sok. Seba Office No:21 D:45 Sarıyer / İstanbul',
                        'website' => 'https://vedubox.com',
                        'theme_color' => '#00A86B',
                        'is_active' => true,
                    ]);
                }
            }
        }

        // Products
        $products = Product::where('company_id', $company->id)->orderBy('sort_order')->get();

        // Staff members
        $staffMembers = User::where('company_id', $company->id)->with('businessCard')->get();

        // Customers
        $customers = Customer::where('company_id', $company->id)->with('interactionNotes')->latest()->get();

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
