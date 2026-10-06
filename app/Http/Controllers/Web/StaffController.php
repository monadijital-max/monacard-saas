<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Customer;
use App\Models\InteractionNote;
use App\Models\Meeting;
use App\Models\Reminder;
use App\Models\StaffTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    /**
     * Staff Dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $card = BusinessCard::where('user_id', $user->id)->first();
        $company = $user->company;

        // CRM Leads
        $customers = Customer::where('staff_id', $user->id)
            ->with(['interactionNotes' => function ($q) {
                $q->latest();
            }])
            ->latest('last_contact_at')
            ->get();

        // Meetings
        $meetings = Meeting::where('staff_id', $user->id)
            ->orderBy('start_time', 'asc')
            ->get();

        // Reminders
        $reminders = Reminder::where('user_id', $user->id)
            ->orderBy('is_completed', 'asc')
            ->orderBy('due_date', 'asc')
            ->get();

        // Monthly Target
        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');
        $target = StaffTarget::firstOrCreate(
            ['user_id' => $user->id, 'month' => $currentMonth, 'year' => $currentYear],
            ['company_id' => $user->company_id, 'monthly_meeting_goal' => 20, 'monthly_hot_lead_goal' => 10]
        );

        $hotLeadsCount = $customers->where('stage', 'hot')->count();
        $meetingsCount = $meetings->count();

        return view('staff.dashboard', compact(
            'user',
            'card',
            'company',
            'customers',
            'meetings',
            'reminders',
            'target',
            'hotLeadsCount',
            'meetingsCount'
        ));
    }

    /**
     * Update Staff Digital Card Profile
     */
    public function updateCard(Request $request)
    {
        $user = Auth::user();
        $card = BusinessCard::firstOrCreate(
            ['user_id' => $user->id],
            ['company_id' => $user->company_id, 'slug' => Str::slug($user->name)]
        );

        $validated = $request->validate([
            'bio' => ['nullable', 'string', 'max:1000'],
            'direct_phone' => ['nullable', 'string', 'max:30'],
            'work_email' => ['nullable', 'email', 'max:255'],
            'work_address' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'string', 'max:255'],
            'google_review_url' => ['nullable', 'string', 'max:255'],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'social_links' => ['nullable', 'array'],
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $card->avatar_url = $avatarPath;
        }

        $card->bio = $validated['bio'] ?? $card->bio;
        $card->direct_phone = $validated['direct_phone'] ?? $card->direct_phone;
        $card->work_email = $validated['work_email'] ?? $card->work_email;
        $card->work_address = $validated['work_address'] ?? $card->work_address;
        $card->website = $validated['website'] ?? $card->website;
        $card->google_review_url = $validated['google_review_url'] ?? $card->google_review_url;
        $card->theme_color = $validated['theme_color'] ?? $card->theme_color;
        if (isset($validated['social_links'])) {
            $card->social_links = $validated['social_links'];
        }

        $card->save();

        return back()->with('success', 'Kartvizit profiliniz başarıyla güncellendi!');
    }

    /**
     * Store CRM Contact Lead
     */
    public function storeCustomer(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'stage' => ['required', 'in:hot,warm,cold'],
            'source' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
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

        if (! empty($validated['note'])) {
            InteractionNote::create([
                'customer_id' => $customer->id,
                'staff_id' => $user->id,
                'company_id' => $user->company_id,
                'type' => 'text',
                'content' => $validated['note'],
            ]);
        }

        return back()->with('success', 'Müşteri adayı CRM havuzuna başarıyla eklendi.');
    }

    /**
     * Store Scheduled Meeting
     */
    public function storeMeeting(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'meeting_type' => ['required', 'in:meet,zoom,physical'],
            'start_time' => ['required', 'date'],
            'meeting_link' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        Meeting::create([
            'company_id' => $user->company_id,
            'staff_id' => $user->id,
            'title' => $validated['title'],
            'meeting_type' => $validated['meeting_type'],
            'start_time' => $validated['start_time'],
            'meeting_link' => $validated['meeting_link'] ?? null,
            'location' => $validated['location'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'scheduled',
        ]);

        return back()->with('success', 'Toplantı takvime başarıyla eklendi.');
    }

    /**
     * Store Reminder
     */
    public function storeReminder(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'due_time' => ['nullable', 'string'],
        ]);

        Reminder::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'title' => $validated['title'],
            'due_date' => $validated['due_date'] ?? null,
            'due_time' => $validated['due_time'] ?? null,
            'is_completed' => false,
        ]);

        return back()->with('success', 'Hatırlatıcı ajandaya eklendi.');
    }

    /**
     * Toggle Reminder Completion
     */
    public function toggleReminder($id)
    {
        $user = Auth::user();
        $reminder = Reminder::where('user_id', $user->id)->findOrFail($id);
        $reminder->is_completed = ! $reminder->is_completed;
        $reminder->completed_at = $reminder->is_completed ? now() : null;
        $reminder->save();

        return back()->with('success', 'Hatırlatıcı durumu güncellendi.');
    }
}
