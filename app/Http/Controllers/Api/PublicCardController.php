<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class PublicCardController extends Controller
{
    /**
     * Get Public Business Card details (Ultra-fast cached endpoint for NFC/QR loads)
     */
    public function show(string $slug): JsonResponse
    {
        $card = Cache::remember("public_card_{$slug}", 300, function () use ($slug) {
            return BusinessCard::with([
                'user:id,name,title,department,phone,email,status,company_id',
                'company:id,name,sector,logo_url,brand_color,theme_mode,interface_language,staff_features,website,address,social_links',
                'company.products' => function ($query) {
                    $query->where('is_featured', true)->orderBy('sort_order', 'asc');
                }
            ])
            ->where('slug', $slug)
            ->first();
        });

        if (!$card) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartvizit bulunamadı.',
            ], 404);
        }

        if (!$card->is_active || $card->user?->status === 'deactivated') {
            return response()->json([
                'status' => 'deactivated',
                'message' => 'Bu dijital kartvizit firma yöneticisi tarafından kullanıma kapatılmıştır.',
                'company_name' => $card->company?->name,
            ], 403);
        }

        // Increment view count
        $card->increment('view_count');

        return response()->json([
            'status' => 'success',
            'data' => [
                'card' => $card,
                'interface_language' => $card->company?->interface_language ?? 'tr',
                'staff_features' => $card->company?->staff_features ?? [],
            ],
        ]);
    }

    /**
     * Download .vcf (vCard 3.0) file for saving directly to smartphone contacts
     */
    public function downloadVcard(string $slug): Response
    {
        $card = BusinessCard::with(['user', 'company'])->where('slug', $slug)->firstOrFail();

        $card->increment('vcard_download_count');

        $user = $card->user;
        $company = $card->company;

        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= "N:;" . ($user->name ?? 'MonaCard') . ";;;\r\n";
        $vcard .= "FN:" . ($user->name ?? 'MonaCard') . "\r\n";
        if ($company?->name) {
            $vcard .= "ORG:" . $company->name . "\r\n";
        }
        if ($user->title) {
            $vcard .= "TITLE:" . $user->title . "\r\n";
        }
        if ($card->direct_phone || $user->phone) {
            $vcard .= "TEL;TYPE=WORK,VOICE:" . ($card->direct_phone ?: $user->phone) . "\r\n";
        }
        if ($card->work_email || $user->email) {
            $vcard .= "EMAIL;TYPE=PREF,INTERNET:" . ($card->work_email ?: $user->email) . "\r\n";
        }
        if ($card->website || $company?->website) {
            $vcard .= "URL:" . ($card->website ?: $company->website) . "\r\n";
        }
        if ($card->work_address || $company?->address) {
            $vcard .= "ADR;TYPE=WORK:;;" . ($card->work_address ?: $company->address) . ";;;;\r\n";
        }
        if ($card->bio) {
            $vcard .= "NOTE:" . str_replace("\n", "\\n", $card->bio) . "\r\n";
        }
        $vcard .= "END:VCARD\r\n";

        $filename = ($card->slug ?: 'contact') . '.vcf';

        return response($vcard, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
