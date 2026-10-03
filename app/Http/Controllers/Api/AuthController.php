<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * User Login (SuperAdmin, CompanyAdmin, Staff)
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::with(['company', 'businessCard'])->where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Geçersiz e-posta veya şifre.'],
            ]);
        }

        if ($user->status === 'deactivated') {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartvizitiniz ve hesabınız yönetici tarafından askıya alınmıştır.',
            ], 403);
        }

        // Create Sanctum Token
        $token = $user->createToken('monacard_api_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Giriş başarılı.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'title' => $user->title,
                    'department' => $user->department,
                    'status' => $user->status,
                    'company' => $user->company,
                    'card_slug' => $user->businessCard?->slug,
                ],
            ],
        ]);
    }

    /**
     * User / Company Registration
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'sector' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:50',
            'title' => 'nullable|string|max:255',
        ]);

        $company = \App\Models\Company::create([
            'name' => $validated['company_name'],
            'slug' => str($validated['company_name'])->slug() . '-' . rand(100, 999),
            'sector' => $validated['sector'] ?? 'Bilişim & Hizmet',
            'user_quota' => 10,
            'plan' => 'enterprise_trial',
            'subscription_status' => 'active',
            'brand_color' => '#00A86B',
            'theme_mode' => 'light',
            'interface_language' => 'tr',
            'staff_features' => [
                'hubspot' => true,
                'voiceNotes' => true,
                'products' => true,
                'socialLinks' => true,
                'reviews' => true,
                'vcard' => true,
            ],
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'title' => $validated['title'] ?? 'Kurucu & Yönetici',
            'department' => 'Yönetim',
            'role' => 'company_admin',
            'status' => 'active',
        ]);

        $cardSlug = str($user->name)->slug() . '-' . rand(100, 999);
        $card = \App\Models\BusinessCard::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'slug' => $cardSlug,
            'direct_phone' => $user->phone,
            'work_email' => $user->email,
            'theme_color' => '#00A86B',
            'is_active' => true,
        ]);

        $token = $user->createToken('monacard_api_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Kayıt başarıyla tamamlandı.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'title' => $user->title,
                    'department' => $user->department,
                    'status' => $user->status,
                    'company' => $company,
                    'card_slug' => $cardSlug,
                ],
            ],
        ], 201);
    }

    /**
     * User Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Oturum kapatıldı.',
        ]);
    }

    /**
     * Current authenticated user profile
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['company', 'businessCard', 'leader']);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }
}
