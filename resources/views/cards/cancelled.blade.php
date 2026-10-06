@extends('layouts.app')

@section('title', 'Kartvizit Kullanıma Kapatıldı | MonaCard')

@section('content')
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px;">
  <div class="cancelled-card-box" style="max-width:440px; width:100%; background:rgba(15,23,42,0.9); border:1px solid rgba(239,68,68,0.3); border-radius:24px; padding:36px 28px; text-align:center; box-shadow:0 25px 50px -12px rgba(0,0,0,0.7);">
    <div style="font-size:48px; margin-bottom:14px;">⛔</div>
    <h2 style="font-family:'Space Grotesk', sans-serif; font-size:22px; color:#F8FAFC; margin-bottom:10px;">Kartvizit İptal Edildi</h2>
    <p style="font-size:14px; color:#94A3B8; line-height:1.6; margin-bottom:20px;">
      Bu dijital kartvizit ve çalışan hesabı firma yöneticisi tarafından kullanıma kapatılmıştır.
    </p>
    <div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.25); border-radius:12px; padding:12px; color:#FCA5A5; font-size:13px; margin-bottom:24px;">
      Kart Sahibi: <strong>{{ $card->user->name ?? 'Personel' }}</strong><br>
      Firma: <strong>{{ $card->company->name ?? 'Kurumsal' }}</strong>
    </div>
    <a href="{{ route('login') }}" class="btn-primary" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; width:100%; height:48px; background:#0F172A; border:1px solid rgba(255,255,255,0.15); border-radius:12px; color:#FFF; text-decoration:none; font-weight:600;">
      <span>🔑 Kurumsal Giriş Yap</span>
    </a>
  </div>
</div>
@endsection
