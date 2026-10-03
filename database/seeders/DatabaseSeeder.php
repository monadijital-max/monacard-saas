<?php

namespace Database\Seeders;

use App\Models\BusinessCard;
use App\Models\Company;
use App\Models\CompanyIntegration;
use App\Models\Customer;
use App\Models\InteractionNote;
use App\Models\Meeting;
use App\Models\PricingTier;
use App\Models\Product;
use App\Models\Reminder;
use App\Models\StaffTarget;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admin
        $superAdmin = User::create([
            'name' => 'Süper Admin (SaaS Sahibi)',
            'email' => 'superadmin@monacard.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'phone' => '+90 555 000 00 00',
            'title' => 'Founder & CEO',
            'department' => 'Executive',
            'status' => 'active',
        ]);

        // 2. Demo Company (Vedubox)
        $company = Company::create([
            'name' => 'Vedubox Bilişim & Eğitim Teknolojileri',
            'slug' => 'vedubox',
            'sector' => 'Eğitim Teknolojileri & SaaS Yazılım',
            'logo_url' => 'vedubox.png',
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
            'website' => 'https://vedubox.com',
            'ceo_name' => 'Muhiddin Öktem',
            'ceo_title' => 'Genel Müdür / CEO',
            'email' => 'muhiddinoktem@vedubox.com',
            'phone' => '+90 536 255 64 24',
            'phone2' => '+90 212 900 00 00',
            'address' => 'Dalgıç Sk. Yeşilce Mh. No 3 Kağıthane / İstanbul',
            'social_links' => [
                'linkedin' => 'https://linkedin.com/company/vedubox',
                'instagram' => 'https://instagram.com/vedubox',
                'youtube' => 'https://youtube.com/@vedubox',
                'twitter' => 'https://x.com/vedubox',
            ],
            'user_quota' => 25,
            'plan' => 'enterprise',
            'subscription_status' => 'active',
        ]);

        // 3. Company Admin / Team Leader (Muhiddin Öktem)
        $adminUser = User::create([
            'company_id' => $company->id,
            'name' => 'Muhiddin Öktem',
            'email' => 'muhiddinoktem@vedubox.com',
            'password' => Hash::make('password'),
            'role' => 'company_admin',
            'phone' => '+90 536 255 64 24',
            'title' => 'Senior Product Designer & Creative Technologist',
            'department' => 'Ürün & Tasarım',
            'status' => 'active',
        ]);

        // 4. Staff Members
        $staff1 = User::create([
            'company_id' => $company->id,
            'name' => 'Ali Yıldız',
            'email' => 'ali.yildiz@vedubox.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '+90 532 111 22 33',
            'title' => 'Kurumsal Satış Yöneticisi',
            'department' => 'Satış',
            'leader_id' => $adminUser->id,
            'status' => 'active',
        ]);

        $staff2 = User::create([
            'company_id' => $company->id,
            'name' => 'Zeynep Demir',
            'email' => 'zeynep.demir@vedubox.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '+90 533 444 55 66',
            'title' => 'Müşteri Başarı Uzmanı (CSM)',
            'department' => 'Müşteri İlişkileri',
            'leader_id' => $adminUser->id,
            'status' => 'active',
        ]);

        $staff3 = User::create([
            'company_id' => $company->id,
            'name' => 'Caner Erkin',
            'email' => 'caner.erkin@vedubox.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '+90 535 777 88 99',
            'title' => 'İş Geliştirme Temsilcisi (BDR)',
            'department' => 'İş Geliştirme',
            'leader_id' => $adminUser->id,
            'status' => 'active',
        ]);

        // 5. Business Cards
        BusinessCard::create([
            'user_id' => $adminUser->id,
            'company_id' => $company->id,
            'slug' => 'muhiddin-oktem',
            'avatar_url' => 'avatar_clean.png',
            'bio' => 'Kullanıcı deneyimi (UX/UI), dijital ürün mimarisi ve modern web teknolojileri üzerine 10+ yıldır kurumsal çözümler üretiyorum. Vedubox bünyesinde yenilikçi dijital kartvizit ve CRM ekosistemlerinin ürün liderliğini yürütüyorum.',
            'direct_phone' => '+90 536 255 64 24',
            'work_email' => 'muhiddinoktem@vedubox.com',
            'work_address' => 'Dalgıç Sk. Yeşilce Mh. No 3 Kağıthane / İstanbul',
            'website' => 'https://vedubox.com',
            'social_links' => [
                'linkedin' => 'https://linkedin.com/in/muhiddinoktem',
                'twitter' => 'https://x.com/muhiddinoktem',
                'instagram' => 'https://instagram.com/muhiddinoktem',
                'github' => 'https://github.com/monadijital',
                'whatsapp' => 'https://wa.me/905362556424',
            ],
            'google_review_url' => 'https://g.page/r/vedubox/review',
            'theme_color' => '#00A86B',
            'view_count' => 1240,
            'vcard_download_count' => 380,
            'is_active' => true,
        ]);

        BusinessCard::create([
            'user_id' => $staff1->id,
            'company_id' => $company->id,
            'slug' => 'ali-yildiz',
            'avatar_url' => 'avatar.png',
            'bio' => 'Vedubox Kurumsal Çözümler ve Dijital Dönüşüm Satış Lideri.',
            'direct_phone' => '+90 532 111 22 33',
            'work_email' => 'ali.yildiz@vedubox.com',
            'work_address' => 'Dalgıç Sk. Yeşilce Mh. No 3 Kağıthane / İstanbul',
            'website' => 'https://vedubox.com',
            'social_links' => [
                'linkedin' => 'https://linkedin.com/in/aliyildiz',
            ],
            'theme_color' => '#00A86B',
            'view_count' => 450,
            'vcard_download_count' => 120,
            'is_active' => true,
        ]);

        // 6. Products & Solutions Vitrine
        Product::create([
            'company_id' => $company->id,
            'name' => 'Vedubox Live LMS',
            'description' => 'Canlı ders, video kütüphanesi ve online sınav modüllerini birleştiren hepsi bir arada eğitim platformu.',
            'image_url' => 'MonaCard.png',
            'price' => 14900.00,
            'currency' => '₺',
            'link' => 'https://vedubox.com/lms',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'company_id' => $company->id,
            'name' => 'MonaCard Enterprise NFC',
            'description' => 'Kurumsal şirketler için akıllı NFC kartvizit ve yapay zeka destekli CRM ekosistemi.',
            'image_url' => 'PCard.png',
            'price' => 750.00,
            'currency' => '₺',
            'link' => 'https://vedubox.com/monacard',
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        // 7. Customers / Leads
        $c1 = Customer::create([
            'company_id' => $company->id,
            'staff_id' => $adminUser->id,
            'name' => 'Ahmet Yılmaz',
            'company_name' => 'Turkcell Akademi',
            'title' => 'Eğitim & Gelişim Direktörü',
            'phone' => '+90 532 999 88 77',
            'email' => 'ahmet.yilmaz@turkcell.com.tr',
            'stage' => 'hot',
            'source' => 'nfc_tap',
            'last_contact_at' => now()->subHours(2),
        ]);

        $c2 = Customer::create([
            'company_id' => $company->id,
            'staff_id' => $adminUser->id,
            'name' => 'Selin Kaya',
            'company_name' => 'Eczacıbaşı Holding',
            'title' => 'İnsan Kaynakları Müdürü',
            'phone' => '+90 533 111 22 44',
            'email' => 'selin.kaya@eczacibasi.com.tr',
            'stage' => 'warm',
            'source' => 'ocr_scan',
            'last_contact_at' => now()->subDay(),
        ]);

        $c3 = Customer::create([
            'company_id' => $company->id,
            'staff_id' => $staff1->id,
            'name' => 'Burak Demir',
            'company_name' => 'Koç Sistem',
            'title' => 'IT Satın Alma Sorumlusu',
            'phone' => '+90 535 333 44 55',
            'email' => 'burak.demir@kocsistem.com.tr',
            'stage' => 'cold',
            'source' => 'manual',
            'last_contact_at' => now()->subDays(4),
        ]);

        // 8. Interaction Notes
        InteractionNote::create([
            'customer_id' => $c1->id,
            'staff_id' => $adminUser->id,
            'company_id' => $company->id,
            'type' => 'voice',
            'content' => 'Demo sunumu gerçekleştirildi. 500 kişilik ekip için teklif talep ettiler. Yönetim kurulu onayına sunulacak.',
            'ai_summary' => 'Turkcell Akademi 500 kişilik lisans teklifi bekliyor.',
            'hubspot_synced' => true,
            'salesforce_synced' => true,
        ]);

        InteractionNote::create([
            'customer_id' => $c2->id,
            'staff_id' => $adminUser->id,
            'company_id' => $company->id,
            'type' => 'text',
            'content' => 'Tasarım ekibi için 20 adet özel siyah mat NFC MonaCard talep edildi. Logo dosyaları alındı.',
            'hubspot_synced' => true,
            'salesforce_synced' => false,
        ]);

        // 9. Meetings & Reminders
        Meeting::create([
            'company_id' => $company->id,
            'staff_id' => $adminUser->id,
            'title' => 'Turkcell Akademi - LMS Entegrasyon & Sözleşme Görüşmesi',
            'meeting_type' => 'meet',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'start_time' => now()->addDays(1)->setHour(14)->setMinute(0),
            'end_time' => now()->addDays(1)->setHour(15)->setMinute(0),
            'status' => 'scheduled',
            'participant_customer_ids' => [$c1->id],
        ]);

        Reminder::create([
            'user_id' => $adminUser->id,
            'company_id' => $company->id,
            'title' => 'Selin Hanım\'a MonaCard özel teklif PDF dosyasını ilet',
            'due_date' => now()->toDateString(),
            'due_time' => '16:30',
            'is_completed' => false,
        ]);

        // 10. Staff Targets
        StaffTarget::create([
            'user_id' => $adminUser->id,
            'company_id' => $company->id,
            'month' => now()->month,
            'year' => now()->year,
            'monthly_meeting_goal' => 25,
            'monthly_hot_lead_goal' => 15,
        ]);

        // 11. Company Integrations
        CompanyIntegration::create([
            'company_id' => $company->id,
            'provider' => 'hubspot',
            'is_active' => true,
            'credentials' => [
                'portal_id' => '4829104',
                'api_key' => 'demo_hubspot_token_placeholder',
            ],
            'settings' => [
                'auto_sync_contacts' => true,
                'auto_sync_voice' => true,
                'stage' => 'lead',
            ],
            'last_synced_at' => now(),
        ]);

        // 12. Pricing Tiers
        PricingTier::create([
            'name' => '1 - 10 Kullanıcı',
            'min_users' => 1,
            'max_users' => 10,
            'annual_price_per_user' => 750.00,
            'currency' => '₺',
            'is_active' => true,
        ]);

        PricingTier::create([
            'name' => '11 - 25 Kullanıcı',
            'min_users' => 11,
            'max_users' => 25,
            'annual_price_per_user' => 650.00,
            'currency' => '₺',
            'is_active' => true,
        ]);

        PricingTier::create([
            'name' => '26 - 50 Kullanıcı',
            'min_users' => 26,
            'max_users' => 50,
            'annual_price_per_user' => 550.00,
            'currency' => '₺',
            'is_active' => true,
        ]);

        PricingTier::create([
            'name' => '50+ Enterprise Kurumsal',
            'min_users' => 51,
            'max_users' => 9999,
            'annual_price_per_user' => 450.00,
            'currency' => '₺',
            'is_active' => true,
        ]);
    }
}
