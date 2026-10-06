<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndTenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_registration_creates_company_and_admin_user()
    {
        $payload = [
            'company_name' => 'Acme Corp',
            'tax_number' => '1234567890',
            'name' => 'John Doe',
            'title' => 'Genel Müdür',
            'email' => 'john@acmecorp.com',
            'phone' => '05551112233',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'company',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Acme Corp',
            'ceo_title' => 'Genel Müdür',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@acmecorp.com',
            'role' => 'company_admin',
            'title' => 'Genel Müdür',
        ]);
    }

    public function test_company_admin_can_update_profile_with_second_address()
    {
        $company = Company::create([
            'name' => 'Tech Corp',
            'slug' => 'tech-corp',
            'address' => 'Merkez Mah. No:1',
            'user_quota' => 10,
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => 'Manager User',
            'email' => 'manager@techcorp.com',
            'password' => bcrypt('secret123'),
            'role' => 'company_admin',
            'status' => 'active',
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->putJson('/api/admin/profile', [
            'address' => 'Merkez Mah. No:1 Maslak / İstanbul',
            'address2' => 'Şube: Çankaya Mah. No:15 / Ankara',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'address' => 'Merkez Mah. No:1 Maslak / İstanbul',
            'address2' => 'Şube: Çankaya Mah. No:15 / Ankara',
        ]);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $company = Company::create([
            'name' => 'Test Tech',
            'slug' => 'test-tech',
            'user_quota' => 10,
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => 'Jane Smith',
            'email' => 'jane@testtech.com',
            'password' => bcrypt('password123'),
            'role' => 'company_admin',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@testtech.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'user',
                    'token',
                ],
            ]);
    }

    public function test_new_company_starts_with_clean_empty_crm_and_meetings()
    {
        $company = Company::create([
            'name' => 'Clean Tech',
            'slug' => 'clean-tech',
            'user_quota' => 5,
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => 'Clean Admin',
            'email' => 'clean@tech.com',
            'password' => bcrypt('password123'),
            'role' => 'company_admin',
            'status' => 'active',
        ]);

        $this->actingAs($user, 'sanctum');

        // Check CRM leads is empty
        $crmResponse = $this->getJson('/api/staff/crm');
        $crmResponse->assertStatus(200);
        $this->assertEmpty($crmResponse->json('data'));

        // Check Meetings is empty
        $meetingsResponse = $this->getJson('/api/staff/meetings');
        $meetingsResponse->assertStatus(200);
        $this->assertEmpty($meetingsResponse->json('data'));

        // Check Reminders is empty
        $remindersResponse = $this->getJson('/api/staff/reminders');
        $remindersResponse->assertStatus(200);
        $this->assertEmpty($remindersResponse->json('data'));
    }

    public function test_regular_admin_cannot_access_superadmin_routes()
    {
        $company = Company::create([
            'name' => 'Normal Co',
            'slug' => 'normal-co',
            'user_quota' => 5,
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => 'Normal Admin',
            'email' => 'admin@normal.com',
            'password' => bcrypt('password123'),
            'role' => 'company_admin',
            'status' => 'active',
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/super/dashboard');
        $response->assertStatus(403);
    }
}
