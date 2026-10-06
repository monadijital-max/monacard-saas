@extends('layouts.app')

@section('title', ($card->user->name ?? 'Kartvizit') . ' | ' . ($card->company->name ?? 'MonaCard'))
@section('description', ($card->user->name ?? '') . ' - ' . ($card->user->title ?? '') . ' | ' . ($card->company->name ?? ''))

@section('content')
<div class="app-container" id="app" style="margin: 0 auto; max-width: 480px; min-height: 100vh; position: relative;">
  
  <!-- Top Header Bar -->
  <header class="top-bar">
    <div class="header-left">
      <div class="brand-logo" title="{{ $card->company->name ?? 'MonaCard' }}">
        @if($card->company && $card->company->logo_url)
          <img src="{{ asset($card->company->logo_url) }}" alt="{{ $card->company->name }}" class="logo-img">
        @else
          <span style="font-weight:700; font-family:'Space Grotesk',sans-serif; color:#00A86B;">{{ $card->company->name ?? 'MonaCard' }}</span>
        @endif
      </div>
    </div>
    
    <div class="header-actions">
      <a href="{{ route('login') }}" style="font-size:12px; font-weight:600; color:#64748B; text-decoration:none; padding:6px 12px; background:rgba(0,0,0,0.04); border-radius:20px;">
        🔑 Giriş
      </a>
    </div>
  </header>

  <!-- Card Body -->
  <div class="pages-viewport" style="padding: 16px 16px 80px 16px;">
    
    <!-- Hero Profile Section -->
    <section class="hero-card" style="position:relative; text-align:center; padding: 24px 16px; background:#FFFFFF; border-radius:24px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom:16px;">
      
      <div class="avatar-wrapper" style="display:inline-block; margin-bottom:14px;">
        <div class="avatar-ring" style="width:104px; height:104px; border-radius:50%; padding:3px; background:linear-gradient(135deg, {{ $card->theme_color ?: '#00A86B' }}, #008f5a);">
          <img src="{{ $card->avatar_url ? asset($card->avatar_url) : asset('avatar_clean.png') }}" alt="{{ $card->user->name ?? 'Profil' }}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
        </div>
      </div>

      <h1 style="font-family:'Space Grotesk',sans-serif; font-size:22px; font-weight:700; color:#0F172A; margin-bottom:4px;">
        {{ $card->user->name ?? 'İsimsiz Kullanıcı' }}
      </h1>
      
      <p style="font-size:13.5px; font-weight:500; color:#64748B; margin-bottom:16px;">
        {{ $card->user->title ?? 'Çalışan' }} • {{ $card->company->name ?? 'MonaCard' }}
      </p>

      <!-- Main Action Buttons -->
      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:12px;">
        <a href="{{ route('card.vcard', $card->slug) }}" class="btn-primary" style="display:flex; align-items:center; justify-content:center; gap:6px; height:46px; background:{{ $card->theme_color ?: '#00A86B' }}; color:#FFF; border-radius:14px; text-decoration:none; font-weight:600; font-size:13.5px; box-shadow:0 4px 12px rgba(0,168,107,0.3);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
          <span>Rehbere Kaydet</span>
        </a>

        <button type="button" onclick="document.getElementById('exchangeModal').style.display='flex'" style="display:flex; align-items:center; justify-content:center; gap:6px; height:46px; background:#F1F5F9; color:#0F172A; border:1px solid #E2E8F0; border-radius:14px; font-weight:600; font-size:13.5px; cursor:pointer;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>Kartını Bırak</span>
        </button>
      </div>

      <!-- Quick Contact Pills -->
      <div style="display:flex; justify-content:center; gap:12px; margin-top:14px; flex-wrap:wrap;">
        @if($card->direct_phone || ($card->user && $card->user->phone))
          @php $phone = $card->direct_phone ?: $card->user->phone; @endphp
          <a href="tel:{{ $phone }}" title="Telefon ile Ara" style="width:40px; height:40px; border-radius:50%; background:#F8FAFC; border:1px solid #E2E8F0; display:flex; align-items:center; justify-content:center; color:#00A86B;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </a>
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" target="_blank" title="WhatsApp Mesaj Gönder" style="width:40px; height:40px; border-radius:50%; background:#25D366; color:#FFF; display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.062-2.122-.533-1.428-.595-2.336-2.05-2.406-2.146-.071-.095-.584-.778-.584-1.484 0-.706.368-1.053.499-1.196.13-.144.286-.18.382-.18.095 0 .191 0 .274.004.088.005.205-.033.319.241.118.286.405.99.44 1.062.036.072.06.155.012.25-.047.095-.072.155-.143.238-.071.084-.15.187-.214.25-.072.072-.147.151-.063.296.084.143.373.616.801.998.552.492 1.017.644 1.16.716.143.072.227.06.311-.036.084-.095.358-.417.453-.56.096-.143.191-.119.323-.072.13.048.834.393.978.465.143.071.238.107.274.167.036.06.036.345-.108.75z"/></svg>
          </a>
        @endif

        @if($card->work_email || ($card->user && $card->user->email))
          <a href="mailto:{{ $card->work_email ?: $card->user->email }}" title="E-Posta Gönder" style="width:40px; height:40px; border-radius:50%; background:#F8FAFC; border:1px solid #E2E8F0; display:flex; align-items:center; justify-content:center; color:#0284C7;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </a>
        @endif

        @if($card->website || ($card->company && $card->company->website))
          <a href="{{ $card->website ?: $card->company->website }}" target="_blank" title="Web Sitesini Ziyaret Et" style="width:40px; height:40px; border-radius:50%; background:#F8FAFC; border:1px solid #E2E8F0; display:flex; align-items:center; justify-content:center; color:#6366F1;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          </a>
        @endif
      </div>
    </section>

    <!-- Bio Section -->
    @if($card->bio)
      <section style="background:#FFFFFF; border-radius:20px; padding:20px; margin-bottom:16px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <h3 style="font-family:'Space Grotesk',sans-serif; font-size:15px; font-weight:700; color:#0F172A; margin-bottom:8px;">Hakkımda</h3>
        <p style="font-size:13.5px; color:#475569; line-height:1.6; margin:0;">
          {{ $card->bio }}
        </p>
      </section>
    @endif

    <!-- Company Solutions & Products Vitrine -->
    @if(isset($products) && $products->count() > 0)
      <section style="background:#FFFFFF; border-radius:20px; padding:20px; margin-bottom:16px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <h3 style="font-family:'Space Grotesk',sans-serif; font-size:15px; font-weight:700; color:#0F172A; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between;">
          <span>📦 Ürün ve Çözümlerimiz</span>
          <span style="font-size:12px; font-weight:500; color:#64748B;">{{ $products->count() }} Çözüm</span>
        </h3>

        <div style="display:flex; flex-direction:column; gap:12px;">
          @foreach($products as $product)
            <div style="display:flex; align-items:center; gap:14px; padding:12px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:14px;">
              <div style="width:48px; height:48px; border-radius:10px; background:#E2E8F0; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden;">
                @if($product->image_url)
                  <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                  <span style="font-size:20px;">💼</span>
                @endif
              </div>
              <div style="flex:1; min-width:0;">
                <h4 style="font-size:13.5px; font-weight:700; color:#0F172A; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ $product->name }}
                </h4>
                <p style="font-size:12px; color:#64748B; margin:0; line-height:1.4;">
                  {{ Str::limit($product->description, 60) }}
                </p>
              </div>
              @if($product->price)
                <div style="font-weight:700; font-size:13px; color:#00A86B; white-space:nowrap;">
                  {{ number_format($product->price, 0, ',', '.') }} {{ $product->currency }}
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </section>
    @endif

    <!-- Address & Office -->
    @if($card->work_address || ($card->company && $card->company->address))
      @php $address = $card->work_address ?: $card->company->address; @endphp
      <section style="background:#FFFFFF; border-radius:20px; padding:20px; margin-bottom:16px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <h3 style="font-family:'Space Grotesk',sans-serif; font-size:15px; font-weight:700; color:#0F172A; margin-bottom:10px;">📍 Ofis Adresi</h3>
        <p style="font-size:13px; color:#475569; line-height:1.6; margin-bottom:12px;">
          {{ $address }}
        </p>
        <a href="https://maps.google.com/?q={{ urlencode($address) }}" target="_blank" style="display:inline-flex; align-items:center; gap:6px; font-size:12.5px; font-weight:600; color:#00A86B; text-decoration:none;">
          <span>Haritada Yol Tarifi Al</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </section>
    @endif

    <!-- Footer Branding -->
    <div style="text-align:center; margin-top:28px; padding-bottom:20px;">
      <a href="https://monacard.com" target="_blank" style="display:inline-flex; align-items:center; gap:6px; font-size:12.5px; color:#94A3B8; text-decoration:none; font-weight:500;">
        <span>Powered by</span>
        <strong style="color:#00A86B;">MonaCard</strong>
      </a>
    </div>

  </div>
</div>

<!-- Modal: Contact Exchange / Lead Capture -->
<div id="exchangeModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
  <div style="background:#FFFFFF; border-radius:24px; padding:28px 24px; width:100%; max-width:420px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.4); position:relative;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <h3 style="font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; color:#0F172A; margin:0;">🤝 İletişim Bilgilerinizi Bırakın</h3>
      <button onclick="document.getElementById('exchangeModal').style.display='none'" style="background:none; border:none; font-size:22px; cursor:pointer; color:#64748B;">&times;</button>
    </div>
    
    <p style="font-size:13px; color:#64748B; margin-bottom:18px;">
      {{ $card->user->name ?? 'Kartvizit Sahibi' }} ile hemen iletişimde kalmak için bilgilerinizi paylaşın.
    </p>

    <form method="POST" action="{{ route('card.lead', $card->slug) }}">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Adınız Soyadınız *</label>
        <input type="text" name="name" required style="width:100%; height:42px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13.5px; outline:none;" placeholder="Örn: Ayşe Kaya">
      </div>

      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Telefon Numaranız *</label>
        <input type="text" name="phone" required style="width:100%; height:42px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13.5px; outline:none;" placeholder="+90 5XX XXX XX XX">
      </div>

      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">E-Posta Adresiniz</label>
        <input type="email" name="email" style="width:100%; height:42px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13.5px; outline:none;" placeholder="adiniz@sirket.com">
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Şirket / Ünvan</label>
        <input type="text" name="company_name" style="width:100%; height:42px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13.5px; outline:none;" placeholder="Şirket adı veya pozisyonunuz">
      </div>

      <button type="submit" style="width:100%; height:46px; background:#00A86B; color:#FFF; border:none; border-radius:12px; font-weight:700; font-size:14px; cursor:pointer;">
        Bilgilerimi Gönder
      </button>
    </form>
  </div>
</div>
@endsection
