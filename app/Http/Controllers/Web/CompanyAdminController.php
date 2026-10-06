<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StaffTarget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyAdminController extends Controller
{
    /**
     * Company Admin Dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company) {
            abort(403, 'Bağlı olduğunuz bir şirket bulunamadı.');
        }

        // Staff members
        $staffMembers = User::where('company_id', $company->id)
            ->with(['businessCard', 'staffTargets' => function ($q) {
                $q->where('month', (int) date('n'))->where('year', (int) date('Y'));
            }])
            ->withCount('customers')
            ->get();

        // Products
        $products = Product::where('company_id', $company->id)
            ->orderBy('sort_order', 'asc')
            ->get();

        // All Company CRM Contacts
        $customers = Customer::where('company_id', $company->id)
            ->with('staff')
            ->latest('last_contact_at')
            ->get();

        // Key Metrics
        $totalStaff = $staffMembers->count();
        $activeCardsCount = BusinessCard::where('company_id', $company->id)->where('is_active', true)->count();
        $totalCrmCount = $customers->count();
        $hotLeadsCount = $customers->where('stage', 'hot')->count();

        return view('admin.dashboard', compact(
            'user',
            'company',
            'staffMembers',
            'products',
            'customers',
            'totalStaff',
            'activeCardsCount',
            'totalCrmCount',
            'hotLeadsCount'
        ));
    }

    /**
     * Store new staff member under company
     */
    public function storeStaff(Request $request)
    {
        $admin = Auth::user();
        $company = $admin->company;

        // Quota check
        $currentStaffCount = User::where('company_id', $company->id)->count();
        if ($currentStaffCount >= $company->user_quota) {
            return back()->withErrors([
                'name' => "Kullanıcı kotanız dolmuştur ({$currentStaffCount}/{$company->user_quota}). Yeni personel eklemek için lütfen paketinizi yükseltiniz.",
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'title' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'monthly_meeting_goal' => ['nullable', 'integer', 'min:0'],
            'monthly_hot_lead_goal' => ['nullable', 'integer', 'min:0'],
        ]);

        $staff = User::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'title' => $validated['title'] ?? 'Uzman',
            'department' => $validated['department'] ?? 'Satış & Pazarlama',
            'role' => 'staff',
            'status' => 'active',
            'password' => Hash::make($validated['password']),
        ]);

        // Default Business Card
        $cardSlug = Str::slug($validated['name']);
        if (BusinessCard::where('slug', $cardSlug)->exists()) {
            $cardSlug .= '-'.Str::random(4);
        }

        BusinessCard::create([
            'user_id' => $staff->id,
            'company_id' => $company->id,
            'slug' => $cardSlug,
            'bio' => $company->name.' dijital kartvizit profilim.',
            'direct_phone' => $staff->phone,
            'work_email' => $staff->email,
            'theme_color' => $company->brand_color ?: '#00A86B',
            'is_active' => true,
        ]);

        // Staff monthly target
        StaffTarget::create([
            'user_id' => $staff->id,
            'company_id' => $company->id,
            'month' => (int) date('n'),
            'year' => (int) date('Y'),
            'monthly_meeting_goal' => $validated['monthly_meeting_goal'] ?? 20,
            'monthly_hot_lead_goal' => $validated['monthly_hot_lead_goal'] ?? 10,
        ]);

        return back()->with('success', "Personel '{$staff->name}' başarıyla eklendi ve dijital kartviziti oluşturuldu!");
    }

    /**
     * Toggle staff status (Active / Deactivated - Madde 5)
     */
    public function toggleStaffStatus($id)
    {
        $admin = Auth::user();
        $staff = User::where('company_id', $admin->company_id)->findOrFail($id);

        if ($staff->id === $admin->id) {
            return back()->withErrors(['error' => 'Kendi yönetici hesabınızı devre dışı bırakamazsınız.']);
        }

        $staff->status = $staff->status === 'active' ? 'deactivated' : 'active';
        $staff->save();

        // Also update card status
        BusinessCard::where('user_id', $staff->id)->update([
            'is_active' => ($staff->status === 'active'),
        ]);

        $statusText = $staff->status === 'active' ? 'aktif edildi' : 'erişimi iptal edildi/kapatıldı';

        return back()->with('success', "Personel '{$staff->name}' kartvizit ve hesap durumu {$statusText}.");
    }

    /**
     * Transfer clients from one staff to another
     */
    public function transferClients(Request $request)
    {
        $admin = Auth::user();
        $validated = $request->validate([
            'from_staff_id' => ['required', 'exists:users,id'],
            'to_staff_id' => ['required', 'exists:users,id'],
        ]);

        $fromStaff = User::where('company_id', $admin->company_id)->findOrFail($validated['from_staff_id']);
        $toStaff = User::where('company_id', $admin->company_id)->findOrFail($validated['to_staff_id']);

        $count = Customer::where('company_id', $admin->company_id)
            ->where('staff_id', $fromStaff->id)
            ->update(['staff_id' => $toStaff->id]);

        return back()->with('success', "{$count} adet müşteri kontağı '{$fromStaff->name}' üzerinden '{$toStaff->name}' kullanıcısına aktarıldı.");
    }

    /**
     * Store Product in Vitrine
     */
    public function storeProduct(Request $request)
    {
        $admin = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'link' => ['nullable', 'url', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        Product::create([
            'company_id' => $admin->company_id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency' => $validated['currency'] ?? '₺',
            'link' => $validated['link'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'is_featured' => $request->boolean('is_featured', true),
            'sort_order' => Product::where('company_id', $admin->company_id)->count() + 1,
        ]);

        return back()->with('success', 'Ürün / Çözüm vitrine başarıyla eklendi.');
    }

    /**
     * Delete Product
     */
    public function deleteProduct($id)
    {
        $admin = Auth::user();
        $product = Product::where('company_id', $admin->company_id)->findOrFail($id);
        $product->delete();

        return back()->with('success', 'Ürün vitrinden silindi.');
    }

    /**
     * Update Company Settings & Branding
     */
    public function updateSettings(Request $request)
    {
        $admin = Auth::user();
        $company = $admin->company;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:255'],
            'brand_color' => ['required', 'string', 'max:20'],
            'theme_mode' => ['required', 'in:light,dark'],
            'website' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'phone2' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'address2' => ['nullable', 'string', 'max:500'],
            'staff_features' => ['nullable', 'array'],
        ]);

        $company->name = $validated['name'];
        $company->sector = $validated['sector'] ?? $company->sector;
        $company->brand_color = $validated['brand_color'];
        $company->theme_mode = $validated['theme_mode'];
        $company->website = $validated['website'] ?? $company->website;
        $company->email = $validated['email'] ?? $company->email;
        $company->phone = $validated['phone'] ?? $company->phone;
        $company->phone2 = $validated['phone2'] ?? $company->phone2;
        $company->address = $validated['address'] ?? $company->address;
        $company->address2 = $validated['address2'] ?? $company->address2;

        if (isset($validated['staff_features'])) {
            $company->staff_features = $validated['staff_features'];
        }

        $company->save();

        return back()->with('success', 'Şirket ayarları ve kurumsal kimlik bilgileri güncellendi.');
    }
}
