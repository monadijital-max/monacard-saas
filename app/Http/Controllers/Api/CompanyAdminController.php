<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Company;
use App\Models\CompanyIntegration;
use App\Models\Customer;
use App\Models\InteractionNote;
use App\Models\Meeting;
use App\Models\Product;
use App\Models\StaffTarget;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyAdminController extends Controller
{
    /**
     * Executive Dashboard Analytics & Metrics
     */
    public function dashboard(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id;

        $totalClients = Customer::where('company_id', $companyId)->count();
        $hotClients = Customer::where('company_id', $companyId)->where('stage', 'hot')->count();
        $warmClients = Customer::where('company_id', $companyId)->where('stage', 'warm')->count();
        $coldClients = Customer::where('company_id', $companyId)->where('stage', 'cold')->count();
        $totalMeetings = Meeting::where('company_id', $companyId)->count();
        $meetingRate = $totalClients > 0 ? round(($totalMeetings / $totalClients) * 100, 1) : 0;

        // Leaderboard (Top performers)
        $topStaff = User::where('company_id', $companyId)
            ->whereIn('role', ['staff', 'company_admin'])
            ->withCount([
                'customers as total_customers',
                'customers as hot_customers' => function ($q) {
                    $q->where('stage', 'hot');
                },
                'meetings as total_meetings',
            ])
            ->orderBy('hot_customers', 'desc')
            ->take(5)
            ->get();

        // Recent live stream
        $recentNotes = InteractionNote::with(['customer', 'staff'])
            ->where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'metrics' => [
                    'total_clients' => $totalClients,
                    'hot_clients' => $hotClients,
                    'warm_clients' => $warmClients,
                    'cold_clients' => $coldClients,
                    'total_meetings' => $totalMeetings,
                    'meeting_rate' => $meetingRate,
                ],
                'leaderboard' => $topStaff,
                'live_stream' => $recentNotes,
            ],
        ]);
    }

    /**
     * Get Company Staff List with Hierarchy
     */
    public function getStaffList(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $staff = User::where('company_id', $companyId)
            ->with(['businessCard', 'subordinates.businessCard', 'leader'])
            ->withCount(['customers', 'meetings'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $staff,
        ]);
    }

    /**
     * Add new staff member (with quota check)
     */
    public function storeStaff(Request $request): JsonResponse
    {
        $company = $request->user()->company;
        $currentStaffCount = User::where('company_id', $company->id)->count();

        if ($currentStaffCount >= $company->user_quota) {
            return response()->json([
                'status' => 'quota_exceeded',
                'message' => "Kullanıcı lisans limitine ulaştınız ({$company->user_quota} personel). Yeni personel eklemek için paketinizi yükseltin.",
            ], 422);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'role' => 'required|in:staff,company_admin',
            'leader_id' => 'nullable|exists:users,id',
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'] ?? '12345678'),
            'phone' => $validated['phone'] ?? null,
            'title' => $validated['title'],
            'department' => $validated['department'],
            'role' => $validated['role'],
            'leader_id' => $validated['leader_id'] ?? null,
            'status' => 'active',
        ]);

        // Generate Business Card for staff
        $card = BusinessCard::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'slug' => str($user->name)->slug() . '-' . rand(100, 999),
            'direct_phone' => $user->phone,
            'work_email' => $user->email,
            'website' => $company->website,
            'work_address' => $company->address,
            'theme_color' => $company->brand_color,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Personel başarıyla oluşturuldu ve dijital kartviziti tanımlandı.',
            'data' => $user->load('businessCard'),
        ], 201);
    }

    /**
     * Toggle staff status (Deactivate / Reactivate Cardvizit)
     */
    public function toggleStaffStatus(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $staff = User::where('id', $id)->where('company_id', $companyId)->firstOrFail();

        $staff->status = $staff->status === 'active' ? 'deactivated' : 'active';
        $staff->save();

        if ($staff->businessCard) {
            $staff->businessCard->is_active = ($staff->status === 'active');
            $staff->businessCard->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => $staff->status === 'active' 
                ? "{$staff->name} personeline ait dijital kartvizit yeniden aktif edildi." 
                : "{$staff->name} kartviziti iptal edildi. Giriş yetkisi durduruldu.",
            'data' => $staff,
        ]);
    }

    /**
     * Set Monthly Staff Performance Goal
     */
    public function setStaffTarget(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $staff = User::where('id', $id)->where('company_id', $companyId)->firstOrFail();

        $validated = $request->validate([
            'monthly_meeting_goal' => 'required|integer|min:1',
            'monthly_hot_lead_goal' => 'nullable|integer|min:1',
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer',
        ]);

        $target = StaffTarget::updateOrCreate(
            [
                'user_id' => $staff->id,
                'month' => $validated['month'] ?? now()->month,
                'year' => $validated['year'] ?? now()->year,
            ],
            [
                'company_id' => $companyId,
                'monthly_meeting_goal' => $validated['monthly_meeting_goal'],
                'monthly_hot_lead_goal' => $validated['monthly_hot_lead_goal'] ?? round($validated['monthly_meeting_goal'] * 0.5),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Personel aylık hedefi başarıyla kaydedildi.',
            'data' => $target,
        ]);
    }

    /**
     * Mass Transfer all CRM leads and meetings to another staff
     */
    public function transferClients(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id;

        $validated = $request->validate([
            'from_staff_id' => 'required|exists:users,id',
            'to_staff_id' => 'required|exists:users,id|different:from_staff_id',
        ]);

        $fromStaff = User::where('id', $validated['from_staff_id'])->where('company_id', $companyId)->firstOrFail();
        $toStaff = User::where('id', $validated['to_staff_id'])->where('company_id', $companyId)->firstOrFail();

        DB::transaction(function () use ($fromStaff, $toStaff, $companyId) {
            Customer::where('company_id', $companyId)
                ->where('staff_id', $fromStaff->id)
                ->update(['staff_id' => $toStaff->id]);

            Meeting::where('company_id', $companyId)
                ->where('staff_id', $fromStaff->id)
                ->update(['staff_id' => $toStaff->id]);

            InteractionNote::where('company_id', $companyId)
                ->where('staff_id', $fromStaff->id)
                ->update(['staff_id' => $toStaff->id]);
        });

        return response()->json([
            'status' => 'success',
            'message' => "{$fromStaff->name} personeline ait tüm müşteri ve görüşme kayıtları başarıyla {$toStaff->name} personeline devredildi.",
        ]);
    }

    /**
     * Get Company-wide CRM pool
     */
    public function getAllCrm(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $staffId = $request->query('staff_id');
        $stage = $request->query('stage');

        $query = Customer::with(['staff:id,name,title', 'notes'])
            ->where('company_id', $companyId);

        if ($staffId && $staffId !== 'all') {
            $query->where('staff_id', $staffId);
        }

        if ($stage && $stage !== 'all') {
            $query->where('stage', $stage);
        }

        $customers = $query->orderBy('last_contact_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $customers,
        ]);
    }

    /**
     * Add new CRM Customer directly
     */
    public function storeCustomer(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'stage' => 'nullable|string|in:hot,warm,cold,kazanildi,kaybedildi,gorusuldu,yeni',
            'staff_id' => 'nullable',
            'note' => 'nullable|string',
        ]);

        $staffId = $validated['staff_id'] ?? $request->user()->id;
        if (is_string($staffId) && str_starts_with($staffId, 'staff-')) {
            $staffId = str_replace('staff-', '', $staffId);
        }

        $customer = Customer::create([
            'company_id' => $companyId,
            'staff_id' => $staffId,
            'name' => $validated['name'],
            'company_name' => $validated['company_name'] ?? $validated['company'] ?? 'Bireysel Müşteri',
            'title' => $validated['title'] ?? 'Yetkili',
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'stage' => $validated['stage'] ?? 'warm',
            'source' => 'panel',
            'last_contact_at' => now(),
        ]);

        if (! empty($validated['note'])) {
            InteractionNote::create([
                'customer_id' => $customer->id,
                'staff_id' => $staffId,
                'company_id' => $companyId,
                'type' => 'text',
                'note' => $validated['note'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Müşteri başarıyla CRM havuzuna eklendi.',
            'customer' => $customer->load('notes', 'staff'),
        ], 201);
    }

    /**
     * Update Company Settings (Brand Color, Theme Mode, Interface Language, Staff Feature Toggles)
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        $validated = $request->validate([
            'brand_color' => 'sometimes|string|max:10',
            'theme_mode' => 'sometimes|in:light,dark',
            'interface_language' => 'sometimes|in:tr,en',
            'staff_features' => 'sometimes|array',
        ]);

        $company->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kurumsal ayarlar ve yetkilendirmeler başarıyla kaydedildi.',
            'data' => $company,
        ]);
    }

    /**
     * Update Company Profile (Identity, Address, Socials)
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sector' => 'sometimes|string|nullable|max:255',
            'website' => 'sometimes|string|nullable|max:255',
            'ceo_name' => 'sometimes|string|nullable|max:255',
            'ceo_title' => 'sometimes|string|nullable|max:255',
            'email' => 'sometimes|email|nullable|max:255',
            'phone' => 'sometimes|string|nullable|max:50',
            'phone2' => 'sometimes|string|nullable|max:50',
            'address' => 'sometimes|string|nullable',
            'address2' => 'sometimes|string|nullable',
            'social_links' => 'sometimes|array',
        ]);

        $company->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Firma kimlik bilgileri ve iletişim kanalları güncellendi.',
            'data' => $company,
        ]);
    }

    /**
     * Product Management
     */
    public function getProducts(Request $request): JsonResponse
    {
        $products = Product::where('company_id', $request->user()->company_id)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function storeProduct(Request $request): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'price' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'link' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $product = Product::create(array_merge($validated, ['company_id' => $companyId]));

        return response()->json([
            'status' => 'success',
            'message' => 'Ürün/Hizmet başarıyla vitrine eklendi.',
            'data' => $product,
        ], 201);
    }

    public function deleteProduct(Request $request, int $id): JsonResponse
    {
        $product = Product::where('id', $id)->where('company_id', $request->user()->company_id)->firstOrFail();
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ürün vitrinden silindi.',
        ]);
    }
}
