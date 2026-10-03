<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sector')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('brand_color')->default('#00A86B');
            $table->string('theme_mode')->default('light'); // light, dark
            $table->string('interface_language')->default('tr'); // tr, en
            $table->json('staff_features')->nullable(); // toggles for hubspot, voiceNotes, products, etc.
            $table->string('website')->nullable();
            $table->string('ceo_name')->nullable();
            $table->string('ceo_title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->text('address')->nullable();
            $table->json('social_links')->nullable();
            $table->integer('user_quota')->default(10);
            $table->string('plan')->default('enterprise');
            $table->string('subscription_status')->default('active');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('role')->default('staff'); // superadmin, company_admin, staff
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('title')->nullable();
            $table->string('department')->nullable();
            $table->foreignId('leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('active'); // active, deactivated
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('companies');
    }
};
