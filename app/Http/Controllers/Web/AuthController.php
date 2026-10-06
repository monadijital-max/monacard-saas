<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember', true);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status === 'deactivated') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Hesabınız veya kartvizit erişiminiz yönetici tarafından askıya alınmıştır.',
                ])->withInput($request->only('email'));
            }

            return $this->redirectBasedOnRole($user)
                ->with('success', 'Hoş geldiniz, '.$user->name.'!');
        }

        return back()->withErrors([
            'email' => 'Girdiğiniz e-posta veya şifre hatalı.',
        ])->withInput($request->only('email'));
    }

    /**
     * Quick 1-click Demo Login for development and test drive
     */
    public function demoLogin($role)
    {
        $user = null;
        if ($role === 'superadmin') {
            $user = User::where('role', 'superadmin')->first();
        } elseif ($role === 'admin' || $role === 'company_admin') {
            $user = User::where('role', 'company_admin')->first();
        } elseif ($role === 'staff') {
            $user = User::where('role', 'staff')->first();
        }

        if (! $user) {
            $user = User::first();
        }

        if ($user) {
            Auth::login($user, true);
            request()->session()->regenerate();

            return $this->redirectBasedOnRole($user)
                ->with('success', 'Demo hesaba başarıyla giriş yapıldı: '.$user->name.' ('.ucfirst($user->role).')');
        }

        return redirect()->route('login')->withErrors(['email' => 'Demo kullanıcı bulunamadı. Lütfen veritabanını seed ediniz.']);
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Handle SaaS Company & Admin Registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'plan' => ['nullable', 'string'],
        ]);

        $companySlug = Str::slug($validated['company_name']);
        if (Company::where('slug', $companySlug)->exists()) {
            $companySlug .= '-'.Str::random(4);
        }

        // 1. Create Company
        $company = Company::create([
            'name' => $validated['company_name'],
            'slug' => $companySlug,
            'sector' => $validated['sector'] ?? 'Teknoloji',
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
            'ceo_name' => $validated['name'],
            'ceo_title' => $validated['title'] ?? 'Kurucu & Yönetici',
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'user_quota' => 10,
            'plan' => $validated['plan'] ?? 'pro',
            'subscription_status' => 'active',
        ]);

        // 2. Create Company Admin User
        $user = User::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'title' => $validated['title'] ?? 'Firma Yöneticisi',
            'department' => 'Yönetim',
            'role' => 'company_admin',
            'status' => 'active',
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Create Business Card for User
        $cardSlug = Str::slug($validated['name']);
        if (BusinessCard::where('slug', $cardSlug)->exists()) {
            $cardSlug .= '-'.Str::random(4);
        }

        BusinessCard::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'slug' => $cardSlug,
            'bio' => $company->name.' bünyesinde dijital kartvizit profilim.',
            'direct_phone' => $user->phone,
            'work_email' => $user->email,
            'theme_color' => '#00A86B',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Tebrikler! Firmanız ve yönetici profiliniz başarıyla oluşturuldu.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Oturum başarıyla kapatıldı.');
    }

    /**
     * Redirect authenticated users based on role
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role === 'company_admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('staff.dashboard');
        }
    }
}
