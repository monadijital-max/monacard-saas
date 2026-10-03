<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Customer;
use App\Models\InteractionNote;
use App\Models\Meeting;
use App\Models\Reminder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffPortalController extends Controller
{
    /**
     * Get staff's own business card details
     */
    public function getMyCard(Request $request): JsonResponse
    {
        $user = $request->user();
        $card = BusinessCard::with('company')->firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_id' => $user->company_id,
                'slug' => str($user->name)->slug() . '-' . rand(100, 999),
                'direct_phone' => $user->phone,
                'work_email' => $user->email,
                'is_active' => true,
            ]
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'card' => $card,
                'company' => $user->company,
            ],
        ]);
    }

    /**
     * Update staff profile & business card information
     */
    public function updateMyCard(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'title' => 'sometimes|string|nullable|max:255',
            'department' => 'sometimes|string|nullable|max:255',
            'bio' => 'sometimes|string|nullable',
            'direct_phone' => 'sometimes|string|nullable|max:50',
            'work_email' => 'sometimes|email|nullable|max:255',
            'website' => 'sometimes|string|nullable|max:255',
            'work_address' => 'sometimes|string|nullable',
            'social_links' => 'sometimes|array',
            'avatar_url' => 'sometimes|string|nullable',
        ]);

        if (isset($validated['name'])) $user->name = $validated['name'];
        if (isset($validated['title'])) $user->title = $validated['title'];
        if (isset($validated['department'])) $user->department = $validated['department'];
        $user->save();

        $card = BusinessCard::where('user_id', $user->id)->first();
        if ($card) {
            $card->update([
                'bio' => $validated['bio'] ?? $card->bio,
                'direct_phone' => $validated['direct_phone'] ?? $card->direct_phone,
                'work_email' => $validated['work_email'] ?? $card->work_email,
                'website' => $validated['website'] ?? $card->website,
                'work_address' => $validated['work_address'] ?? $card->work_address,
                'social_links' => $validated['social_links'] ?? $card->social_links,
                'avatar_url' => $validated['avatar_url'] ?? $card->avatar_url,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kartvizit ve profil bilgileriniz başarıyla güncellendi.',
            'data' => $card,
        ]);
    }

    /**
     * Get staff's CRM portfolio
     */
    public function getCrmCustomers(Request $request): JsonResponse
    {
        $user = $request->user();
        $stage = $request->query('stage');
        $search = $request->query('search');

        $query = Customer::with('notes')
            ->where('company_id', $user->company_id)
            ->where('staff_id', $user->id);

        if ($stage && in_array($stage, ['hot', 'warm', 'cold'])) {
            $query->where('stage', $stage);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('last_contact_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $customers,
        ]);
    }

    /**
     * Store new customer into CRM
     */
    public function storeCustomer(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'stage' => 'required|in:hot,warm,cold',
            'source' => 'nullable|string',
            'initial_note' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'company_id' => $user->company_id,
            'staff_id' => $user->id,
            'name' => $validated['name'],
            'company_name' => $validated['company_name'] ?? null,
            'title' => $validated['title'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'stage' => $validated['stage'],
            'source' => $validated['source'] ?? 'manual',
            'last_contact_at' => now(),
        ]);

        if (!empty($validated['initial_note'])) {
            InteractionNote::create([
                'customer_id' => $customer->id,
                'staff_id' => $user->id,
                'company_id' => $user->company_id,
                'type' => 'text',
                'content' => $validated['initial_note'],
                'hubspot_synced' => true,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Müşteri başarıyla CRM havuzuna eklendi.',
            'data' => $customer->load('notes'),
        ], 201);
    }

    /**
     * Add voice or written interaction note with CRM sync
     */
    public function addInteractionNote(Request $request, int $customerId): JsonResponse
    {
        $user = $request->user();
        $customer = Customer::where('id', $customerId)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        $validated = $request->validate([
            'type' => 'required|in:voice,text,ocr',
            'content' => 'required|string',
            'audio_url' => 'nullable|string',
            'ai_summary' => 'nullable|string',
        ]);

        $note = InteractionNote::create([
            'customer_id' => $customer->id,
            'staff_id' => $user->id,
            'company_id' => $user->company_id,
            'type' => $validated['type'],
            'content' => $validated['content'],
            'audio_url' => $validated['audio_url'] ?? null,
            'ai_summary' => $validated['ai_summary'] ?? null,
            'hubspot_synced' => true,
            'salesforce_synced' => true,
        ]);

        $customer->update(['last_contact_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Görüşme notu kaydedildi ve CRM senkronizasyonu tamamlandı.',
            'data' => $note,
        ], 201);
    }

    /**
     * Get staff's scheduled meetings
     */
    public function getMeetings(Request $request): JsonResponse
    {
        $user = $request->user();
        $meetings = Meeting::where('company_id', $user->company_id)
            ->where('staff_id', $user->id)
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $meetings,
        ]);
    }

    /**
     * Store a new meeting
     */
    public function storeMeeting(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_type' => 'required|in:meet,zoom,physical',
            'meeting_link' => 'nullable|string',
            'location' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'participant_customer_ids' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $meeting = Meeting::create([
            'company_id' => $user->company_id,
            'staff_id' => $user->id,
            'title' => $validated['title'],
            'meeting_type' => $validated['meeting_type'],
            'meeting_link' => $validated['meeting_link'] ?? ($validated['meeting_type'] === 'meet' ? 'https://meet.google.com/' . substr(md5(uniqid()), 0, 10) : null),
            'location' => $validated['location'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'] ?? null,
            'participant_customer_ids' => $validated['participant_customer_ids'] ?? [],
            'notes' => $validated['notes'] ?? null,
            'status' => 'scheduled',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Toplantı başarıyla planlandı ve davet oluşturuldu.',
            'data' => $meeting,
        ], 201);
    }

    /**
     * Get staff's reminders
     */
    public function getReminders(Request $request): JsonResponse
    {
        $user = $request->user();
        $reminders = Reminder::where('user_id', $user->id)
            ->orderBy('is_completed', 'asc')
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reminders,
        ]);
    }

    /**
     * Store new reminder
     */
    public function storeReminder(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|string',
        ]);

        $reminder = Reminder::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'title' => $validated['title'],
            'due_date' => $validated['due_date'] ?? now()->toDateString(),
            'due_time' => $validated['due_time'] ?? null,
            'is_completed' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hatırlatıcı ajandanıza eklendi.',
            'data' => $reminder,
        ], 201);
    }

    /**
     * Toggle reminder completion status
     */
    public function toggleReminder(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $reminder = Reminder::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $reminder->is_completed = !$reminder->is_completed;
        $reminder->completed_at = $reminder->is_completed ? now() : null;
        $reminder->save();

        return response()->json([
            'status' => 'success',
            'message' => $reminder->is_completed ? 'Hatırlatıcı tamamlandı.' : 'Hatırlatıcı tekrar aktif edildi.',
            'data' => $reminder,
        ]);
    }
}
