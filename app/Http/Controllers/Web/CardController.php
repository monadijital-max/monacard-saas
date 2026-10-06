<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use App\Models\Customer;
use App\Models\InteractionNote;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CardController extends Controller
{
    /**
     * Show Public Digital Business Card
     */
    public function show($slug = null)
    {
        $card = null;
        if ($slug) {
            $card = BusinessCard::with(['user', 'company'])->where('slug', $slug)->first();
        }

        // Fallback to first active card (e.g. demo card) if no slug specified
        if (! $card) {
            $card = BusinessCard::with(['user', 'company'])->where('is_active', true)->first();
        }

        if (! $card) {
            abort(404, 'Dijital kartvizit bulunamadı.');
        }

        // If card or user is deactivated
        if (! $card->is_active || ($card->user && $card->user->status === 'deactivated')) {
            return view('cards.cancelled', compact('card'));
        }

        // Increment view count
        $card->increment('view_count');

        // Fetch company products
        $products = Product::where('company_id', $card->company_id)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get();

        return view('cards.show', compact('card', 'products'));
    }

    /**
     * Download vCard (.vcf)
     */
    public function downloadVcard($slug)
    {
        $card = BusinessCard::with(['user', 'company'])->where('slug', $slug)->firstOrFail();
        $card->increment('vcard_download_count');

        $user = $card->user;
        $company = $card->company;

        $nameParts = explode(' ', $user->name);
        $lastName = count($nameParts) > 1 ? array_pop($nameParts) : '';
        $firstName = implode(' ', $nameParts);

        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= "N:{$lastName};{$firstName};;;\r\n";
        $vcard .= "FN:{$user->name}\r\n";
        $vcard .= "ORG:{$company->name}\r\n";
        $vcard .= "TITLE:{$user->title}\r\n";
        if ($card->direct_phone || $user->phone) {
            $phone = $card->direct_phone ?: $user->phone;
            $vcard .= "TEL;TYPE=CELL,VOICE:{$phone}\r\n";
        }
        if ($card->work_email || $user->email) {
            $email = $card->work_email ?: $user->email;
            $vcard .= "EMAIL;TYPE=WORK,INTERNET:{$email}\r\n";
        }
        if ($card->website || $company->website) {
            $web = $card->website ?: $company->website;
            $vcard .= "URL:{$web}\r\n";
        }
        if ($card->work_address || $company->address) {
            $addr = $card->work_address ?: $company->address;
            $addrClean = str_replace(["\r", "\n"], ' ', $addr);
            $vcard .= "ADR;TYPE=WORK:;;{$addrClean};;;;\r\n";
        }
        if ($card->bio) {
            $bioClean = str_replace(["\r", "\n"], ' ', $card->bio);
            $vcard .= "NOTE:{$bioClean}\r\n";
        }
        $vcard .= "END:VCARD\r\n";

        $filename = ($card->slug ?: 'contact').'.vcf';

        return Response::make($vcard, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Submit Lead / Contact Exchange Form from Digital Card
     */
    public function submitLead(Request $request, $slug)
    {
        $card = BusinessCard::with(['user', 'company'])->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer = Customer::create([
            'company_id' => $card->company_id,
            'staff_id' => $card->user_id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
            'title' => $validated['title'] ?? null,
            'stage' => 'hot',
            'source' => 'nfc_tap',
            'last_contact_at' => now(),
        ]);

        if (! empty($validated['note'])) {
            InteractionNote::create([
                'customer_id' => $customer->id,
                'staff_id' => $card->user_id,
                'company_id' => $card->company_id,
                'type' => 'text',
                'content' => 'Dijital kartvizit üzerinden iletişim formu dolduruldu: '.$validated['note'],
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bilgileriniz başarıyla iletildi. En kısa sürede sizinle iletişime geçilecektir.',
            ]);
        }

        return back()->with('success', 'İletişim bilgileriniz başarıyla kaydedildi! Teşekkür ederiz.');
    }
}
