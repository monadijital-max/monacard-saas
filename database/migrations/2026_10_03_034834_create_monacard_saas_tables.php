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
        // 1. Digital Business Cards
        Schema::create('business_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('slug')->unique(); // e.g. /muhiddin-oktem
            $table->string('avatar_url')->nullable();
            $table->text('bio')->nullable();
            $table->string('direct_phone')->nullable();
            $table->string('work_email')->nullable();
            $table->text('work_address')->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable(); // linkedin, twitter, instagram, youtube, github, etc.
            $table->string('google_review_url')->nullable();
            $table->string('theme_color')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('vcard_download_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Company Products & Solutions Vitrine
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 10)->default('₺');
            $table->string('link')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Customer CRM Pool (Leads & Contacts)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('title')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('stage')->default('warm'); // hot, warm, cold
            $table->string('source')->default('manual'); // manual, ocr_scan, nfc_tap, qr_scan
            $table->timestamp('last_contact_at')->nullable();
            $table->timestamps();
        });

        // 4. Interaction Notes & Voice Transcripts with HubSpot / Salesforce sync
        Schema::create('interaction_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('type')->default('text'); // voice, text, ocr
            $table->text('content');
            $table->string('audio_url')->nullable();
            $table->text('ai_summary')->nullable();
            $table->boolean('hubspot_synced')->default(false);
            $table->boolean('salesforce_synced')->default(false);
            $table->string('hubspot_note_id')->nullable();
            $table->string('salesforce_note_id')->nullable();
            $table->timestamps();
        });

        // 5. Meetings & Calendar
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('meeting_type')->default('meet'); // meet, zoom, physical
            $table->string('meeting_link')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->json('participant_customer_ids')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Reminders / Agenda To-Dos
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('title');
            $table->date('due_date')->nullable();
            $table->time('due_time')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 7. Monthly Staff Targets (CRM & Meeting goals)
        Schema::create('staff_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->unsignedTinyInteger('month'); // 1 - 12
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('monthly_meeting_goal')->default(20);
            $table->unsignedInteger('monthly_hot_lead_goal')->default(10);
            $table->timestamps();

            $table->unique(['user_id', 'month', 'year']);
        });

        // 8. Company Integrations (Zoom, Google, HubSpot, Salesforce)
        Schema::create('company_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('provider'); // hubspot, salesforce, zoom, google
            $table->boolean('is_active')->default(true);
            $table->text('credentials')->nullable(); // Encrypted JSON
            $table->json('settings')->nullable(); // stage mapping, auto sync toggles
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'provider']);
        });

        // 9. Demo Requests (Super Admin)
        Schema::create('demo_requests', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('employee_count')->nullable(); // e.g. "21-50"
            $table->string('status')->default('pending'); // pending, contacted, converted, rejected
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 10. Pricing Tiers (Super Admin)
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "1-10 Çalışan", "21-50 Çalışan"
            $table->unsignedInteger('min_users');
            $table->unsignedInteger('max_users');
            $table->decimal('annual_price_per_user', 10, 2);
            $table->string('currency', 10)->default('₺');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_tiers');
        Schema::dropIfExists('demo_requests');
        Schema::dropIfExists('company_integrations');
        Schema::dropIfExists('staff_targets');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('interaction_notes');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('business_cards');
    }
};
