<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <title>Muhiddin Öktem | MonaCard Dijital Kartvizit</title>
  <meta name="description" content="Muhiddin Öktem - Senior Product Designer & Creative Technologist. MonaCard Akıllı Dijital Kartvizit Profili.">
  <meta name="theme-color" content="#00A86B">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  
  <!-- OpenGraph / Social Meta Tags -->
  <meta property="og:title" content="Muhiddin Öktem | MonaCard Dijital Kartvizit">
  <meta property="og:description" content="Senior Product Designer & Creative Technologist | Vedubox">
  <meta property="og:image" content="avatar_clean.png">
  <meta property="og:type" content="profile">
  
  <!-- Google Fonts: Space Grotesk -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="{{ asset('style.css') }}?v={{ file_exists(public_path('style.css')) ? filemtime(public_path('style.css')) : time() }}">
</head>
<body class="role-customer">

  <!-- Desktop Ambient Glow -->
  <div class="desktop-bg-decoration">
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>
  </div>

  <!-- Role Switcher Bar (Tüm Roller Arası Hızlı Geçiş) -->
  <aside class="role-switcher-banner" aria-label="Görünüm Seçici" id="roleSwitcherBanner">
    <span class="role-switcher-label">Görünüm:</span>
    <div class="role-toggle-group">
      <button class="role-toggle-btn active" id="btnRoleCustomer" title="Müşterinin gördüğü sade kartvizit">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>1. Müşteri Ekranı</span>
      </button>
      <button class="role-toggle-btn" id="btnRoleStaff" title="Personel yönetim paneli">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        <span>2. Personel Paneli</span>
      </button>
      <button class="role-toggle-btn" id="btnRoleAdmin" title="Firma Yöneticisi & Executive CRM Paneli">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        <span>3. Firma Yöneticisi</span>
      </button>
      <button class="role-toggle-btn" id="btnRoleSuperAdmin" title="SaaS Sahibi & Süper Admin Paneli (Demo Talepleri, Firmalar, Fiyatlandırma)">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>4. Süper Admin (SaaS Sahibi)</span>
      </button>
    </div>

    <!-- Auth Action Buttons (Standalone Pages / Logout) -->
    <div class="role-auth-actions">
      @auth
        <button type="button" class="role-auth-btn admin-logout-btn" id="btnTopLogout" onclick="logoutUser()" title="Oturumu Kapat / Çıkış Yap" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.4); color: #FCA5A5;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span id="authBtnLabel">🚪 Çıkış Yap ({{ Auth::user()->name }})</span>
        </button>
      @else
        <a href="/login" class="role-auth-btn" id="btnGoLogin" title="Giriş Sayfası">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
          <span id="authBtnLabel">🔑 Giriş Yap</span>
        </a>
        <a href="/register" class="role-auth-btn role-reg-btn" id="btnGoRegister" title="Yeni Firma Kayıt Sayfası">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
          <span>✨ Firma Kaydı</span>
        </a>
      @endauth
    </div>
  </aside>

  <!-- Mobile App Wrapper -->
  <div class="app-container" id="app">
    
    <!-- Top Header Bar (Hafif Gölgeli & MonaCard Yazısız Sadece Logo) -->
    <header class="top-bar" id="topHeader">
      <div class="header-left">
        <!-- Back button on sub-pages -->
        <button class="page-back-btn hidden" id="headerBackBtn" title="Geri Dön" aria-label="Geri">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F172A" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
        </button>
        <!-- Logo (Vedubox Firma Logosu) -->
        <div class="brand-logo" id="brandLogoWrap" title="Vedubox">
          <img src="vedubox.png" alt="Vedubox Logo" class="logo-img" id="brandLogo">
        </div>
        <span class="header-subpage-title hidden" id="headerSubpageTitle"></span>
      </div>
      
      <!-- Top Profile & Language Actions -->
      <div class="header-actions">
        <!-- Language Switcher (TR / ENG) (Sadece Personel / Yönetici görür, Müşteri ekranında dil seçeneği olmaz) -->
        <div class="lang-switcher-pill mini staff-only" id="mobileLangSwitcher">
          <button type="button" class="lang-btn active" data-lang="tr" title="Türkçe">TR</button>
          <button type="button" class="lang-btn" data-lang="en" title="English">ENG</button>
        </div>

        <button class="profile-icon-btn staff-only" id="headerProfileBtn" aria-label="Profil Bilgilerini Düzenle" title="Profil Bilgilerini Düzenle">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <span class="edit-badge-dot" title="Düzenleme Aktif"></span>
        </button>

        <button class="profile-icon-btn staff-only admin-logout-btn" id="headerLogoutBtn" aria-label="Çıkış Yap" title="Çıkış Yap" style="margin-left: 2px;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
        </button>
      </div>
    </header>

    <!-- Pages Container (Tüm sayfalar kendi alanında açılır) -->
    <div class="pages-viewport" id="pagesViewport">
      
      <!-- Cancelled Card Access Blocker (Madde 5) -->
      <div class="card-cancelled-overlay hidden" id="staffCancelledCardOverlay">
        <div class="cancelled-card-box">
          <div class="cancelled-icon-ring">⛔</div>
          <h3 class="cancelled-title">Kartvizit İptal Edildi</h3>
          <p class="cancelled-desc">Bu dijital kartvizit ve hesap firma yöneticisi tarafından kullanıma kapatılmıştır. Giriş yetkiniz bulunmamaktadır.</p>
          <div class="cancelled-info-pill">
            <span>Durum: <strong>Erişim İptal Edildi</strong></span>
          </div>
          <button type="button" class="btn-primary mt-3" id="btnSwitchAdminFromCancelled" style="background:#0F172A; font-size:13px; width:100%; border-radius:12px; height:44px; display:inline-flex; align-items:center; justify-content:center; gap:6px;">
            <span>🏢 Firma Yöneticisi Paneline Geç</span>
          </button>
        </div>
      </div>

      <!-- =========================================================
           SAYFA 1: ANASAYFA (Dijital Kartvizit)
           ========================================================= -->
      <div class="page-view active" id="pageHome">
        
        <!-- HERO PROFILE CARD -->
        <section class="hero-card">
          <div class="hero-bg-gradient"></div>
          <div class="avatar-wrapper">
            <div class="avatar-ring">
              <img src="avatar_clean.png" alt="Muhiddin Öktem" class="avatar-img" id="userAvatar">
            </div>
          </div>

          <h1 class="user-name" id="displayFullName">Muhiddin Öktem</h1>
          <p class="user-title" id="displayTitle">Senior Product Designer &amp; Creative Technologist</p>

          <!-- Main Action Buttons -->
          <div class="action-buttons-group">
            <!-- Rehbere Kaydet -->
            <button class="btn-primary" id="btnSaveContact" title="Rehbere Kaydet (Tüm Bilgilerle vCard İndir)">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <line x1="19" y1="8" x2="19" y2="14"></line>
                <line x1="22" y1="11" x2="16" y2="11"></line>
              </svg>
              <span>Rehbere Kaydet</span>
            </button>

            <!-- Paylaş Butonu -->
            <button class="btn-icon" id="btnShareModal" aria-label="Kartviziti Paylaş" title="Paylaş">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="18" cy="5" r="3"></circle>
                <circle cx="6" cy="12" r="3"></circle>
                <circle cx="18" cy="19" r="3"></circle>
                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
              </svg>
            </button>

            <!-- QR Butonu (SADECE PERSONEL GÖRÜR, Müşteriye Gösterilmez) -->
            <button class="btn-icon staff-only" id="btnQrModal" aria-label="QR Kodu Göster" title="Karekod">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                <line x1="18" y1="7" x2="18.01" y2="7"></line>
                <line x1="7" y1="18" x2="7.01" y2="18"></line>
                <line x1="18" y1="18" x2="18.01" y2="18"></line>
              </svg>
            </button>
          </div>
        </section>

        <!-- GOOGLE REVIEWS BADGE -->
        <section class="review-badge-card" id="reviewSection">
          <div class="google-logo-wrapper">
            <svg width="28" height="28" viewBox="0 0 24 24">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
          </div>

          <div class="review-info">
            <div class="rating-row">
              <span class="rating-score">4.9</span>
              <div class="stars-group" aria-label="5 üzerinden 4.9 puan">
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
              </div>
              <span class="review-count">(48 Yorum)</span>
            </div>
            <p class="review-platform">Google Değerlendirmeleri</p>
          </div>

          <button class="btn-review" id="btnLeaveReview" title="Google Değerlendirmesi Bırak">
            <span id="reviewBtnText">Yorum Yap</span>
          </button>
        </section>

        <!-- İLETİŞİM KANALLARI (CONTACT CHANNELS) -->
        <section class="section-block">
          <div class="section-header">
            <h2 class="section-title">İletişim Kanalları</h2>
            <span class="status-badge active"><span class="pulse-dot"></span>Aktif</span>
          </div>

          <div class="contact-list">
            <!-- Telefon -->
            <div class="contact-item">
              <div class="contact-icon-circle">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
              </div>
              <div class="contact-details">
                <span class="contact-label">Telefon</span>
                <a href="tel:+905362556424" class="contact-value font-mono" id="displayPhone">+90 536 255 64 24</a>
              </div>
              <div class="contact-actions">
                <a href="tel:+905362556424" class="contact-action-btn" id="linkCallAction" title="Hemen Ara">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#334155" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                  </svg>
                </a>
                <a href="sms:+905362556424" class="contact-action-btn" id="linkSmsAction" title="SMS Gönder">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#334155" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                  </svg>
                </a>
              </div>
            </div>

            <!-- E-Posta -->
            <div class="contact-item">
              <div class="contact-icon-circle">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="4"></circle>
                  <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>
                </svg>
              </div>
              <div class="contact-details">
                <span class="contact-label">E-Posta</span>
                <a href="mailto:muhiddinoktem@vedubox.com" class="contact-value" id="displayEmail">muhiddinoktem@vedubox.com</a>
              </div>
              <div class="contact-actions">
                <a href="mailto:muhiddinoktem@vedubox.com" class="contact-action-btn" id="linkEmailAction" title="E-Posta Gönder">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#334155" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="7" y1="17" x2="17" y2="7"></line>
                    <polyline points="7 7 17 7 17 17"></polyline>
                  </svg>
                </a>
              </div>
            </div>

            <!-- Web Sitesi -->
            <div class="contact-item">
              <div class="contact-icon-circle">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="2" y1="12" x2="22" y2="12"></line>
                  <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                </svg>
              </div>
              <div class="contact-details">
                <span class="contact-label">Web Sitesi</span>
                <a href="https://vedubox.com" target="_blank" rel="noopener noreferrer" class="contact-value" id="displayWebsite">vedubox.com</a>
              </div>
              <div class="contact-actions">
                <a href="https://vedubox.com" target="_blank" rel="noopener noreferrer" class="contact-action-btn" id="linkWebsiteAction" title="Web Sitesini Ziyaret Et">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#334155" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="7" y1="17" x2="17" y2="7"></line>
                    <polyline points="7 7 17 7 17 17"></polyline>
                  </svg>
                </a>
              </div>
            </div>

            <!-- Ofis & Lokasyon -->
            <div class="contact-item" id="contactItemAddress">
              <div class="contact-icon-circle">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <div class="contact-details">
                <span class="contact-label">Ofis &amp; Lokasyon</span>
                <span class="contact-value" id="displayAddress"></span>
              </div>
              <div class="contact-actions">
                <button class="btn-review" id="btnLocationNav" title="Haritada Aç ve Paylaş" style="background:#fff;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="18" cy="5" r="3"></circle>
                    <circle cx="6" cy="12" r="3"></circle>
                    <circle cx="18" cy="19" r="3"></circle>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                  </svg>
                  <span>Paylaş</span>
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- MARKALARIMIZ (BRANDS VITRIN) -->
        <section class="section-block" id="productsSection">
          <div class="section-header">
            <h2 class="section-title">Markalarımız</h2>
            <span class="platform-count" id="cardProductsCountBadge">0 Marka</span>
          </div>

          <div class="products-grid" id="cardProductsGrid">
            <!-- Dinamik Olarak app.js Tarafından Doldurulur -->
          </div>
        </section>

        <!-- SOSYAL AĞLAR & İLETİŞİM (DİNAMİK PLATFORMLAR) -->
        <section class="section-block" id="cardSocialSection">
          <div class="section-header">
            <h2 class="section-title">Sosyal Ağlar &amp; İletişim</h2>
            <span class="platform-count" id="cardSocialCountBadge">0 Platform</span>
          </div>

          <div class="social-grid compact-grid" id="cardSocialGrid">
            <!-- Dinamik Olarak app.js Tarafından Doldurulur -->
          </div>
        </section>

        <!-- Bottom Spacer -->
        <div class="bottom-spacer"></div>

      </div>

      <!-- =========================================================
           SAYFA 2: PROFİL BİLGİLERİNİ DÜZENLEME SAYFASI
           ========================================================= -->
      <div class="page-view" id="pageProfile">
        <div class="subpage-header">
          <h2 class="subpage-title">Profil Bilgilerini Düzenle</h2>
          <p class="subpage-desc">MonaCard kartvizitinizde görünen tüm iletişim ve profil detaylarını güncelleyin.</p>
        </div>

        <form id="profileEditForm" class="styled-form">
          <div class="form-row">
            <div class="form-group">
              <label for="editFullName">Adınız Soyadınız *</label>
              <input type="text" id="editFullName" required>
            </div>
            <div class="form-group">
              <label for="editCompany">Şirket *</label>
              <input type="text" id="editCompany" required>
            </div>
          </div>
          
          <div class="form-group">
            <label for="editTitle">Ünvan / Pozisyon *</label>
            <input type="text" id="editTitle" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editPhone">Telefon Numarası *</label>
              <input type="tel" id="editPhone" required>
            </div>
            <div class="form-group">
              <label for="editEmail">E-Posta Adresi *</label>
              <input type="email" id="editEmail" required>
            </div>
          </div>

          <div class="form-group">
            <label for="editWebsite">Web Sitesi</label>
            <input type="text" id="editWebsite">
          </div>

          <div class="form-group">
            <label for="editAddress">Ofis &amp; Lokasyon Adresi</label>
            <textarea id="editAddress" rows="2"></textarea>
          </div>

          <div class="form-divider">
            <span>Sosyal Medya Kullanıcı Adları &amp; Bağlantıları</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editWhatsapp">WhatsApp Numarası</label>
              <input type="text" id="editWhatsapp" placeholder="905362556424">
            </div>
            <div class="form-group">
              <label for="editTelegram">Telegram Kullanıcı Adı</label>
              <input type="text" id="editTelegram" placeholder="muhiddinoktem">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editLinkedin">LinkedIn Kullanıcı Adı</label>
              <input type="text" id="editLinkedin" placeholder="muhiddinoktem">
            </div>
            <div class="form-group">
              <label for="editTwitter">X (Twitter) Kullanıcı Adı</label>
              <input type="text" id="editTwitter" placeholder="muhiddinoktem">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editFacebook">Facebook Kullanıcı Adı</label>
              <input type="text" id="editFacebook" placeholder="muhiddinoktem">
            </div>
            <div class="form-group">
              <label for="editInstagram">Instagram Kullanıcı Adı</label>
              <input type="text" id="editInstagram" placeholder="muhiddinoktem">
            </div>
          </div>

          <div class="form-divider">
            <span>Markalarımız Bağlantıları</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editProdVedubox">Vedubox Web Bağlantısı</label>
              <input type="url" id="editProdVedubox" placeholder="https://vedubox.com">
            </div>
            <div class="form-group">
              <label for="editProdEtgigrup">Etgigrup Web Bağlantısı</label>
              <input type="url" id="editProdEtgigrup" placeholder="https://etgigrup.com">
            </div>
          </div>

          <div class="modal-btn-row mt-4 mb-4">
            <button type="button" class="btn-outline" id="btnCancelProfileEdit">İptal</button>
            <button type="submit" class="btn-primary">Değişiklikleri Kaydet</button>
          </div>
        </form>

        <div class="bottom-spacer"></div>
      </div>

      <!-- =========================================================
           SAYFA 3: TOPLANTI DÜZENLEME & YÖNETİM SAYFASI
           ========================================================= -->
      <div class="page-view" id="pageMeetings">
        <div class="subpage-header">
          <h2 class="subpage-title">Toplantı Yönetimi</h2>
          <p class="subpage-desc">Müşterilerinizle yeni toplantılar planlayın ve ajandanızı yönetin.</p>
        </div>

        <!-- Yeni Toplantı Ekleme Formu -->
        <div class="card-box mb-4">
          <h4 class="card-box-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
            Yeni Toplantı Düzenle
          </h4>
          <form id="newMeetingForm" class="styled-form">
            <div class="form-group">
              <label for="meetTitle">Toplantı Konusu *</label>
              <input type="text" id="meetTitle" placeholder="Örn: Vedubox Dijital Dönüşüm Sunumu" required>
            </div>
            <!-- Katılımcı / Müşteri (Çoklu Seçim) -->
            <div class="form-group">
              <label>Katılımcı / Müşteri (Çoklu Seçim) *</label>
              <div class="customer-multiselect-container" id="newMeetParticipantsContainer">
                <div class="multiselect-trigger-box" id="newMeetParticipantsToggle">
                  <span id="newMeetParticipantsPlaceholder" class="placeholder-text">Katılımcıları seçin (Çoklu seçim)...</span>
                  <span class="multiselect-count-badge hidden" id="newMeetSelectedCount">0</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="multiselect-dropdown-menu hidden" id="newMeetParticipantsDropdown">
                  <!-- Müşteri checkboxları dinamik olarak doldurulur -->
                </div>
                <div class="selected-participant-chips" id="newMeetSelectedChips"></div>
              </div>
            </div>

            <!-- Toplantı Ortamı (Katılımcının Altında) -->
            <div class="form-group">
              <label for="meetType">Toplantı Ortamı *</label>
              <select id="meetType">
                <option value="Google Meet">Google Meet (Online)</option>
                <option value="Zoom Meet">Zoom Meet (Online)</option>
                <option value="Microsoft Teams">Microsoft Teams (Online)</option>
                <option value="Bizim Ofis">Bizim Ofis</option>
                <option value="Müşteri Ofisi">Müşteri Ofisi</option>
              </select>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="meetDate">Tarih *</label>
                <input type="date" id="meetDate" required>
              </div>
              <div class="form-group">
                <label for="meetTime">Saat *</label>
                <input type="time" id="meetTime" value="14:00" required>
              </div>
            </div>
            <!-- Yüksekliği Rehbere Kaydet ile aynı (46px), "Toplantı Oluştur" butonu -->
            <button type="submit" class="btn-primary full-width btn-meeting-create">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
              <span>Toplantı Oluştur</span>
            </button>
          </form>
        </div>

        <!-- Yaklaşan Toplantılar Kartları (Yazılar sıkışmasın diye tarih ve toplantı şekli en altta) -->
        <div class="section-sub-header">
          <h4 class="font-bold text-dark">Yaklaşan Toplantılar</h4>
          <span class="badge-count" id="meetingCountBadge">2 Toplantı</span>
        </div>
        <div class="meetings-list" id="meetingsListContainer"></div>

        <div class="bottom-spacer"></div>
      </div>

      <!-- =========================================================
           SAYFA 4: TAKVİM & HATIRLATICI SAYFASI
           ========================================================= -->
      <div class="page-view" id="pageCalendar">
        <div class="subpage-header">
          <h2 class="subpage-title">Takvim &amp; Hatırlatıcılar</h2>
          <p class="subpage-desc">Müşteri takiplerinizi, etkinlikleri ve ajandanızı düzenleyin.</p>
        </div>

        <!-- Yeni Hatırlatıcı Ekle -->
        <div class="card-box mb-4">
          <h4 class="card-box-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            Yeni Etkinlik ya da Hatırlatıcı Düzenle
          </h4>
          <form id="newReminderForm" class="styled-form">
            <div class="form-group">
              <label for="remText">Hatırlatıcı / Görev Başlığı *</label>
              <input type="text" id="remText" placeholder="Örn: Kemal Bey'e teklif revizesini ilet" required>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="remDate">Tarih *</label>
                <input type="date" id="remDate" required>
              </div>
              <div class="form-group">
                <label for="remPriority">Öncelik Seviyesi</label>
                <select id="remPriority">
                  <option value="high">Yüksek (Acil)</option>
                  <option value="medium" selected>Normal</option>
                  <option value="low">Düşük</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn-primary full-width btn-reminder-save">Hatırlatıcıyı Kaydet</button>
          </form>
        </div>

        <!-- Aktif Hatırlatıcılar Listesi -->
        <div class="section-sub-header">
          <h4 class="font-bold text-dark">Ajanda &amp; Hatırlatıcı Listesi</h4>
          <span class="badge-count" id="reminderCountBadge">0 Hatırlatıcı</span>
        </div>
        <div class="reminders-list" id="remindersListContainer"></div>

        <div class="bottom-spacer"></div>
      </div>

      <!-- =========================================================
           SAYFA 5: CRM MÜŞTERİ LİSTESİ SAYFASI
           ========================================================= -->
      <div class="page-view" id="pageCrm">
        <div class="subpage-header">
          <div class="flex items-center justify-between">
            <h2 class="subpage-title">Müşteri İlişkileri (CRM)</h2>
            <span class="hubspot-sync-badge" title="HubSpot CRM Entegrasyonu Aktif">
              <span class="hubspot-dot"></span> HubSpot Sync
            </span>
          </div>
          <p class="subpage-desc">Müşteri havuzunuzu yönetin, durumlarına göre takip edin.</p>
        </div>

        <!-- CRM Filtre Sekmeleri: Sıcak, Ilık, Soğuk -->
        <div class="crm-tabs-row">
          <button class="crm-filter-tab active" data-filter="all">Tümü (<span id="countAll">0</span>)</button>
          <button class="crm-filter-tab hot" data-filter="hot">🔥 Sıcak (<span id="countHot">0</span>)</button>
          <button class="crm-filter-tab warm" data-filter="warm">⚡ Ilık (<span id="countWarm">0</span>)</button>
          <button class="crm-filter-tab cold" data-filter="cold">❄️ Soğuk (<span id="countCold">0</span>)</button>
        </div>

        <!-- Firma Filtresi Dropdown -->
        <div class="crm-company-filter-row">
          <div class="company-filter-wrap">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            <select id="crmCompanyFilter" class="crm-company-select" aria-label="Firmaya Göre Filtrele">
              <option value="all">Tüm Firmalar (Tümü)</option>
            </select>
          </div>
        </div>

        <!-- Arama ve Yeni Kişi Ekleme Barı (Aynı Yükseklikte) -->
        <div class="crm-search-bar-row">
          <div class="crm-search-box">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="crmSearchInput" placeholder="Müşteri veya şirket ara...">
          </div>
          <button class="btn-primary btn-add-customer" id="btnOpenNewCustomerForm">+ Yeni Müşteri</button>
        </div>

        <!-- Yeni Müşteri Hızlı Ekleme Akordiyonu -->
        <div class="new-customer-box hidden" id="newCustomerBox">
          <h4 class="card-box-title">Yeni Müşteri Ekle</h4>
          <form id="newCustomerDirectForm" class="styled-form">
            <div class="form-row">
              <div class="form-group">
                <label for="ncName">Ad Soyad *</label>
                <input type="text" id="ncName" required placeholder="Müşteri Adı">
              </div>
              <div class="form-group">
                <label for="ncCompany">Şirket *</label>
                <input type="text" id="ncCompany" required placeholder="Şirket Adı">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="ncPhone">Telefon *</label>
                <input type="tel" id="ncPhone" required placeholder="+90 5XX XXX XX XX">
              </div>
              <div class="form-group">
                <label for="ncEmail">E-Posta</label>
                <input type="email" id="ncEmail" placeholder="ornek@sirket.com">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="ncStage">Müşteri Durumu *</label>
                <select id="ncStage">
                  <option value="hot">🔥 Sıcak (Teklif / Anlaşmaya Yakın)</option>
                  <option value="warm" selected>⚡ Ilık (Teklif / İletişim Aşamasında)</option>
                  <option value="cold">❄️ Soğuk (İlk Temas / Beklemede)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="ncTitle">Pozisyon / Ünvan</label>
                <input type="text" id="ncTitle" placeholder="Genel Müdür, Satın Alma...">
              </div>
            </div>
            <div class="form-group">
              <label for="ncInitialNote">İlk Not</label>
              <input type="text" id="ncInitialNote" placeholder="Görüşülen konu veya detay...">
            </div>
            <div class="modal-btn-row">
              <button type="button" class="btn-outline" id="btnCancelNewCustomer">Vazgeç</button>
              <button type="submit" class="btn-primary">Kaydet</button>
            </div>
          </form>
        </div>

        <!-- Müşteri Listesi -->
        <div class="customer-cards-list" id="customerCardsContainer"></div>

        <div class="bottom-spacer"></div>
      </div>

      <!-- =========================================================
           SAYFA 6: MÜŞTERİ KARTVİZİT PROFİL SAYFASI
           ========================================================= -->
      <div class="page-view" id="pageCrmDetail">
        <div class="subpage-header">
          <div class="flex items-center justify-between">
            <h2 class="subpage-title" id="custProfName">Kemal Yılmaz</h2>
            <span class="customer-stage-pill" id="custProfStage">🔥 Sıcak Müşteri</span>
          </div>
          <p class="subpage-desc">Müşteri dijital kartviziti, sesli &amp; yazılı notlar ve HubSpot senkronizasyonu.</p>
        </div>

        <!-- Müşteri Dijital Kartvizit Görünümü -->
        <div class="customer-vcard-card">
          <div class="cust-card-top">
            <div class="cust-card-avatar" id="custProfInitials">KY</div>
            <div class="cust-card-info">
              <h4 id="custCardFullName">Kemal Yılmaz</h4>
              <p id="custCardCompany">TechPlus Bilişim A.Ş.</p>
              <span id="custCardTitle" class="cust-card-role">Genel Müdür</span>
            </div>
          </div>

          <!-- İletişim ve Durum Bilgileri (Telefon üstte, Mail ortada, Durum en altta) -->
          <div class="cust-card-contact-list">
            <!-- 1. Telefon Numarası -->
            <a href="tel:" class="cust-card-contact-row" id="custCallBtn" title="Hemen Ara">
              <div class="cust-contact-icon-badge icon-phone-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-2.2 2.2a15.053 15.053 0 0 1-6.59-6.59l2.2-2.21a.96.96 0 0 0 .25-1A11.36 11.36 0 0 1 8.5 3.99c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.61c0-.55-.45-1-.99-1z"/>
                </svg>
              </div>
              <span class="cust-contact-val" id="custPhoneLabel">+90 532 111 22 33</span>
              <span class="cust-contact-action">Ara ➔</span>
            </a>

            <!-- 2. E-Posta Adresi -->
            <a href="mailto:" class="cust-card-contact-row" id="custMailBtn" title="E-Posta Gönder">
              <div class="cust-contact-icon-badge icon-mail-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
              </div>
              <span class="cust-contact-val" id="custEmailLabel">kemal@techplus.com</span>
              <span class="cust-contact-action">E-Posta ➔</span>
            </a>

            <!-- 3. Durum (En Altta) -->
            <div class="cust-card-status-bottom-row">
              <label for="custStatusSelect" class="cust-status-bottom-label">
                <span class="cust-status-pulse-dot"></span>
                <span>Müşteri Durumu:</span>
              </label>
              <div class="cust-status-bottom-select-wrap">
                <select id="custStatusSelect" class="cust-status-bottom-select">
                  <option value="hot">🔥 Sıcak</option>
                  <option value="warm">⚡ Ilık</option>
                  <option value="cold">❄️ Soğuk</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- NOT DÜŞME BÖLÜMÜ (SESLİ & YAZILI) -->
        <div class="notes-hubspot-section">
          
          <div class="hubspot-sync-status-box">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#FF7A59">
                  <path d="M18.7 13.3c-.6 0-1.1.2-1.5.6l-2.6-1.5c.1-.4.1-.7.1-1.1 0-.4 0-.8-.1-1.1l2.5-1.5c.4.4.9.6 1.5.6 1.2 0 2.2-1 2.2-2.2S19.9 5 18.7 5s-2.2 1-2.2 2.2c0 .4.1.7.2 1.1L14.2 9.8c-.5-.4-1.2-.7-1.9-.7V5.9c.7-.2 1.2-.9 1.2-1.7 0-1-.8-1.8-1.8-1.8s-1.8.8-1.8 1.8c0 .8.5 1.5 1.2 1.7v3.2c-.7.1-1.4.4-1.9.8L6.6 8.4c.1-.4.2-.7.2-1.1 0-1.2-1-2.2-2.2-2.2S2.4 6.1 2.4 7.3s1 2.2 2.2 2.2c.6 0 1.1-.2 1.5-.6l2.6 1.5c-.1.4-.1.7-.1 1.1 0 .4 0 .8.1 1.1l-2.6 1.5c-.4-.4-.9-.6-1.5-.6-1.2 0-2.2 1-2.2 2.2s1 2.2 2.2 2.2 2.2-1 2.2-2.2c0-.4-.1-.7-.2-1.1l2.6-1.5c.5.4 1.2.7 1.9.7v3.2c-.7.2-1.2.9-1.2 1.7 0 1 .8 1.8 1.8 1.8s1.8-.8 1.8-1.8c0-.8-.5-1.5-1.2-1.7V15c.7-.1 1.4-.4 1.9-.8l2.6 1.5c-.1.4-.2.7-.2 1.1 0 1.2 1 2.2 2.2 2.2s2.2-1 2.2-2.2-1-2.2-2.2-2.2z"/>
                </svg>
                <span class="hubspot-title">HubSpot CRM Entegrasyonu</span>
              </div>
              <span class="hubspot-status-pill synced">● Otomatik Senkron</span>
            </div>
            <p class="hubspot-desc">Bu müşteriye düşeceğiniz sesli ve yazılı notlar anında HubSpot CRM müşteri kartına kaydedilir.</p>
          </div>

          <!-- Not Girişi (Sesli & Yazılı Seçici) -->
          <div class="note-input-container">
            <div class="note-tabs">
              <button class="note-tab-btn active" id="tabTextNote">✏️ Yazılı Not</button>
              <button class="note-tab-btn" id="tabVoiceNote">🎙️ Sesli Not</button>
            </div>

            <!-- Yazılı Not Alanı -->
            <div class="note-tab-panel active" id="panelTextNote">
              <textarea id="textNoteInput" class="note-textarea" rows="3" placeholder="Müşteri görüşmesi, teklif detayları veya hatırlatma yazın..."></textarea>
              <button class="btn-primary btn-sm full-width" id="btnSaveTextNote">
                <span>Notu Kaydet &amp; HubSpot'a İlet</span>
              </button>
            </div>

            <!-- Sesli Not Alanı -->
            <div class="note-tab-panel" id="panelVoiceNote">
              <div class="voice-recorder-box">
                <div class="voice-wave-visualizer" id="voiceWaveVisualizer">
                  <span class="wave-bar"></span>
                  <span class="wave-bar"></span>
                  <span class="wave-bar"></span>
                  <span class="wave-bar"></span>
                  <span class="wave-bar"></span>
                  <span class="wave-bar"></span>
                  <span class="wave-bar"></span>
                </div>
                <div class="voice-timer" id="voiceTimer">00:00</div>
                <p class="voice-status-text" id="voiceStatusText">Ses kaydı almak için mikrofon butonuna basın</p>
                
                <div class="voice-actions-row">
                  <button class="btn-mic" id="btnToggleRecord" title="Ses Kaydını Başlat/Durdur">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg>
                  </button>
                  <button class="btn-primary btn-sm hidden" id="btnSaveVoiceNote">
                    <span>Kaydı HubSpot'a Gönder</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Müşterinin Geçmiş Notları (HubSpot Timeline) -->
          <div class="notes-timeline-header">
            <h4>Görüşme &amp; Not Geçmişi</h4>
            <span class="text-xs text-muted" id="notesCountLabel">0 Not</span>
          </div>
          <div class="customer-notes-timeline" id="customerNotesTimeline"></div>

        </div>

        <div class="bottom-spacer"></div>
      </div>

    </div>

    <!-- FLOATING ACTION BUTTON (+ BUTONU: Tıklanınca Ekranın Ortasında Kart Okuma Modalı Açar) -->
    <div class="fab-wrapper staff-only" id="fabWrapper">
      <button class="fab-btn" id="mainFabBtn" aria-label="Müşteri Kartvizit Bilgilerini Oku" title="Müşteri Kartvizit Bilgisi Al">
        <svg class="fab-icon-plus" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
      </button>
    </div>

    <!-- BOTTOM NAVIGATION BAR (SADECE PERSONEL EKRANINDA GÖRÜNÜR) -->
    <nav class="bottom-nav-bar staff-only" id="bottomNav">
      <button class="nav-tab active" data-page="pageHome" id="tabAnasayfa" title="Kartvizit">
        <div class="nav-icon-wrap">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
        </div>
        <span class="nav-title">Anasayfa</span>
      </button>

      <button class="nav-tab" data-page="pageMeetings" id="tabToplanti" title="Toplantı Düzenle">
        <div class="nav-icon-wrap">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="23 7 16 12 23 17 23 7"></polygon>
            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
          </svg>
        </div>
        <span class="nav-title">Toplantı</span>
      </button>

      <button class="nav-tab" data-page="pageCalendar" id="tabTakvim" title="Etkinlik ve Hatırlatıcı">
        <div class="nav-icon-wrap">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
        </div>
        <span class="nav-title">Takvim</span>
      </button>

      <button class="nav-tab" data-page="pageCrm" id="tabCrm" title="Müşteri Yönetimi (CRM)">
        <div class="nav-icon-wrap">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="18" cy="5" r="3"></circle>
            <circle cx="6" cy="12" r="3"></circle>
            <circle cx="18" cy="19" r="3"></circle>
            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
          </svg>
        </div>
        <span class="nav-title">Crm</span>
      </button>
    </nav>

  </div>

  <!-- =========================================================
       FİRMA YÖNETİCİSİ (EXECUTIVE CRM & ADMIN) PANELİ
       Tasarım: SaaS CRM Dashboard UI/UX Görseli ile Birebir Uyumlu
       ========================================================= -->
  <div class="admin-panel-wrapper hidden" id="adminPanelWrapper">
    <!-- Mobile Sidebar Backdrop -->
    <div class="mobile-sidebar-backdrop" id="adminMobileBackdrop"></div>
    
    <!-- LEFT SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar">
      <div class="admin-sidebar-top">
        <div class="admin-brand-icon" id="adminBrandIcon" title="MonaCard Executive">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" rx="2"></rect>
            <rect x="14" y="3" width="7" height="7" rx="2"></rect>
            <rect x="14" y="14" width="7" height="7" rx="2"></rect>
            <rect x="3" y="14" width="7" height="7" rx="2"></rect>
          </svg>
        </div>
      </div>

      <!-- Navigation Menu -->
      <nav class="admin-nav-menu">
        <button class="admin-nav-item active" data-admin-view="viewAdminDashboard" id="navAdminDashboard" title="Genel Bakış (Dashboard)">
          <div class="admin-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          </div>
        </button>

        <button class="admin-nav-item" data-admin-view="viewAdminStaff" id="navAdminStaff" title="Personeller & Liderler">
          <div class="admin-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </div>
        </button>

        <button class="admin-nav-item" data-admin-view="viewAdminCrm" id="navAdminCrm" title="Müşteri Havuzu (CRM)">
          <div class="admin-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
          </div>
        </button>

        <button class="admin-nav-item" data-admin-view="viewAdminCalendar" id="navAdminCalendar" title="Toplantı & Takvim">
          <div class="admin-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
          </div>
        </button>

        <button class="admin-nav-item" data-admin-view="viewAdminIntegrations" id="navAdminIntegrations" title="Kurumsal Entegrasyonlar & API">
          <div class="admin-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
          </div>
        </button>

        <button class="admin-nav-item" data-admin-view="viewAdminSettings" id="navAdminSettings" title="Ayarlar & Arayüz Yönetimi">
          <div class="admin-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          </div>
        </button>

        <button class="admin-nav-item" data-admin-view="viewAdminProfile" id="navAdminProfile" title="Firma & Yönetici Profili">
          <div class="admin-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          </div>
        </button>
      </nav>

      <!-- Sidebar Bottom Actions -->
      <div class="admin-sidebar-bottom">
        <button class="admin-logout-btn" id="btnAdminSwitchStaff" title="Personel Ekranına Hızlı Geçiş">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </button>
      </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="admin-main-area">
      
      <!-- TOPBAR -->
      <header class="admin-topbar">
        <div class="admin-topbar-left">
          <button class="mobile-menu-btn" id="btnToggleMobileAdminSidebar" aria-label="Menüyü Aç" title="Menü">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
          </button>
          <div class="admin-search-wrap" id="adminSearchWrap">
            <svg class="admin-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" class="admin-search-input" id="adminGlobalSearch" placeholder="Personel, müşteri veya rapor ara..." autocomplete="off">
            <button type="button" class="admin-search-clear-btn hidden" id="adminSearchClearBtn" title="Temizle">&times;</button>
            <div class="global-search-results-dropdown hidden" id="adminSearchResultsDropdown"></div>
          </div>
        </div>

        <div class="admin-topbar-right">
          <!-- Language Switcher (TR / ENG) -->
          <div class="lang-switcher-pill" id="adminLangSwitcher">
            <button type="button" class="lang-btn active" data-lang="tr" title="Türkçe">TR</button>
            <button type="button" class="lang-btn" data-lang="en" title="English">ENG</button>
          </div>

          <!-- Theme Toggle -->
          <button class="admin-icon-btn" id="btnAdminThemeToggle" title="Karanlık / Aydınlık Mod">
            <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
            <svg class="moon-icon hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
          </button>

          <!-- Notification Bell & Dropdown Wrapper -->
          <div class="header-notification-wrapper" id="adminNotifWrapper">
            <button class="admin-icon-btn" id="btnAdminNotifications" title="Bildirimler">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
              <span class="notification-badge-dot" id="adminNotifBadgeDot"></span>
            </button>
            <div class="notifications-dropdown-menu hidden" id="adminNotificationsDropdown">
              <div class="notif-header">
                <div class="notif-title-wrap">
                  <h4 class="notif-title">Bildirimler</h4>
                  <span class="notif-badge" id="adminNotifUnreadBadge">3 Yeni</span>
                </div>
                <div class="notif-header-actions">
                  <button type="button" class="notif-action-btn" id="btnAdminMarkAllRead" title="Tümünü Okundu İşaretle">Tümünü Oku</button>
                  <button type="button" class="notif-action-btn" id="btnAdminClearNotifs" title="Bildirimleri Temizle" style="color:#94A3B8;">Temizle</button>
                </div>
              </div>
              <div class="notif-filter-tabs" id="adminNotifTabs">
                <button type="button" class="notif-tab-btn active" data-filter="all">Tümü</button>
                <button type="button" class="notif-tab-btn" data-filter="meeting">Toplantılar</button>
                <button type="button" class="notif-tab-btn" data-filter="lead">Müşteri / Lead</button>
                <button type="button" class="notif-tab-btn" data-filter="system">Sistem</button>
              </div>
              <div class="notif-list-container" id="adminNotifListContainer">
                <!-- Bildirimler dinamik render edilir -->
              </div>
              <div class="notif-footer">
                <span style="font-size:11.5px; color:#94A3B8;">MonaCard Akıllı Bildirim &amp; Aktivite Merkezi</span>
              </div>
            </div>
          </div>

          <!-- Admin Profile Pill -->
          <div class="admin-user-badge" id="adminUserBadgeTrigger">
            <div class="admin-user-avatar">
              <img src="avatar_clean.png" alt="Admin Avatar" id="adminHeaderAvatar">
            </div>
            <div class="admin-user-info">
              <span class="admin-user-name" id="adminHeaderName">Yönetici</span>
              <span class="admin-user-role" id="adminHeaderRole">Firma Yöneticisi</span>
            </div>
          </div>
        </div>
      </header>

      <!-- VIEWPORT (Pages) -->
      <div class="admin-viewport">
        
        <!-- =======================================================
             ADMIN SAYFA 1: GENEL BAKIŞ / DASHBOARD (Görseldeki Gibi)
             ======================================================= -->
        <section class="admin-view active" id="viewAdminDashboard">
          
          <!-- Greeting Header -->
          <div class="admin-greeting-header">
            <div class="greeting-text-wrap">
              <h1 class="greeting-title">Hoş geldin, <span id="adminGreetingName">Yönetici</span> ! <span class="wave-emoji">👋</span></h1>
              <p class="greeting-subtitle">İşte şirketinizin ve ekibinizin bugünkü performans özeti.</p>
            </div>
            <div class="greeting-badge-wrap" id="greetingBadgeWrap">
              <button type="button" class="dash-integration-btn" id="btnDashIntegrations" title="Kurumsal Entegrasyonları Yapılandır">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
                <span>Entegrasyonlar</span>
              </button>
            </div>
          </div>

          <!-- 4 STAT CARDS ROW (Görseldeki Sparkline Tasarımı) -->
          <div class="admin-stats-grid">
            
            <!-- Card 1: Sıcak Müşteriler (Toplam Ciro Yerine) -->
            <div class="admin-stat-card card-blue-glow">
              <div class="stat-card-header">
                <span class="stat-card-title">Sıcak Müşteriler</span>
                <div class="stat-icon-circle" style="background:#FEF2F2; color:#EF4444;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg>
                </div>
              </div>
              <div class="stat-card-body">
                <h3 class="stat-number" id="statHotCustomers">0</h3>
                <div class="stat-trend" id="statHotTrend">
                  <span id="statHotTrendPct">%0</span>
                  <span class="trend-label">vs geçen hafta</span>
                </div>
              </div>
              <div class="stat-sparkline-wrap">
                <svg class="sparkline-svg" viewBox="0 0 120 30" preserveAspectRatio="none">
                  <path d="M0,25 Q20,10 40,18 T80,8 T120,5" fill="none" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
              </div>
            </div>

            <!-- Card 2: Ilık Müşteriler (Satış Yerine) -->
            <div class="admin-stat-card">
              <div class="stat-card-header">
                <span class="stat-card-title">Ilık Müşteri</span>
                <div class="stat-icon-circle" style="background:#FEF3C7; color:#F59E0B;">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                </div>
              </div>
              <div class="stat-card-body">
                <h3 class="stat-number" id="statWarmCustomers">0</h3>
                <div class="stat-trend" id="statWarmTrend">
                  <span id="statWarmTrendPct">%0</span>
                  <span class="trend-label">vs geçen hafta</span>
                </div>
              </div>
              <div class="stat-sparkline-wrap">
                <svg class="sparkline-svg" viewBox="0 0 120 30" preserveAspectRatio="none">
                  <path d="M0,20 Q30,8 60,16 T120,6" fill="none" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
              </div>
            </div>

            <!-- Card 3: Yeni Kayıtlı Müşteriler -->
            <div class="admin-stat-card">
              <div class="stat-card-header">
                <span class="stat-card-title">Yeni Müşteriler</span>
                <div class="stat-icon-circle indigo">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                </div>
              </div>
              <div class="stat-card-body">
                <h3 class="stat-number" id="statNewLeads">0</h3>
                <div class="stat-trend" id="statNewLeadsTrend">
                  <span id="statNewLeadsTrendPct">%0</span>
                  <span class="trend-label">vs geçen hafta</span>
                </div>
              </div>
              <div class="stat-sparkline-wrap">
                <svg class="sparkline-svg" viewBox="0 0 120 30" preserveAspectRatio="none">
                  <path d="M0,22 Q30,5 60,18 T120,6" fill="none" stroke="#6366F1" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
              </div>
            </div>

            <!-- Card 4: Bu Ayki Toplantılar (Ort. Sepet / Satış Yerine) -->
            <div class="admin-stat-card">
              <div class="stat-card-header">
                <span class="stat-card-title">Bu Ayki Toplantılar</span>
                <div class="stat-icon-circle green">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
              </div>
              <div class="stat-card-body">
                <h3 class="stat-number" id="statMonthMeetings">0</h3>
                <div class="stat-trend" id="statMonthMeetingsTrend">
                  <span id="statMonthMeetingsTrendPct">%0</span>
                  <span class="trend-label">vs geçen ay</span>
                </div>
              </div>
              <div class="stat-sparkline-wrap">
                <svg class="sparkline-svg" viewBox="0 0 120 30" preserveAspectRatio="none">
                  <path d="M0,20 Q30,12 60,8 T120,4" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
              </div>
            </div>

          </div>

          <!-- CHARTS SECTION (Bar Chart + Donut Chart) -->
          <div class="admin-charts-grid">
            
            <!-- Left Card: En İyi Performans Gösteren 3 Personel (Top 3 Performers) -->
            <div class="admin-chart-card top-performers-widget">
              <div class="chart-card-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                <div style="flex: 1;">
                  <h3 class="chart-title">🏆 En İyi Performans Gösteren Personeller</h3>
                  <p class="chart-subtitle" style="font-size: 12px; color: var(--admin-text-sub, #64748B); margin-top: 2px;">En yüksek müşteri kazanımı ve sıcak görüşme performansına sahip ilk 3 ekip üyesi</p>
                </div>
                <button type="button" class="dash-card-add-btn" id="btnDashAddStaff" title="Yeni Personel Ekle" style="background: #00A86B !important; color: #FFFFFF !important; font-weight: 700 !important; font-size: 12.5px !important; padding: 6px 12px !important; padding-left: 12px !important; padding-right: 12px !important; border-radius: 10px !important; border: none !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 4px !important; box-shadow: 0 3px 10px rgba(0, 168, 107, 0.3) !important; flex: 0 0 auto !important; width: auto !important; max-width: max-content !important; height: auto !important; white-space: nowrap !important; margin-left: auto !important;">
                  <span>+ Personel Ekle</span>
                </button>
              </div>
              
              <!-- Dynamic Top 3 Staff List -->
              <div class="top-performers-list" id="topPerformersList">
                <!-- Dinamik olarak JS tarafından doldurulur -->
              </div>
            </div>

            <!-- Right Chart: Donut Chart (Müşteri Durum Dağılımı - Sıcak, Ilık, Soğuk %100) -->
            <div class="admin-chart-card">
              <div class="chart-card-header" style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                <h3 class="chart-title" style="flex: 1;">Müşteri Durum Dağılımı</h3>
                <button type="button" class="dash-card-add-btn" id="btnDashAddCustomer" title="Yeni Müşteri Ekle" style="background: #00A86B !important; color: #FFFFFF !important; font-weight: 700 !important; font-size: 12.5px !important; padding: 6px 12px !important; padding-left: 12px !important; padding-right: 12px !important; border-radius: 10px !important; border: none !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 4px !important; box-shadow: 0 3px 10px rgba(0, 168, 107, 0.3) !important; flex: 0 0 auto !important; width: auto !important; max-width: max-content !important; height: auto !important; white-space: nowrap !important; margin-left: auto !important;">
                  <span>+ Müşteri Ekle</span>
                </button>
              </div>
              
              <div class="donut-chart-flex">
                <div class="donut-svg-wrap">
                  <svg class="donut-svg" viewBox="0 0 160 160">
                    <circle cx="80" cy="80" r="58" fill="none" stroke="#F1F5F9" stroke-width="24"/>
                    <!-- Segments: Hot, Warm, Cold (%100 Toplam) -->
                    <circle cx="80" cy="80" r="58" fill="none" stroke="#EF4444" stroke-width="24" stroke-dasharray="160 365" stroke-dashoffset="0" class="donut-segment hot active" id="donutSegHot" data-type="hot"/>
                    <circle cx="80" cy="80" r="58" fill="none" stroke="#F59E0B" stroke-width="24" stroke-dasharray="125 365" stroke-dashoffset="-160" class="donut-segment warm" id="donutSegWarm" data-type="warm"/>
                    <circle cx="80" cy="80" r="58" fill="none" stroke="#3B82F6" stroke-width="24" stroke-dasharray="80 365" stroke-dashoffset="-285" class="donut-segment cold" id="donutSegCold" data-type="cold"/>
                  </svg>
                  <div class="donut-center-overlay" id="donutCenterOverlay">
                    <span class="donut-center-val" id="donutHotPercent">0%</span>
                    <span class="donut-center-sub" id="donutCenterSub">🔥 Sıcak</span>
                  </div>
                </div>

                <div class="donut-legend-col">
                  <div class="donut-legend-item active" id="donutLegendHot" data-type="hot" title="Sıcak Müşteri Verilerini Göster">
                    <span class="legend-bullet red"></span>
                    <span class="legend-name">🔥 Sıcak Müşteri</span>
                    <span class="legend-val font-mono" id="legendHotVal">0% (0)</span>
                  </div>
                  <div class="donut-legend-item" id="donutLegendWarm" data-type="warm" title="Ilık Müşteri Verilerini Göster">
                    <span class="legend-bullet yellow"></span>
                    <span class="legend-name">⚡ Ilık Müşteri</span>
                    <span class="legend-val font-mono" id="legendWarmVal">0% (0)</span>
                  </div>
                  <div class="donut-legend-item" id="donutLegendCold" data-type="cold" title="Soğuk Müşteri Verilerini Göster">
                    <span class="legend-bullet blue"></span>
                    <span class="legend-name">❄️ Soğuk Müşteri</span>
                    <span class="legend-val font-mono" id="legendColdVal">0% (0)</span>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- WEEKLY SCHEDULE / 7-DAY MEETING TIMELINE (Görsel 2'deki Peoplexio Takvim Tasarımı) -->
          <div class="admin-chart-card weekly-timeline-widget">
            
            <!-- Header with Month Navigation -->
            <div class="weekly-timeline-header">
              <button class="week-nav-btn" id="btnWeeklyPrevMonth" title="Önceki Ay">&lt; Eylül</button>
              <h3 class="weekly-timeline-title" id="weeklyTimelineTitle">Ekim 2026</h3>
              <button class="week-nav-btn" id="btnWeeklyNextMonth" title="Sonraki Ay">Kasım &gt;</button>
            </div>

            <!-- Scrollable Timeline Canvas & Days Row -->
            <div class="weekly-timeline-scroll-wrap">
              <!-- Days of Week Header Row (7 Günlük Dinamik Çizelge) -->
              <div class="weekly-days-row" id="weeklyDaysRow">
                <!-- Dinamik olarak JS tarafından doldurulur -->
              </div>

              <!-- Timeline Grid Canvas -->
              <div class="weekly-timeline-canvas">
                <!-- Left Time Labels Column (08:00 - 18:00) -->
                <div class="weekly-time-labels-col">
                  <div class="weekly-time-slot-label">08:00</div>
                  <div class="weekly-time-slot-label">09:00</div>
                  <div class="weekly-time-slot-label">10:00</div>
                  <div class="weekly-time-slot-label">11:00</div>
                  <div class="weekly-time-slot-label">12:00</div>
                  <div class="weekly-time-slot-label">13:00</div>
                  <div class="weekly-time-slot-label">14:00</div>
                  <div class="weekly-time-slot-label">15:00</div>
                  <div class="weekly-time-slot-label">16:00</div>
                  <div class="weekly-time-slot-label">17:00</div>
                  <div class="weekly-time-slot-label">18:00</div>
                </div>

                <!-- 7-Day Grid Columns & Meeting Blocks -->
                <div class="weekly-grid-body" id="weeklyTimelineGridBody">
                  
                  <!-- 7 Day Background Columns -->
                  <div class="weekly-grid-columns-bg" id="weeklyGridColumnsBg">
                    <div class="weekly-grid-col"></div>
                    <div class="weekly-grid-col"></div>
                    <div class="weekly-grid-col active"></div>
                    <div class="weekly-grid-col"></div>
                    <div class="weekly-grid-col"></div>
                    <div class="weekly-grid-col"></div>
                    <div class="weekly-grid-col"></div>
                  </div>



                  <!-- Dynamic Event Cards Layer -->
                  <div class="weekly-events-layer" id="weeklyEventsLayer">
                    <!-- Dinamik olarak JS tarafından doldurulur -->
                  </div>

                </div>

              </div>
            </div>

          </div>

        </section>

        <!-- =======================================================
             ADMIN SAYFA 2: PERSONELLER & LİDER YÖNETİMİ (Görseldeki 2. Ekran Gibi)
             ======================================================= -->
        <section class="admin-view" id="viewAdminStaff">
          
          <!-- Top Action Bar -->
          <div class="admin-view-header">
            <div>
              <h2 class="admin-view-title">Personel &amp; Lider Performans Yönetimi</h2>
              <p class="admin-view-subtitle">Personellerin müşteri kazanımı, sıcak/ılık/soğuk oranları, takım liderleri ve satış dönüşümleri.</p>
            </div>
            <button class="admin-btn-primary" id="btnAdminAddStaff">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              <span>Yeni Personel / Lider Ekle</span>
            </button>
          </div>

          <!-- 4 Status Filter Pills (Görseldeki Gibi) -->
          <div class="admin-status-pills-row">
            <div class="status-pill-box active" data-staff-filter="all">
              <div class="pill-title">Tüm Personeller</div>
              <div class="pill-number-row">
                <span class="pill-num" id="pillCountAllStaff">8</span>
                <span class="pill-sub">Aktif Ekip</span>
              </div>
            </div>
            <div class="status-pill-box leaders" data-staff-filter="leaders">
              <div class="pill-title">Takım Liderleri 👑</div>
              <div class="pill-number-row">
                <span class="pill-num" id="pillCountLeaders">3</span>
                <span class="pill-sub">Lider Kadro</span>
              </div>
            </div>
            <div class="status-pill-box top-performers" data-staff-filter="top">
              <div class="pill-title">Yüksek Performans 🚀</div>
              <div class="pill-number-row">
                <span class="pill-num" id="pillCountTopPerformers">4</span>
                <span class="pill-sub">Hedefi Aşan</span>
              </div>
            </div>
            <div class="status-pill-box new-staff" data-staff-filter="new">
              <div class="pill-title">Yeni Katılanlar ✨</div>
              <div class="pill-number-row">
                <span class="pill-num" id="pillCountNewStaff">2</span>
                <span class="pill-sub">Bu Ay Başlayan</span>
              </div>
            </div>
          </div>

          <!-- Search & Filter Toolbar -->
          <div class="admin-table-toolbar">
            <div class="admin-table-search">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              <input type="text" id="staffSearchInput" placeholder="Personel veya departman ara...">
            </div>

            <div class="admin-table-filters">
              <!-- Team / Leader Filter -->
              <div class="table-filter-item">
                <select id="staffLeaderFilter" class="admin-select-field">
                  <option value="all">Tüm Ekipler / Liderler</option>
                  <!-- Lider seçenekleri dinamik gelir -->
                </select>
              </div>

              <!-- Sort Filter -->
              <div class="table-filter-item">
                <select id="staffSortSelect" class="admin-select-field">
                  <option value="performance">Performansa Göre</option>
                  <option value="leads">Toplam Müşteriye Göre</option>
                  <option value="name">Ada Göre (A-Z)</option>
                </select>
              </div>
            </div>
          </div>

          <!-- STAFF DATA TABLE (Görseldeki Gibi Pürüzsüz SaaS Tablosu) -->
          <div class="admin-table-card">
            <div class="admin-table-responsive">
              <table class="admin-data-table" id="staffDataTable">
                <thead>
                  <tr>
                    <th>Personel</th>
                    <th>Bağlı Olduğu Lider</th>
                    <th>Kayıtlı Müşteri</th>
                    <th>Müşteri Dağılımı</th>
                    <th>Aylık Hedef</th>
                    <th class="text-center" style="text-align: center;">Performans</th>
                    <th class="text-center" style="text-align: center;">İşlemler</th>
                  </tr>
                </thead>
                <tbody id="staffTableBody">
                  <!-- Dinamik Personel Satırları -->
                </tbody>
              </table>
            </div>

            <!-- Table Pagination -->
            <div class="admin-table-pagination">
              <span class="pagination-info" id="staffPaginationInfo">1-8 / 8 Personel</span>
              <div class="pagination-buttons">
                <button class="pagination-btn disabled">&lt;</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn disabled">&gt;</button>
              </div>
            </div>
          </div>

        </section>

        <!-- =======================================================
             ADMIN SAYFA 2.1: PERSONEL PERFORMANS & PROFİL DETAY SAYFASI (Peoplexio UI)
             ======================================================= -->
        <section class="admin-view" id="viewAdminStaffDetail">
          
          <!-- Top Header with Back Navigation on its own row -->
          <div class="staff-detail-top-nav mb-3">
            <button class="admin-back-btn" id="btnBackToStaffList" title="Personel Listesine Geri Dön">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
              <span>Personellere Dön</span>
            </button>
          </div>

          <!-- Staff Header Info & Action Controls Row -->
          <div class="admin-view-header staff-detail-sub-header">
            <div>
              <h2 class="admin-view-title flex items-center gap-2">
                <span id="staffDetHeaderName">Ali Rıza Çelik</span>
                <span id="staffDetHeaderBadge" class="leader-badge">👑 Takım Lideri</span>
              </h2>
              <p class="admin-view-subtitle" id="staffDetHeaderTitle">Kurumsal Müşteri Direktörü | B2B Çözümler</p>
            </div>

            <div class="flex items-center" style="gap: 8px;">
              <!-- Time Filter -->
              <div class="admin-date-filter-wrap">
                <select class="admin-date-select" id="staffDetailTimeFilter">
                  <option value="week">Bu Hafta</option>
                  <option value="month" selected>Bu Ay (Eylül 2026)</option>
                  <option value="30days">Son 30 Gün</option>
                  <option value="3months">Son 3 Ay</option>
                  <option value="all">Tüm Zamanlar</option>
                </select>
              </div>

              <!-- Target Setting Button (8px gap) -->
              <button class="admin-btn-primary" id="btnEditStaffTargetModal" style="background:#4F46E5;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                <span>🎯 Hedef Belirle / Düzenle</span>
              </button>
            </div>
          </div>

          <!-- Quick Metric Stats Chips Header (Peoplexio Style - Satış Kaldırıldı) -->
          <div class="peoplexio-stat-chips-row">
            <div class="peoplexio-metric-chip">
              <span class="metric-chip-label">🗣️ Toplam Görüşme</span>
              <div class="metric-chip-val" id="detMetricMeetings">24 Görüşme</div>
              <span class="metric-chip-sub text-emerald-600">%100 Tamamlandı</span>
            </div>

            <div class="peoplexio-metric-chip">
              <span class="metric-chip-label">🔥 Sıcak Müşteri</span>
              <div class="metric-chip-val text-rose-600" id="detMetricHot">14 Müşteri</div>
              <span class="metric-chip-sub">Yüksek Anlaşma İhtimali</span>
            </div>

            <div class="peoplexio-metric-chip">
              <span class="metric-chip-label">⚡ Ilık Müşteri</span>
              <div class="metric-chip-val text-amber-600" id="detMetricWarm">8 Müşteri</div>
              <span class="metric-chip-sub">Teklif Aşamasında</span>
            </div>

            <div class="peoplexio-metric-chip">
              <span class="metric-chip-label">❄️ Soğuk Müşteri</span>
              <div class="metric-chip-val text-blue-600" id="detMetricCold">6 Müşteri</div>
              <span class="metric-chip-sub">Takip Bekleyen</span>
            </div>

            <div class="peoplexio-metric-chip highlight-card">
              <span class="metric-chip-label">🎯 Hedef Tamamlama</span>
              <div class="metric-chip-val" id="detMetricTargetPct">%60</div>
              <span class="metric-chip-sub" id="detMetricTargetSub">18 / 15 Görüşme Hedefi</span>
            </div>
          </div>

          <!-- Main 3-Column Peoplexio Dashboard Grid -->
          <div class="peoplexio-dashboard-grid">
            
            <!-- Col 1: Profile & Target Tracker Card (Peoplexio Ananya Sharma Purple Style) -->
            <div class="peoplexio-profile-col">
              
              <!-- Purple Hero Card -->
              <div class="peoplexio-hero-card">
                <div class="peoplexio-hero-top">
                  <div class="peoplexio-hero-avatar" id="staffDetAvatar">AR</div>
                  <div class="peoplexio-hero-badge" id="staffDetHeroSalaryBadge">
                    <span class="text-xs opacity-80">Toplam Görüşme</span>
                    <strong>24 Görüşme</strong>
                  </div>
                </div>
                <div class="peoplexio-hero-info">
                  <h3 class="peoplexio-hero-name" id="staffDetCardName">Ali Rıza Çelik</h3>
                  <p class="peoplexio-hero-role" id="staffDetCardRole">Kurumsal Müşteri Direktörü</p>
                </div>
              </div>

              <!-- Single Clean White Details Card (Unified info with Subordinates Button) -->
              <div class="peoplexio-single-details-card">
                <div class="single-detail-row">
                  <span class="single-detail-label">📞 İletişim &amp; Telefon</span>
                  <a href="#" id="staffDetPhoneLink" class="single-detail-value font-mono font-bold text-primary" style="text-decoration: none;"><span id="staffDetPhoneText">+90 532 987 65 43</span></a>
                  <button type="button" class="btn-subordinates" id="btnOpenSubordinatesModal">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Bağlı Personeller (<span id="staffDetSubCountBadge">0</span>)</span>
                  </button>
                </div>
                <div class="single-detail-divider"></div>
                <div class="single-detail-row">
                  <span class="single-detail-label">✉️ Kurumsal E-Posta</span>
                  <a href="#" id="staffDetMailLink" class="single-detail-value font-bold text-slate-700"><span id="staffDetEmailText">aliriza@vedubox.com</span></a>
                </div>
                <div class="single-detail-divider"></div>
                <div class="single-detail-row">
                  <span class="single-detail-label">👑 Liderlik &amp; Ekip</span>
                  <span class="single-detail-value font-bold text-slate-800" id="staffDetTeamText">Takım Lideri (3 Personel Bağlı)</span>
                </div>

                <!-- Status notice if card is cancelled -->
                <div id="staffDetCardStatusNotice" class="staff-status-notice hidden">
                  <span class="status-icon">⛔</span>
                  <div class="status-info">
                    <strong>Kart İptal Edildi</strong>
                    <span>Bu personel dijital kartvizitine ve panele erişemez.</span>
                  </div>
                </div>

                <div class="single-detail-divider"></div>

                <!-- 2 Action Buttons for Card Management (Madde 5) -->
                <div class="staff-card-management-actions">
                  <button type="button" class="btn-staff-action transfer" id="btnTransferStaffClients" title="Tüm Müşterileri ve Görüşmeleri Başka Personele Aktar">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 1l4 4-4 4"></path><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><path d="M7 23l-4-4 4-4"></path><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                    <span>Müşterileri Aktar</span>
                  </button>
                  <button type="button" class="btn-staff-action cancel" id="btnCancelStaffCard" title="Bu Personelin Kartvizitini İptal Et / Girişi Kapat">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                    <span id="btnCancelStaffCardText">Bu Kartı İptal Et</span>
                  </button>
                </div>
              </div>

            </div>

            <!-- Col 2: Görüşmeler, Toplantılar & Aktivite Trendi -->
            <div class="peoplexio-center-col">
              
              <!-- Aktivite & Dönüşüm Paneli (Image 2 Hiring Progress Style) -->
              <div class="admin-chart-card mb-4" style="padding: 22px;">
                <div class="chart-card-header" style="margin-bottom: 16px;">
                  <div>
                    <h4 class="chart-title">Görüşme &amp; Aktivite İlerlemesi</h4>
                    <div style="display: flex; align-items: baseline; gap: 8px; margin-top: 4px;">
                      <span class="text-2xl font-black text-slate-900" id="staffDetBigCount" style="font-size: 24px; font-weight: 800; color: #0F172A;">24</span>
                      <span class="text-xs font-bold text-emerald-600" style="font-size: 12px; font-weight: 700; color: #10B981;">↑ %20 geçen aya göre</span>
                    </div>
                  </div>
                  <span class="text-xs font-bold text-muted">↗</span>
                </div>
                <div class="peoplexio-activity-bars" id="staffDetActivityBars">
                  <div class="act-bar-col">
                    <div class="act-bar-fill" style="height: 55%;"><span class="act-tooltip">5</span></div>
                    <span class="act-label">P</span>
                  </div>
                  <div class="act-bar-col">
                    <div class="act-bar-fill" style="height: 80%;"><span class="act-tooltip">8</span></div>
                    <span class="act-label">S</span>
                  </div>
                  <div class="act-bar-col">
                    <div class="act-bar-fill" style="height: 65%;"><span class="act-tooltip">6</span></div>
                    <span class="act-label">Ç</span>
                  </div>
                  <div class="act-bar-col">
                    <div class="act-bar-fill active" style="height: 100%;">
                      <div class="peoplexio-peak-pill">24 Görüşme</div>
                      <span class="act-tooltip">11</span>
                    </div>
                    <span class="act-label">P</span>
                  </div>
                  <div class="act-bar-col">
                    <div class="act-bar-fill" style="height: 75%;"><span class="act-tooltip">7</span></div>
                    <span class="act-label">C</span>
                  </div>
                  <div class="act-bar-col">
                    <div class="act-bar-fill" style="height: 40%;"><span class="act-tooltip">4</span></div>
                    <span class="act-label">C</span>
                  </div>
                  <div class="act-bar-col">
                    <div class="act-bar-fill" style="height: 30%;"><span class="act-tooltip">2</span></div>
                    <span class="act-label">P</span>
                  </div>
                </div>
              </div>

              <!-- Ayarladığı Toplantılar & Randevular Listesi -->
              <div class="admin-table-card">
                <div class="chart-card-header" style="padding: 18px 20px 0 20px; margin-bottom: 12px;">
                  <h4 class="chart-title">📅 Ayarladığı Toplantılar &amp; Randevular</h4>
                  <span class="badge-count" id="staffDetMeetingCountBadge">3 Toplantı</span>
                </div>
                <div class="staff-meetings-list" id="staffDetMeetingsList" style="padding: 0 16px 16px 16px; display:flex; flex-direction:column; gap:10px;">
                  <!-- Dinamik Toplantılar -->
                </div>
              </div>

            </div>

            <!-- Col 3: Haftalık Aktivite & İşlem Logları (Dark Navy Peoplexio Card) -->
            <div class="peoplexio-right-col">
              
              <div class="peoplexio-dark-task-card">
                <div class="dark-log-header">
                  <span class="dark-log-title">📋 Haftalık İşlem Logları</span>
                  <span class="dark-log-badge">Son 7 Gün</span>
                </div>
                <div class="dark-log-list" id="staffDetLogsList">
                  <!-- Dinamik Haftalık Loglar -->
                </div>
              </div>

            </div>

          </div>

        </section>

        <!-- =======================================================
             ADMIN SAYFA 3: TÜM EKİP CRM & MÜŞTERİ HAVUZU
             ======================================================= -->
        <section class="admin-view" id="viewAdminCrm">
          <div class="admin-view-header">
            <div>
              <h2 class="admin-view-title">Müşteri Havuzu</h2>
              <p class="admin-view-subtitle">Tüm personellerin kaydettiği müşteriler, sesli/yazılı notlar ve anlık HubSpot takibi.</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="hubspot-sync-badge">
                <span class="hubspot-dot"></span> HubSpot Kurumsal Senkron
              </span>
            </div>
          </div>

          <!-- Filters Row -->
          <div class="admin-table-toolbar">
            <div class="admin-table-search">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              <input type="text" id="adminCrmSearchInput" placeholder="Müşteri, şirket veya telefon ara...">
            </div>

            <div class="admin-table-filters">
              <!-- Filter by Staff -->
              <div class="table-filter-item">
                <select id="adminCrmStaffFilter" class="admin-select-field">
                  <option value="all">Tüm Personeller</option>
                  <!-- Dinamik Personeller -->
                </select>
              </div>

              <!-- Filter by Stage -->
              <div class="table-filter-item">
                <select id="adminCrmStageFilter" class="admin-select-field">
                  <option value="all">Tüm Aşamalar (Sıcak/Ilık/Soğuk)</option>
                  <option value="hot">🔥 Sadece Sıcak Müşteriler</option>
                  <option value="warm">⚡ Sadece Ilık Müşteriler</option>
                  <option value="cold">❄️ Sadece Soğuk Müşteriler</option>
                </select>
              </div>

              <!-- Filter by Lead Type -->
              <div class="table-filter-item">
                <select id="adminCrmTypeFilter" class="admin-select-field">
                  <option value="all">Yeni &amp; Eski Müşteriler</option>
                  <option value="new">Sadece Yeni Müşteriler</option>
                  <option value="existing">Eski Portföy Müşterileri</option>
                </select>
              </div>
            </div>
          </div>

          <!-- CRM Customers Table -->
          <div class="admin-table-card">
            <div class="admin-table-responsive">
              <table class="admin-data-table" id="adminCrmTable">
                <thead>
                  <tr>
                    <th>Müşteri</th>
                    <th>Şirket &amp; Ünvan</th>
                    <th>İlgilenen Personel</th>
                    <th>Müşteri Durumu</th>
                    <th>Kayıt Türü</th>
                    <th>Görüşme Notları</th>
                    <th>Tarih</th>
                    <th class="text-right">Aksiyon</th>
                  </tr>
                </thead>
                <tbody id="adminCrmTableBody">
                  <!-- Dinamik CRM satırları -->
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- =======================================================
             ADMIN SAYFA 3.1: MÜŞTERİ KARTVİZİT & CRM DETAY SAYFASI (Detay Tıklanınca Açılır)
             ======================================================= -->
        <section class="admin-view" id="viewAdminCustomerDetail">
          
          <!-- Top Action Bar with Back button -->
          <div class="admin-view-header">
            <div class="flex items-center gap-3">
              <button class="admin-back-btn" id="btnBackToCrmList" title="Müşteri Havuzuna Geri Dön">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                <span>Müşteri Havuzuna Dön</span>
              </button>
            </div>

            <div class="flex items-center gap-2">
              <a href="#" id="crmDetCallBtn" class="admin-btn-primary admin-btn-action-no-line" style="background:#10B981; text-decoration:none !important; box-shadow:none !important;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <span>Hemen Ara</span>
              </a>
              <a href="#" id="crmDetMailBtn" class="admin-btn-primary admin-btn-action-no-line" style="background:#2563EB; text-decoration:none !important; box-shadow:none !important;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>E-Posta</span>
              </a>
            </div>
          </div>

          <!-- Customer Detail Content Grid -->
          <div class="crm-customer-detail-grid">
            
            <!-- Left: Customer Information & Assigned Staff -->
            <div class="crm-cust-info-card">
              <div class="profile-hero-wrap mb-3">
                <div class="profile-large-avatar" id="crmDetAvatar">KY</div>
              </div>
              <h3 class="profile-card-name" id="crmDetCardName">Kemal Yılmaz</h3>
              <p class="profile-card-role" id="crmDetCardRole">Kurumsal İletişim Direktörü</p>
              <div class="text-xs text-muted mb-4 flex items-center justify-center gap-1" id="crmDetCardCompany">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="9" y1="22" x2="9" y2="22.01"></line><line x1="15" y1="22" x2="15" y2="22.01"></line><line x1="9" y1="6" x2="9" y2="6.01"></line><line x1="15" y1="6" x2="15" y2="6.01"></line><line x1="9" y1="10" x2="9" y2="10.01"></line><line x1="15" y1="10" x2="15" y2="10.01"></line><line x1="9" y1="14" x2="9" y2="14.01"></line><line x1="15" y1="14" x2="15" y2="14.01"></line><line x1="9" y1="18" x2="9" y2="18.01"></line><line x1="15" y1="18" x2="15" y2="18.01"></line></svg>
                <span>TechPlus Bilişim A.Ş.</span>
              </div>

              <div class="customer-fields-list">
                <div class="cust-field-item">
                  <span class="cust-field-label">Telefon:</span>
                  <a href="#" id="crmDetPhoneVal" class="cust-field-value font-mono font-bold text-primary">+90 532 111 22 33</a>
                </div>
                <div class="cust-field-item">
                  <span class="cust-field-label">E-Posta:</span>
                  <a href="#" id="crmDetEmailVal" class="cust-field-value font-bold">kemal@techplus.com.tr</a>
                </div>
                <div class="cust-field-item">
                  <span class="cust-field-label">İlgilenen Personel:</span>
                  <div class="cust-field-value flex items-center gap-2" id="crmDetAssignedStaffVal">
                    <span class="font-bold text-slate-800">Muhiddin Öktem</span>
                  </div>
                </div>
                <div class="cust-field-item">
                  <span class="cust-field-label">Müşteri Durumu:</span>
                  <div class="cust-field-value" id="crmDetStageVal">
                    <span id="crmDetStageBadge" class="stage-badge-sm hot font-bold">Sıcak Müşteri</span>
                  </div>
                </div>
                <div class="cust-field-item">
                  <span class="cust-field-label">HubSpot Senkron:</span>
                  <span class="badge-count" style="background:#ECFDF5; color:#10B981;">✅ Anlık Eşleşti</span>
                </div>
              </div>
            </div>

            <!-- Right: Interaction Notes, Voice Recordings & Meetings with this customer -->
            <div class="crm-cust-timeline-col">
              
              <!-- Planned Meetings with this Customer -->
              <div class="admin-table-card mb-4" style="padding: 20px;">
                <div class="chart-card-header" style="margin-bottom: 14px;">
                  <h4 class="chart-title flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Bu Müşteriyle Planlanan / Yapılan Toplantılar</span>
                  </h4>
                  <button class="admin-btn-primary" id="btnCrmAddMeetForCust" style="height:32px; font-size:12px; padding:0 12px;">+ Toplantı Planla</button>
                </div>
                <div id="crmDetMeetingsList" style="display:flex; flex-direction:column; gap:10px;">
                  <!-- Dinamik Toplantılar -->
                </div>
              </div>

              <!-- Notes Timeline -->
              <div class="admin-table-card" style="padding: 20px;">
                <div class="chart-card-header" style="margin-bottom: 14px;">
                  <h4 class="chart-title flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                    <span>Görüşme &amp; Sesli / Yazılı Notlar</span>
                  </h4>
                  <span class="text-xs text-muted" id="crmDetNotesCount">2 Not</span>
                </div>
                <div id="crmDetNotesList" style="display:flex; flex-direction:column; gap:12px;">
                  <!-- Dinamik Notlar -->
                </div>
              </div>

            </div>

          </div>

        </section>

        <!-- =======================================================
             ADMIN SAYFA 4: TAKVİM & TOPLANTILAR (Google Calendar Görünümü + Ekip/VIP)
             ======================================================= -->
        <section class="admin-view" id="viewAdminCalendar">
          
          <!-- Top Action Bar -->
          <div class="admin-view-header">
            <div>
              <h2 class="admin-view-title">Toplantı &amp; Takvim Yönetimi</h2>
              <p class="admin-view-subtitle">Aylık Google Calendar görünümüyle tüm toplantıları denetleyin veya VIP ajanda planlayın.</p>
            </div>
            <button class="admin-btn-primary" id="btnAdminCreateMeeting">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
              <span>Yeni Toplantı / Görev Planla</span>
            </button>
          </div>

          <!-- Yanyana Kontroller: Filtre Solda, Tablar Sağa Yaslanmış (Görsel 5 Gibi) -->
          <div class="admin-cal-controls-row">
            
            <!-- Sol Taraf: Personel Filtresi & Görünüm Tipi (Takvim / Liste) -->
            <div class="cal-controls-left" id="calStaffFilterWrap">
              <div class="flex items-center gap-2">
                <label class="font-bold text-xs text-slate-600">Personele Göre Filtrele:</label>
                <select id="adminCalStaffSelect" class="admin-select-field" style="height: 38px; font-size: 12.5px;">
                  <option value="all">Tüm Personellerin Randevuları</option>
                  <!-- Dinamik Personel Listesi -->
                </select>
              </div>

              <div class="cal-view-switch-btns">
                <button class="cal-view-btn active" id="btnCalViewMonth" title="Aylık Takvim Görünümü">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                  <span>Aylık Takvim</span>
                </button>
                <button class="cal-view-btn" id="btnCalViewCards" title="Kart / Liste Görünümü">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                  <span>Kart Görünümü</span>
                </button>
              </div>
            </div>

            <!-- Sağ Taraf: Segmented Tab Switch (Tüm Ekip vs Yöneticiye Özel) -->
            <div class="admin-calendar-tabs">
              <button class="admin-cal-tab active" id="tabAdminAllMeetings" data-cal-view="all">
                <span>👥 Tüm Ekip Toplantıları &amp; Randevuları</span>
              </button>
              <button class="admin-cal-tab" id="tabAdminPersonalMeetings" data-cal-view="personal">
                <span>⭐ Yöneticiye Özel Ajanda &amp; VIP Toplantılar</span>
              </button>
            </div>

          </div>

          <!-- Google Calendar Style Monthly Interactive Grid (Varsayılan Görünüm) -->
          <div class="google-calendar-container" id="adminMonthlyCalendarView">
            <div class="gcal-header">
              <div class="gcal-nav-left">
                <h3 class="gcal-month-title" id="gcalMonthTitle">Eylül 2026</h3>
                <div class="gcal-nav-arrows">
                  <button class="gcal-nav-btn" id="btnGcalPrevMonth" title="Önceki Ay">&lt;</button>
                  <button class="gcal-today-btn" id="btnGcalToday">Bugün</button>
                  <button class="gcal-nav-btn" id="btnGcalNextMonth" title="Sonraki Ay">&gt;</button>
                </div>
              </div>
              <div class="gcal-legend">
                <span class="gcal-legend-dot meet"></span><span>Google Meet</span>
                <span class="gcal-legend-dot zoom"></span><span>Zoom Meet</span>
                <span class="gcal-legend-dot teams"></span><span>MS Teams</span>
                <span class="gcal-legend-dot inperson"></span><span>Yüz Yüze</span>
              </div>
            </div>

            <!-- Weekdays Row -->
            <div class="gcal-weekdays-row">
              <div class="gcal-weekday">Pzt</div>
              <div class="gcal-weekday">Sal</div>
              <div class="gcal-weekday">Çar</div>
              <div class="gcal-weekday">Per</div>
              <div class="gcal-weekday">Cum</div>
              <div class="gcal-weekday weekend">Cmt</div>
              <div class="gcal-weekday weekend">Paz</div>
            </div>

            <!-- 35/42 Days Grid Cells -->
            <div class="gcal-days-grid" id="gcalDaysGrid">
              <!-- Dinamik Gün Hücreleri ve İçindeki Toplantı Etiketleri -->
            </div>
          </div>

          <!-- Meetings Grid / List (İkincil Kart Görünümü) -->
          <div class="admin-meetings-grid hidden" id="adminMeetingsGrid">
            <!-- Dinamik Toplantı Kartları -->
          </div>

        </section>

        <!-- =======================================================
             ADMIN SAYFA 5: AYARLAR & PERSONEL ARAYÜZÜ ÖZELLEŞTİRME
             ======================================================= -->
        <section class="admin-view" id="viewAdminSettings">
          <div class="admin-view-header">
            <div>
              <h2 class="admin-view-title">Sistem &amp; Marka Ayarları</h2>
              <p class="admin-view-subtitle">Firma kurumsal logonuzu/renginizi seçin, açık/koyu temayı belirleyin ve personel arayüzünde nelerin görüneceğini kontrol edin.</p>
            </div>
            <button class="btn-primary btn-sm admin-btn-save-settings" id="btnSaveCompanySettings" title="Değişiklikleri Kaydet">
              <span>Kaydet</span>
            </button>
          </div>

          <div class="admin-settings-layout">
            
            <!-- SECTION 1: KURUMSAL RENK & MARKA KİMLİĞİ (Logo Rengi Seçimi) -->
            <div class="admin-settings-card">
              <div class="settings-card-header">
                <div class="settings-header-icon bg-blue-subtle">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                </div>
                <div>
                  <h3 class="settings-card-title">Kurumsal Marka Rengi &amp; Tema</h3>
                  <p class="settings-card-desc">Seçtiğiniz renk sistemdeki tüm butonlarda, ikonlarda, rozetlerde ve personel dijital kartvizitlerinde anında aktif olur.</p>
                </div>
              </div>

              <!-- Color Picker + Palettes -->
              <div class="color-picker-section">
                <label class="settings-label">Ana Kurumsal Renk Seçimi:</label>
                <div class="color-picker-row">
                  <div class="color-input-badge">
                    <input type="color" id="companyColorPicker" value="#00A86B" class="native-color-picker" title="Renk Paletini Aç">
                    <input type="text" id="colorHexInput" class="color-hex-input" value="#00A86B" maxlength="7" placeholder="#00A86B" spellcheck="false" title="Hex Renk Kodunu Girin (Örn: #00A86B)">
                  </div>

                  <!-- Preset Luxury Themes -->
                  <div class="preset-colors-group">
                    <button type="button" class="preset-color-dot active" data-color="#00A86B" style="background:#00A86B;" title="Zümrüt Yeşili (Vedubox Emerald)"></button>
                    <button type="button" class="preset-color-dot" data-color="#2563EB" style="background:#2563EB;" title="Safir Mavisi (Corporate Blue)"></button>
                    <button type="button" class="preset-color-dot" data-color="#6366F1" style="background:#6366F1;" title="İndigo Moru"></button>
                    <button type="button" class="preset-color-dot" data-color="#7C3AED" style="background:#7C3AED;" title="Kraliyet Moru"></button>
                    <button type="button" class="preset-color-dot" data-color="#EA580C" style="background:#EA580C;" title="Enerjik Turuncu"></button>
                    <button type="button" class="preset-color-dot" data-color="#E11D48" style="background:#E11D48;" title="Gül Kırmızı"></button>
                    <button type="button" class="preset-color-dot" data-color="#0F172A" style="background:#0F172A;" title="Gece Siyahı"></button>
                  </div>
                </div>

                <!-- Live Button / Icon Preview with Generous Spacing -->
                <div class="live-theme-preview-box mt-4">
                  <span class="preview-label">Canlı Önizleme:</span>
                  <div class="live-theme-preview-items">
                    <button type="button" class="btn-primary preview-sample-btn" style="pointer-events:none;">Örnek Buton</button>
                    <div class="preview-right-items">
                      <span class="status-badge active preview-status-badge" style="pointer-events:none;"><span class="pulse-dot"></span>Aktif İkon</span>
                      <button type="button" class="btn-icon preview-check-btn" style="pointer-events:none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Dark / Light Mode Selector -->
              <div class="mt-4 pt-3 border-t">
                <label class="settings-label" id="settingsThemeModeLabel">Varsayılan Görünüm Modu:</label>
                <div class="theme-mode-radios">
                  <label class="theme-radio-card">
                    <input type="radio" name="adminThemeMode" value="light" checked id="radioThemeLight">
                    <div class="theme-radio-content">
                      <div class="theme-radio-icon">☀️</div>
                      <div class="theme-radio-info">
                        <strong id="lblThemeLightTitle">Aydınlık Mod (Light)</strong>
                        <span id="lblThemeLightDesc">Ferah ve temiz arayüz</span>
                      </div>
                    </div>
                  </label>
                  <label class="theme-radio-card">
                    <input type="radio" name="adminThemeMode" value="dark" id="radioThemeDark">
                    <div class="theme-radio-content">
                      <div class="theme-radio-icon">🌙</div>
                      <div class="theme-radio-info">
                        <strong id="lblThemeDarkTitle">Karanlık Mod (Dark)</strong>
                        <span id="lblThemeDarkDesc">Göz yormayan modern koyu tema</span>
                      </div>
                    </div>
                  </label>
                </div>
              </div>

              <!-- Interface Language Selector (Firma Yönetici Ayarlarından Dil Değiştirme) -->
              <div class="mt-4 pt-3 border-t">
                <label class="settings-label" id="settingsLanguageLabel">Sistem Arayüz Dili (Interface Language):</label>
                <div class="theme-mode-radios">
                  <label class="theme-radio-card">
                    <input type="radio" name="adminSettingsLanguage" value="tr" checked id="radioLangTr">
                    <div class="theme-radio-content">
                      <div class="theme-radio-icon">🇹🇷</div>
                      <div class="theme-radio-info">
                        <strong id="lblLangTrTitle">Türkçe (Turkish)</strong>
                        <span id="lblLangTrDesc">Tüm arayüz ve yönetim paneli Türkçe</span>
                      </div>
                    </div>
                  </label>
                  <label class="theme-radio-card">
                    <input type="radio" name="adminSettingsLanguage" value="en" id="radioLangEn">
                    <div class="theme-radio-content">
                      <div class="theme-radio-icon">🇬🇧</div>
                      <div class="theme-radio-info">
                        <strong id="lblLangEnTitle">English</strong>
                        <span id="lblLangEnDesc">Complete interface in English</span>
                      </div>
                    </div>
                  </label>
                </div>
              </div>

            </div>

            <!-- SECTION 2: PERSONEL ARAYÜZÜ MODÜL GÖRÜNÜRLÜK AYARLARI (Yetkiler) -->
            <div class="admin-settings-card">
              <div class="settings-card-header">
                <div class="settings-header-icon bg-purple-subtle">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
                <div>
                  <h3 class="settings-card-title">Personel Arayüzü Görünürlük Kontrolleri</h3>
                  <p class="settings-card-desc">Personellerin MonaCard dijital kartvizit ve çalışma ekranında hangi bölümlerin görüneceğini belirleyin.</p>
                </div>
              </div>

              <div class="settings-toggles-list">
                
                <!-- Toggle 1: HubSpot CRM -->
                <div class="settings-toggle-row">
                  <div class="toggle-info">
                    <strong>HubSpot CRM Entegrasyonu &amp; Senkron Rozeti</strong>
                    <p>Personel panelinde otomatik HubSpot senkronizasyonu bildirimlerini ve durumunu gösterir.</p>
                  </div>
                  <label class="switch">
                    <input type="checkbox" id="settingStaffHubspot" checked>
                    <span class="slider round"></span>
                  </label>
                </div>

                <!-- Toggle 2: Sesli Not (Voice Note) -->
                <div class="settings-toggle-row">
                  <div class="toggle-info">
                    <strong>Sesli Not (Voice Note) Alma Özelliği</strong>
                    <p>Personelin müşteri kartlarına sesli görüşme notu kaydedebilmesini sağlar.</p>
                  </div>
                  <label class="switch">
                    <input type="checkbox" id="settingStaffVoiceNote" checked>
                    <span class="slider round"></span>
                  </label>
                </div>

                <!-- Toggle 3: Markalarımız Bölümü -->
                <div class="settings-toggle-row">
                  <div class="toggle-info">
                    <strong>Markalarımız Vitrini</strong>
                    <p>Kartvizit üzerinde firma marka ve ürün bağlantılarını görüntüler.</p>
                  </div>
                  <label class="switch">
                    <input type="checkbox" id="settingStaffProducts" checked>
                    <span class="slider round"></span>
                  </label>
                </div>

                <!-- Toggle 4: Sosyal Medya & İletişim -->
                <div class="settings-toggle-row">
                  <div class="toggle-info">
                    <strong>Sosyal Medya &amp; İletişim Kanalları</strong>
                    <p>WhatsApp, Telegram, LinkedIn vb. hızlı erişim butonlarını gösterir.</p>
                  </div>
                  <label class="switch">
                    <input type="checkbox" id="settingStaffSocial" checked>
                    <span class="slider round"></span>
                  </label>
                </div>

                <!-- Toggle 5: Google Değerlendirme & Yorumlar -->
                <div class="settings-toggle-row">
                  <div class="toggle-info">
                    <strong>Google Değerlendirme &amp; Yorum Rozeti</strong>
                    <p>Müşterilerin Google üzerinden 5 yıldızlı yorum bırakabilmesini sağlar.</p>
                  </div>
                  <label class="switch">
                    <input type="checkbox" id="settingStaffReviews" checked>
                    <span class="slider round"></span>
                  </label>
                </div>

                <!-- Toggle 6: Rehbere Kaydet (vCard İndir) -->
                <div class="settings-toggle-row">
                  <div class="toggle-info">
                    <strong>Rehbere Kaydet (vCard .VCF İndir) Butonu</strong>
                    <p>Müşterilerin personeli tek tıkla telefon rehberine eklemesine izin verir.</p>
                  </div>
                  <label class="switch">
                    <input type="checkbox" id="settingStaffVCard" checked>
                    <span class="slider round"></span>
                  </label>
                </div>

              </div>
            </div>

          </div>

        </section>

        <!-- =======================================================
             ADMIN SAYFA 5: KURUMSAL ENTEGRASYONLAR & API YÖNETİMİ
             ======================================================= -->
        <section class="admin-view" id="viewAdminIntegrations">
          
          <div class="admin-view-header">
            <div>
              <h2 class="admin-view-title">Kurumsal Entegrasyonlar &amp; API Yönetimi</h2>
              <p class="admin-view-subtitle">Google Workspace, Zoom Video Konferans, HubSpot CRM ve Salesforce Cloud bağlantılarınızı yapılandırın ve otomatik senkronizasyon kurallarını belirleyin.</p>
            </div>
            <button type="button" class="dash-integration-btn" style="pointer-events: none; opacity: 0.95;">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
              <span>Entegrasyon Merkezi</span>
            </button>
          </div>

          <div class="admin-settings-layout mb-4">
            
            <!-- SECTION: KURUMSAL ENTEGRASYONLAR (Google, Zoom, HubSpot, Salesforce) -->
            <div class="admin-settings-card full-width-card" id="integrationsSettingsCard">
              <div class="settings-card-header">
                <div class="settings-header-icon bg-blue-subtle">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
                </div>
                <div>
                  <h3 class="settings-card-title">Kurumsal Entegrasyonlar &amp; API Yönetimi</h3>
                  <p class="settings-card-desc">Google Workspace, Zoom Video Konferans, HubSpot CRM ve Salesforce Cloud bağlantılarınızı yapılandırın ve otomatik senkronizasyon kurallarını belirleyin.</p>
                </div>
              </div>

              <!-- Integration Tabs -->
              <div class="integration-tabs-nav">
                <button type="button" class="integration-tab-btn active" data-integ-tab="integGoogle">
                  <div class="integ-tab-icon google-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                  </div>
                  <span>Google Workspace</span>
                  <span class="integ-pill-badge active" id="badgeGoogleStatus">Bağlı</span>
                </button>

                <button type="button" class="integration-tab-btn" data-integ-tab="integZoom">
                  <div class="integ-tab-icon zoom-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#2D8CFF"><path d="M4 6.5C4 5.12 5.12 4 6.5 4h8C15.88 4 17 5.12 17 6.5v8c0 1.38-1.12 2.5-2.5 2.5h-8C5.12 17 4 15.88 4 14.5v-8zm14 2.21l4-2.85v9.28l-4-2.85V8.71z"/></svg>
                  </div>
                  <span>Zoom Meetings</span>
                  <span class="integ-pill-badge active" id="badgeZoomStatus">Aktif</span>
                </button>

                <button type="button" class="integration-tab-btn" data-integ-tab="integTeams">
                  <div class="integ-tab-icon teams-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#505AC9" d="M19.5 7.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm1.5 2h-3a1.5 1.5 0 0 0-1.5 1.5V15a1 1 0 0 0 1 1h3.5a1.5 1.5 0 0 0 1.5-1.5V11a1.5 1.5 0 0 0-1.5-1.5z"/><path fill="#7B83EB" d="M14.5 6a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm2 2.5h-4a2 2 0 0 0-2 2V18a1.5 1.5 0 0 0 1.5 1.5H16a2 2 0 0 0 2-2V10.5a2 2 0 0 0-1.5-2z"/><rect x="2" y="10" width="8" height="11" rx="2" fill="#4B53BC"/><path d="M4.5 13.5h3v1.5h-1v4h-1v-4h-1v-1.5z" fill="#FFFFFF"/></svg>
                  </div>
                  <span>Microsoft Teams</span>
                  <span class="integ-pill-badge active" id="badgeTeamsStatus">Aktif</span>
                </button>

                <button type="button" class="integration-tab-btn" data-integ-tab="integHubspot">
                  <div class="integ-tab-icon hubspot-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#FF7A59"><path d="M18.8 9.2V6.4c.7-.4 1.2-1.2 1.2-2.1 0-1.3-1.1-2.4-2.4-2.4s-2.4 1.1-2.4 2.4c0 .9.5 1.7 1.2 2.1v2.8c-1.2.5-2.2 1.4-2.8 2.6L8.5 8.3c.1-.3.1-.7.1-1 0-1.7-1.4-3.1-3.1S2.4 5.6 2.4 7.3s1.4 3.1 3.1 3.1c.4 0 .7-.1 1.1-.2l5.1 5.8c-1 1.4-1.2 3.3-.3 4.9 1 1.8 3.1 2.7 5.1 2.2 2-.5 3.4-2.3 3.4-4.4 0-1.7-.9-3.2-2.4-4v-3.7c1.3-.4 2.2-1.6 2.2-3 0-1.8-1.4-3.2-3.2-3.2z"/></svg>
                  </div>
                  <span>HubSpot CRM</span>
                  <span class="integ-pill-badge active" id="badgeHubspotStatus">Senkronize</span>
                </button>

                <button type="button" class="integration-tab-btn" data-integ-tab="integSalesforce">
                  <div class="integ-tab-icon salesforce-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#00A1E0"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3z"/></svg>
                  </div>
                  <span>Salesforce CRM</span>
                  <span class="integ-pill-badge active" id="badgeSalesforceStatus">Bağlı</span>
                </button>
              </div>

              <!-- Integration Panels -->
              <div class="integration-panels-wrapper">
                
                <!-- 1. GOOGLE INTEGRATION PANEL -->
                <div class="integration-panel active" id="integGoogle">
                  <div class="integ-status-card">
                    <div class="integ-status-left">
                      <div class="integ-service-avatar google-bg">G</div>
                      <div>
                        <div class="integ-status-title">
                          <strong>Google Workspace &amp; Takvim Bağlantısı</strong>
                          <span class="status-badge active"><span class="pulse-dot"></span>Bağlandı</span>
                        </div>
                        <p class="integ-status-meta">Aktif Hesap: <strong>muhiddinoktem@vedubox.com</strong> &bull; Son Senkronizasyon: 2 dakika önce</p>
                      </div>
                    </div>
                    <button type="button" class="integ-card-action-btn" id="btnReconnectGoogle" onclick="window.testIntegration('google')">🔄 Yeniden Yetkilendir</button>
                  </div>

                  <div class="styled-form mt-4">
                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Google Takvim 2 Yönlü Senkronizasyonu</strong>
                        <p>MonaCard ajandanıza eklenen toplantıları anında Google Takvim'e işler ve Google Takvim'deki randevuları ajandada gösterir.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integGoogleCalendarSync" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Otomatik Google Meet Linki Üretimi</strong>
                        <p>Toplantı ortamı "Google Meet" seçildiğinde davetlilere özel güvenli video konferans bağlantısı oluşturur.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integGoogleMeetAuto" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="form-row">
                      <div class="form-group">
                        <label for="integGoogleReviewUrl">Google İşletme / Değerlendirme URL'si</label>
                        <input type="url" id="integGoogleReviewUrl" value="https://g.page/r/vedubox/review" placeholder="https://g.page/r/.../review">
                        <small class="form-help-text">Dijital kartvizitteki "Yorum Bırak" butonunun yönleneceği Google Harita / Yorum linki.</small>
                      </div>
                      <div class="form-group">
                        <label for="integGoogleClientId">Google OAuth Client ID</label>
                        <input type="text" id="integGoogleClientId" value="782910482910-vedubox.apps.googleusercontent.com" placeholder="xxxxx.apps.googleusercontent.com">
                        <small class="form-help-text">Google Cloud Console kurumsal API yetkilendirme anahtarı.</small>
                      </div>
                    </div>

                    <div class="integ-action-bar">
                      <button type="button" class="admin-btn-primary btn-sm" onclick="window.testIntegration('google')">
                        <span>⚡ Google Bağlantısını Test Et &amp; Eşitle</span>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- 2. ZOOM INTEGRATION PANEL -->
                <div class="integration-panel" id="integZoom">
                  <div class="integ-status-card">
                    <div class="integ-status-left">
                      <div class="integ-service-avatar zoom-bg">Z</div>
                      <div>
                        <div class="integ-status-title">
                          <strong>Zoom Video Konferans API</strong>
                          <span class="status-badge active"><span class="pulse-dot"></span>Aktif</span>
                        </div>
                        <p class="integ-status-meta">Bağlı Plan: <strong>Zoom Business</strong> &bull; API Durumu: 200 OK</p>
                      </div>
                    </div>
                    <button type="button" class="integ-card-action-btn" id="btnTestZoom" onclick="window.testIntegration('zoom')">🔄 API Testi Yap</button>
                  </div>

                  <div class="styled-form mt-4">
                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Otomatik Zoom Meeting &amp; Parola Üretimi</strong>
                        <p>Toplantı ortamı "Zoom Meet" seçildiğinde tek tıkla dinamik Meeting ID, katılım URL'si ve şifre oluşturur.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integZoomAutoLink" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Güvenli Bekleme Odası (Waiting Room)</strong>
                        <p>Katılımcılar toplantı sahibinden önce odaya giremez; bekleme odasında yönetici onayı bekler.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integZoomWaitingRoom" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="form-row">
                      <div class="form-group">
                        <label for="integZoomAccountId">Zoom Account ID</label>
                        <input type="text" id="integZoomAccountId" value="zoom_acc_98412894" placeholder="Zoom Account ID">
                      </div>
                      <div class="form-group">
                        <label for="integZoomClientId">Zoom Server-to-Server Client ID</label>
                        <input type="text" id="integZoomClientId" value="zm_client_849201948" placeholder="Zoom Client ID">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="integZoomClientSecret">Zoom Client Secret</label>
                      <input type="password" id="integZoomClientSecret" value="zm_secret_k84920f92j1923" placeholder="Zoom Client Secret">
                    </div>

                    <div class="integ-action-bar">
                      <button type="button" class="admin-btn-primary btn-sm" onclick="window.testIntegration('zoom')">
                        <span>⚡ Zoom API Bağlantısını Doğrula</span>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- 3. MICROSOFT TEAMS INTEGRATION PANEL -->
                <div class="integration-panel" id="integTeams">
                  <div class="integ-status-card">
                    <div class="integ-status-left">
                      <div class="integ-service-avatar teams-bg">T</div>
                      <div>
                        <div class="integ-status-title">
                          <strong>Microsoft 365 &amp; Teams Video Konferans API</strong>
                          <span class="status-badge active"><span class="pulse-dot"></span>Aktif</span>
                        </div>
                        <p class="integ-status-meta">Bağlı Organizasyon: <strong>vedubox.onmicrosoft.com</strong> &bull; Graph API Durumu: 200 OK</p>
                      </div>
                    </div>
                    <button type="button" class="integ-card-action-btn" id="btnTestTeams" onclick="window.testIntegration('teams')">🔄 Bağlantıyı Test Et</button>
                  </div>

                  <div class="styled-form mt-4">
                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Otomatik Microsoft Teams Toplantı Linki Üretimi</strong>
                        <p>Toplantı ortamı "Microsoft Teams" seçildiğinde davetlilere özel güvenli video konferans bağlantısı (joinWebUrl) ve lobi erişim kodu oluşturur.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integTeamsAutoMeeting" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Outlook &amp; Microsoft 365 Takvim Senkronizasyonu</strong>
                        <p>MonaCard randevularını ve görüşme takvimini Microsoft 365 Outlook ajandanızla çift yönlü senkronize eder.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integTeamsCalendarSync" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Teams Kanalı Bildirim Webhook'u</strong>
                        <p>Yeni müşteri kartı oluşturulduğunda veya randevu alındığında kurumsal Teams kanalına anlık bildirim kartı gönderir.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integTeamsChannelNotify" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="form-row">
                      <div class="form-group">
                        <label for="integTeamsTenantId">Microsoft Azure AD Directory (Tenant) ID</label>
                        <input type="text" id="integTeamsTenantId" value="72f988bf-86f1-41af-91ab-2d7cd011db47" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
                        <small class="form-help-text">Azure Portal &gt; Entra ID Genel Bakış (Tenant ID) anahtarı.</small>
                      </div>
                      <div class="form-group">
                        <label for="integTeamsClientId">Microsoft Entra Application (Client) ID</label>
                        <input type="text" id="integTeamsClientId" value="9a8b7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
                        <small class="form-help-text">App Registration uygulamanızın İstemci (Client) Kimliği.</small>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group">
                        <label for="integTeamsClientSecret">Client Secret (İstemci Parolası)</label>
                        <input type="password" id="integTeamsClientSecret" value="ms_secret_984129841029384" placeholder="Client Secret Değeri">
                      </div>
                      <div class="form-group">
                        <label for="integTeamsWebhookUrl">Teams Kanalı Incoming Webhook URL</label>
                        <input type="url" id="integTeamsWebhookUrl" value="https://vedubox.webhook.office.com/webhookb2/teams-meeting-leads" placeholder="https://outlook.office.com/webhook/...">
                      </div>
                    </div>

                    <div class="integ-action-bar">
                      <button type="button" class="admin-btn-primary btn-sm" onclick="window.testIntegration('teams')">
                        <span>⚡ Microsoft Teams Bağlantısını Test Et &amp; Eşitle</span>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- 4. HUBSPOT CRM INTEGRATION PANEL -->
                <div class="integration-panel" id="integHubspot">
                  <div class="integ-status-card">
                    <div class="integ-status-left">
                      <div class="integ-service-avatar hubspot-bg">H</div>
                      <div>
                        <div class="integ-status-title">
                          <strong>HubSpot CRM 2 Yönlü Entegrasyon</strong>
                          <span class="status-badge active"><span class="pulse-dot"></span>2 Yönlü Aktif</span>
                        </div>
                        <p class="integ-status-meta">Portal ID: <strong>4829104</strong> &bull; Eşleşen Müşteri: <strong>148 Kişi</strong></p>
                      </div>
                    </div>
                    <button type="button" class="integ-card-action-btn" id="btnSyncHubspot" onclick="window.testIntegration('hubspot')">🔄 Şimdi Eşitle</button>
                  </div>

                  <div class="styled-form mt-4">
                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Yeni Kartvizitleri Otomatik HubSpot Contacts'a Ekle</strong>
                        <p>Kamera ile taranan veya personele eklenen her yeni kartvizit verisi anında HubSpot veritabanına aktarılır.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integHubspotAutoSyncContacts" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Sesli Görüşme Notlarını HubSpot Aktivitelerine İşle</strong>
                        <p>MonaCard müşteri kartına alınan sesli notlar ve transkriptler HubSpot CRM iletişim geçmişine senkronize edilir.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integHubspotAutoSyncVoice" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="form-row">
                      <div class="form-group">
                        <label for="integHubspotPortalId">HubSpot Portal (Hub) ID</label>
                        <input type="text" id="integHubspotPortalId" value="4829104" placeholder="Örn: 4829104">
                      </div>
                      <div class="form-group">
                        <label for="integHubspotStage">Varsayılan Lead (Aşama) Durumu</label>
                        <select id="integHubspotStage">
                          <option value="lead" selected>Lead (Potansiyel Müşteri)</option>
                          <option value="marketingqualifiedlead">Marketing Qualified Lead (MQL)</option>
                          <option value="salesqualifiedlead">Sales Qualified Lead (SQL)</option>
                          <option value="opportunity">Opportunity (Fırsat)</option>
                          <option value="customer">Customer (Kazanılmış Müşteri)</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="integHubspotApiKey">HubSpot Private App Access Token (API Anahtarı)</label>
                      <input type="password" id="integHubspotApiKey" value="demo_hubspot_token_placeholder" placeholder="pat-eu1-xxxx-xxxx">
                    </div>

                    <div class="integ-action-bar">
                      <button type="button" class="admin-btn-primary btn-sm" onclick="window.testIntegration('hubspot')">
                        <span>⚡ HubSpot Verilerini Şimdi Senkronize Et</span>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- 4. SALESFORCE CRM INTEGRATION PANEL -->
                <div class="integration-panel" id="integSalesforce">
                  <div class="integ-status-card">
                    <div class="integ-status-left">
                      <div class="integ-service-avatar salesforce-bg">SF</div>
                      <div>
                        <div class="integ-status-title">
                          <strong>Salesforce CRM Cloud Entegrasyonu</strong>
                          <span class="status-badge active"><span class="pulse-dot"></span>2 Yönlü Aktif</span>
                        </div>
                        <p class="integ-status-meta">Org ID: <strong>00D80000000abcde</strong> &bull; Eşleşen Lead / Contact: <strong>230 Kayıt</strong></p>
                      </div>
                    </div>
                    <button type="button" class="integ-card-action-btn" id="btnSyncSalesforce" onclick="window.testIntegration('salesforce')">🔄 Şimdi Eşitle</button>
                  </div>

                  <div class="styled-form mt-4">
                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Yeni Kartvizitleri Otomatik Salesforce Lead / Contact Olarak Ekle</strong>
                        <p>Kamera ile taranan veya personele kaydedilen her yeni kartvizit verisi anında Salesforce CRM Lead ve Contacts havuzuna aktarılır.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integSalesforceAutoSyncContacts" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Sesli Görüşme Notlarını Salesforce Activities / Tasks Olarak İşle</strong>
                        <p>Müşteri profiline düşülen sesli/yazılı görüşme notları ve yapay zeka özetleri Salesforce müşteri aktivite geçmişine senkronize edilir.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integSalesforceAutoSyncVoice" checked>
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="settings-toggle-row mb-3">
                      <div class="toggle-info">
                        <strong>Otomatik Salesforce Fırsat (Opportunity) Başlat</strong>
                        <p>Sıcak müşteri olarak etiketlenen veya toplantı talep eden kartvizitler için otomatik Opportunity kaydı oluşturur.</p>
                      </div>
                      <label class="switch">
                        <input type="checkbox" id="integSalesforceAutoOpp">
                        <span class="slider round"></span>
                      </label>
                    </div>

                    <div class="form-row">
                      <div class="form-group">
                        <label for="integSalesforceInstanceUrl">Salesforce Instance / Domain URL</label>
                        <input type="url" id="integSalesforceInstanceUrl" value="https://vedubox.my.salesforce.com" placeholder="https://yourcompany.my.salesforce.com">
                        <small class="form-help-text">Kurumsal Salesforce MyDomain giriş adresi.</small>
                      </div>
                      <div class="form-group">
                        <label for="integSalesforceLeadStatus">Varsayılan Lead Durumu</label>
                        <select id="integSalesforceLeadStatus">
                          <option value="Open - Not Contacted" selected>Open - Not Contacted</option>
                          <option value="Working - Contacted">Working - Contacted</option>
                          <option value="Closed - Converted">Closed - Converted</option>
                          <option value="Closed - Not Converted">Closed - Not Converted</option>
                        </select>
                        <small class="form-help-text">Yeni aktarılan kartvizitlerin başlangıç aşaması.</small>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group">
                        <label for="integSalesforceConsumerKey">Connected App Consumer Key (Client ID)</label>
                        <input type="text" id="integSalesforceConsumerKey" value="3MVG9lKc_I.3.4u8912384910283401923" placeholder="3MVG9xxxxxxx">
                      </div>
                      <div class="form-group">
                        <label for="integSalesforceConsumerSecret">Consumer Secret (Client Secret)</label>
                        <input type="password" id="integSalesforceConsumerSecret" value="48920194820194820194" placeholder="Client Secret">
                      </div>
                    </div>

                    <div class="integ-action-bar">
                      <button type="button" class="admin-btn-primary btn-sm" onclick="window.testIntegration('salesforce')">
                        <span>⚡ Salesforce API Bağlantısını Doğrula &amp; Senkronize Et</span>
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>

        </section>

        <!-- =======================================================
             ADMIN SAYFA 6: FİRMA & YÖNETİCİ PROFİLİ
             ======================================================= -->
        <section class="admin-view" id="viewAdminProfile">
          <div class="admin-view-header">
            <div>
              <h2 class="admin-view-title">Firma &amp; Yönetici Kimlik Bilgileri</h2>
              <p class="admin-view-subtitle">Kurumsal kimlik, şirket sosyal medya hesapları ve tüm personellere aktarılan ürün &amp; hizmet vitrini.</p>
            </div>
            <button type="button" class="admin-btn-primary" id="btnAdminSaveAllProfile">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
              <span>Tüm Bilgileri Kaydet &amp; Kartlara Aktar</span>
            </button>
          </div>

          <div class="admin-settings-layout mb-4">
            
            <!-- Kart 1: Firma & Yönetici Temel Bilgileri -->
            <div class="admin-settings-card">
              <div class="settings-card-header">
                <div class="settings-header-icon bg-blue-subtle">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div>
                  <h3 class="settings-card-title">Firma &amp; Yönetici Kimliği</h3>
                  <p class="settings-card-desc">Şirket resmi unvanı, yöneticisi ve merkez iletişim detayları.</p>
                </div>
              </div>

              <form id="adminCompanyProfileForm" class="styled-form">
                <div class="form-row">
                  <div class="form-group">
                    <label for="adminCompName">Firma / Şirket Adı *</label>
                    <input type="text" id="adminCompName" value="{{ $company->name ?? '' }}" placeholder="Firma Adı" required>
                  </div>
                  <div class="form-group">
                    <label for="adminCompSector">Sektör</label>
                    <input type="text" id="adminCompSector" value="{{ $company->sector ?? '' }}" placeholder="Örn: Bilişim &amp; Yazılım">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="adminCompWebsite">Kurumsal Web Sitesi</label>
                    <input type="url" id="adminCompWebsite" value="{{ $company->website ?? '' }}" placeholder="https://firmaniz.com">
                  </div>
                  <div class="form-group">
                    <label for="adminCompEmail">Yönetici E-Posta *</label>
                    <input type="email" id="adminCompEmail" value="{{ $currentUser->email ?? ($company->email ?? '') }}" placeholder="yonetici@firmaniz.com" required>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="adminManagerName">Yönetici Adı Soyadı *</label>
                    <input type="text" id="adminManagerName" value="{{ $currentUser->name ?? ($company->ceo_name ?? '') }}" placeholder="Yönetici Adı Soyadı" required>
                  </div>
                  <div class="form-group">
                    <label for="adminManagerTitle">Yönetici Pozisyonu / Ünvanı</label>
                    <input type="text" id="adminManagerTitle" value="{{ $currentUser->title ?? ($company->ceo_title ?? '') }}" placeholder="Örn: Kurucu &amp; Genel Müdür">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="adminCompPhone">Yönetici / 1. İletişim Telefonu</label>
                    <input type="tel" id="adminCompPhone" value="{{ $currentUser->phone ?? ($company->phone ?? '') }}" placeholder="+90 5XX XXX XX XX">
                  </div>
                  <div class="form-group">
                    <label for="adminCompPhone2">2. İletişim / Sabit Telefon</label>
                    <input type="tel" id="adminCompPhone2" value="{{ $company->phone2 ?? '' }}" placeholder="+90 212 XXX XX XX">
                  </div>
                </div>

                <div class="form-group">
                  <label for="adminCompAddress">Firma Merkez Adresi</label>
                  <textarea id="adminCompAddress" rows="2" placeholder="Firma açık adresi...">{{ $company->address ?? '' }}</textarea>
                </div>

                <div class="form-group">
                  <label for="adminCompAddress2">2. Adres / Şube Adresi</label>
                  <textarea id="adminCompAddress2" rows="2" placeholder="Şube veya 2. firma açık adresi...">{{ $company->address2 ?? '' }}</textarea>
                </div>
              </form>
            </div>

            <!-- Kart 2: Şirket Sosyal Medya & İletişim Kanalları -->
            <div class="admin-settings-card">
              <div class="settings-card-header">
                <div class="settings-header-icon bg-purple-subtle">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2.2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                </div>
                <div>
                  <h3 class="settings-card-title">Kurumsal Sosyal Medya Kanalları</h3>
                  <p class="settings-card-desc">Tüm personellerin ve yöneticinin dijital kartvizitlerine otomatik aktarılır.</p>
                </div>
              </div>

              <form id="adminSocialForm" class="styled-form">
                <div class="form-row">
                  <div class="form-group">
                    <label for="adminSocialWhatsapp">Kurumsal WhatsApp</label>
                    <input type="text" id="adminSocialWhatsapp" value="{{ $company->social_links['whatsapp'] ?? '' }}" placeholder="905xxxxxxxxx">
                  </div>
                  <div class="form-group">
                    <label for="adminSocialLinkedin">Kurumsal LinkedIn</label>
                    <input type="text" id="adminSocialLinkedin" value="{{ $company->social_links['linkedin'] ?? '' }}" placeholder="linkedin-profil-adi">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="adminSocialInstagram">Kurumsal Instagram</label>
                    <input type="text" id="adminSocialInstagram" value="{{ $company->social_links['instagram'] ?? '' }}" placeholder="instagram-profil-adi">
                  </div>
                  <div class="form-group">
                    <label for="adminSocialTwitter">Kurumsal X (Twitter)</label>
                    <input type="text" id="adminSocialTwitter" value="{{ $company->social_links['twitter'] ?? '' }}" placeholder="x-kullanici-adi">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="adminSocialTelegram">Kurumsal Telegram</label>
                    <input type="text" id="adminSocialTelegram" value="{{ $company->social_links['telegram'] ?? '' }}" placeholder="telegram-kullanici-adi">
                  </div>
                  <div class="form-group">
                    <label for="adminSocialFacebook">Kurumsal Facebook</label>
                    <input type="text" id="adminSocialFacebook" value="{{ $company->social_links['facebook'] ?? '' }}" placeholder="facebook-sayfa-adi">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="adminSocialYoutube">Kurumsal YouTube</label>
                    <input type="text" id="adminSocialYoutube" value="{{ $company->social_links['youtube'] ?? '' }}" placeholder="youtube-kanali">
                  </div>
                  <div class="form-group">
                    <label for="adminSocialTiktok">Kurumsal TikTok</label>
                    <input type="text" id="adminSocialTiktok" value="{{ $company->social_links['tiktok'] ?? '' }}" placeholder="tiktok-kullanici-adi">
                  </div>
                </div>
              </form>
            </div>

          </div>

          <!-- Kart 3: Markalarımız Vitrin Yönetimi -->
          <div class="admin-settings-card">
            <div class="settings-card-header" style="justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
              <div class="flex items-center gap-3">
                <div class="settings-header-icon" style="background:#ECFDF5; color:#10B981;">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                </div>
                <div>
                  <h3 class="settings-card-title">Markalarımız Vitrin Yönetimi</h3>
                  <p class="settings-card-desc">Yönetici ve personellerin tüm dijital kartvizitlerinde 'Markalarımız' alanında otomatik gösterilir.</p>
                </div>
              </div>

              <button type="button" class="admin-btn-primary" id="btnAdminAddProduct" style="font-size:12.5px; height:38px; padding:0 14px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Yeni Marka / Ürün Ekle</span>
              </button>
            </div>

            <!-- Products Grid List Container -->
            <div class="admin-products-grid mt-3" id="adminProductsListContainer">
              <!-- Dinamik Ürün Kartları -->
            </div>
          </div>
        </section>

      </div>
    </main>

  </div>

  <!-- =========================================================
       SÜPER ADMİN (SAAS SAHİBİ) EXECUTIVE YÖNETİM PANELİ
       Tasarım: Ultra-Modern SaaS Owner Multi-Tenant Control Hub
       ========================================================= -->
  <div class="superadmin-panel-wrapper hidden" id="superAdminPanelWrapper">
    <!-- Mobile Sidebar Backdrop -->
    <div class="mobile-sidebar-backdrop" id="superMobileBackdrop"></div>
    
    <!-- LEFT SIDEBAR -->
    <aside class="super-sidebar" id="superSidebar">
      <div class="super-sidebar-top">
        <div class="super-brand-icon" id="superBrandIcon" title="MonaCard SaaS Master Hub">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
          </svg>
        </div>
      </div>

      <!-- Super Navigation Menu -->
      <nav class="super-nav-menu">
        <button class="super-nav-item active" data-super-view="viewSuperDashboard" id="navSuperDashboard" title="Genel Bakış &amp; Finans">
          <div class="super-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          </div>
        </button>

        <button class="super-nav-item" data-super-view="viewSuperCompanies" id="navSuperCompanies" title="Firma Listesi &amp; Kotalar">
          <div class="super-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
          </div>
        </button>

        <button class="super-nav-item" data-super-view="viewSuperDemoRequests" id="navSuperDemoRequests" title="Demo Talepleri &amp; Onay Havuzu">
          <div class="super-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <span class="super-badge-dot" id="superDemoBadgeDot">5</span>
          </div>
        </button>

        <button class="super-nav-item" data-super-view="viewSuperPricing" id="navSuperPricing" title="Çalışan Sayısına Göre Fiyatlandırma &amp; Paketler">
          <div class="super-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 6v12"></path></svg>
          </div>
        </button>

        <button class="super-nav-item" data-super-view="viewSuperSettings" id="navSuperSettings" title="SaaS Sistem Ayarları">
          <div class="super-nav-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          </div>
        </button>
      </nav>

      <!-- Sidebar Bottom: Logout -->
      <div class="super-sidebar-bottom">
        <button class="super-logout-btn" id="btnSuperSwitchAdmin" title="Çıkış Yap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </button>
      </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="super-main-area">
      
      <!-- TOPBAR -->
      <header class="super-topbar">
        <div class="super-topbar-left">
          <button class="mobile-menu-btn" id="btnToggleMobileSuperSidebar" aria-label="Menüyü Aç" title="Menü">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
          </button>
          <div class="super-search-wrap" id="superSearchWrap">
            <svg class="super-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" class="super-search-input" id="superGlobalSearch" placeholder="Firma adı, yetkili, demo talebi veya paket ara..." autocomplete="off">
            <button type="button" class="super-search-clear-btn hidden" id="superSearchClearBtn" title="Temizle">&times;</button>
            <div class="global-search-results-dropdown hidden" id="superSearchResultsDropdown"></div>
          </div>
        </div>

        <div class="super-topbar-right">
          <!-- Currency / Metric Switcher -->
          <div class="super-period-badge" id="superPeriodBadge">
            <span class="super-dot-live"></span>
            <span>MRR / Canlı SaaS Akışı</span>
          </div>

          <!-- Language Switcher (TR / ENG) -->
          <div class="lang-switcher-pill" id="superLangSwitcher">
            <button type="button" class="lang-btn active" data-lang="tr" title="Türkçe">TR</button>
            <button type="button" class="lang-btn" data-lang="en" title="English">ENG</button>
          </div>

          <!-- Theme Toggle -->
          <button class="super-icon-btn" id="btnSuperThemeToggle" title="Karanlık / Aydınlık Mod">
            <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
            <svg class="moon-icon hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
          </button>

          <!-- Super Admin Notification Bell & Dropdown Wrapper -->
          <div class="header-notification-wrapper" id="superNotifWrapper">
            <button class="super-icon-btn" id="btnSuperNotifications" title="Demo Bildirimleri">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
              <span class="super-notification-badge-dot" id="superNotifBadgeDot"></span>
            </button>
            <div class="notifications-dropdown-menu hidden" id="superNotificationsDropdown">
              <div class="notif-header">
                <div class="notif-title-wrap">
                  <h4 class="notif-title">SaaS &amp; Demo Bildirimleri</h4>
                  <span class="notif-badge" id="superNotifUnreadBadge">2 Yeni</span>
                </div>
                <div class="notif-header-actions">
                  <button type="button" class="notif-action-btn" id="btnSuperMarkAllRead">Tümünü Oku</button>
                </div>
              </div>
              <div class="notif-list-container" id="superNotifListContainer">
                <!-- Bildirimler dinamik render edilir -->
              </div>
              <div class="notif-footer">
                <span style="font-size:11.5px; color:#94A3B8;">MonaCard Global SaaS Akışı</span>
              </div>
            </div>
          </div>

          <!-- Super Admin Profile Pill -->
          <div class="super-user-badge">
            <div class="super-user-avatar">
              <img src="avatar_clean.png" alt="Super Admin Avatar" id="superHeaderAvatar">
              <span class="super-crown-icon" title="SaaS Sahibi">👑</span>
            </div>
            <div class="super-user-info">
              <span class="super-user-name" id="superHeaderName">Muhiddin Öktem</span>
              <span class="super-user-role">Süper Admin</span>
            </div>
          </div>
        </div>
      </header>

      <!-- SUPER ADMIN VIEWPORT -->
      <div class="super-viewport">
        
        <!-- =======================================================
             SÜPER ADMİN GÖRÜNÜM 1: GENEL BAKIŞ & SAAS METRİKLERİ
             ======================================================= -->
        <section class="super-view active" id="viewSuperDashboard">
          
          <!-- Greeting Header -->
          <div class="super-greeting-header">
            <div class="greeting-text-wrap">
              <h1 class="greeting-title">Kontrol Merkezi <span class="wave-emoji">🚀</span></h1>
              <p class="greeting-subtitle">Tüm kayıtlı kurumsal firmaların, demo taleplerinin ve lisans gelirlerinin anlık özeti.</p>
            </div>
            <div class="super-quick-actions-row">
              <button class="super-btn-primary" id="btnDashNewCompany">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Yeni Firma Ekle</span>
              </button>
              <button class="super-btn-outline" id="btnDashViewDemos">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>Demo Taleplerini Gör (<span id="dashPendingDemoCount">5</span>)</span>
              </button>
            </div>
          </div>

          <!-- 4 STAT CARDS ROW -->
          <div class="super-stats-grid">
            
            <!-- Card 1: Toplam Aktif Firma -->
            <div class="super-stat-card card-indigo-glow">
              <div class="super-stat-header">
                <span class="super-stat-title">Aktif Firma Sayısı</span>
                <div class="super-stat-icon-circle bg-indigo">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                </div>
              </div>
              <div class="super-stat-body">
                <h3 class="super-stat-number" id="superStatActiveCompanies">28</h3>
                <div class="super-stat-trend positive">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"></polyline></svg>
                  <span>+4 Firma</span>
                  <span class="trend-label">bu ay katıldı</span>
                </div>
              </div>
              <div class="super-stat-sub">
                <span>14 Demo • 24 Yıllık Lisans</span>
              </div>
            </div>

            <!-- Card 2: Toplam Lisanslı Personel Havuzu -->
            <div class="super-stat-card">
              <div class="super-stat-header">
                <span class="super-stat-title">Lisanslı Personel</span>
                <div class="super-stat-icon-circle bg-emerald">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
              </div>
              <div class="super-stat-body">
                <h3 class="super-stat-number" id="superStatTotalSeats">412</h3>
                <div class="super-stat-trend positive">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"></polyline></svg>
                  <span>%82.4</span>
                  <span class="trend-label">kota doluluk oranı</span>
                </div>
              </div>
              <div class="super-stat-sub">
                <span>Toplam 500 Kota Kapasitesi</span>
              </div>
            </div>

            <!-- Card 3: Bekleyen Demo Talepleri -->
            <div class="super-stat-card card-amber-glow">
              <div class="super-stat-header">
                <span class="super-stat-title">Bekleyen Demo Talebi</span>
                <div class="super-stat-icon-circle bg-amber">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
              </div>
              <div class="super-stat-body">
                <h3 class="super-stat-number text-amber" id="superStatPendingDemos">5</h3>
                <div class="super-stat-trend alert-pulse">
                  <span>⚡ Onay Bekliyor</span>
                </div>
              </div>
              <div class="super-stat-sub">
                <span>Ortalama demo süresi: 14 Gün</span>
              </div>
            </div>

            <!-- Card 4: Aylık Tekrarlayan Ciro (MRR) -->
            <div class="super-stat-card card-purple-glow">
              <div class="super-stat-header">
                <span class="super-stat-title">Aylık Gelir (MRR)</span>
                <div class="super-stat-icon-circle bg-purple">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 6v12"></path></svg>
                </div>
              </div>
              <div class="super-stat-body">
                <h3 class="super-stat-number" id="superStatMRR">₺61.400</h3>
                <div class="super-stat-trend positive">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"></polyline></svg>
                  <span>%18.2</span>
                  <span class="trend-label">ARR: ₺736.800</span>
                </div>
              </div>
              <div class="super-stat-sub">
                <span>Kişi başı ort. ₺149/ay</span>
              </div>
            </div>

          </div>

          <!-- DASHBOARD DEMO TALEPLERİ TABLOSU -->
          <div class="super-dash-card full-width">
            <div class="super-dash-card-header">
              <div class="flex items-center gap-2">
                <span class="super-card-icon-pill bg-amber-soft">📩</span>
                <div>
                  <h3 class="super-dash-card-title">En Son Gelen Demo Talepleri</h3>
                  <p class="super-dash-card-subtitle">Kurumsal firmaların web üzerinden ilettiği demo aktivasyon başvuruları.</p>
                </div>
              </div>
              <button class="super-see-all-pill" id="btnDashSeeAllDemos">
                <span>Tümünü Gör</span>
                <span class="super-pill-count" id="dashSeeAllCount">5</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </button>
            </div>

            <div class="super-table-responsive">
              <table class="super-table super-dash-demo-table">
                <thead>
                  <tr>
                    <th>FİRMA &amp; SEKTÖR</th>
                    <th>YETKİLİ</th>
                    <th>ÇALIŞAN</th>
                    <th>TARİH</th>
                    <th class="text-right">DEMO İŞLEMİ</th>
                  </tr>
                </thead>
                <tbody id="superDashDemoTableBody">
                  <!-- Dinamik Dash Demo Satırları -->
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- =======================================================
             SÜPER ADMİN GÖRÜNÜM 2: FİRMA LİSTESİ & YÖNETİMİ
             ======================================================= -->
        <section class="super-view" id="viewSuperCompanies">
          
          <div class="super-view-header">
            <div>
              <h2 class="super-view-title">Kurumsal Firma Yönetimi</h2>
              <p class="super-view-subtitle">Sisteme kayıtlı tüm firmaları, çalışan kotalarını, lisans paketlerini ve durumlarını yönetin.</p>
            </div>
            <button class="super-btn-primary" id="btnOpenAddCompanyModal">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              <span>Yeni Firma Oluştur</span>
            </button>
          </div>

          <!-- Filter Toolbar -->
          <div class="super-filter-bar">
            <div class="super-filter-left">
              <div class="super-search-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="superCompanySearch" placeholder="Firma adı, yetkili veya sektör ara...">
              </div>

              <select class="super-select" id="superCompanyStatusFilter">
                <option value="all">Tüm Durumlar</option>
                <option value="active">🟢 Aktif Lisanslı</option>
                <option value="demo">⚡ 14 Günlük Demo</option>
                <option value="expired">⚠️ Süresi Dolan</option>
                <option value="suspended">⏸️ Dondurulan</option>
              </select>

              <select class="super-select" id="superCompanyPlanFilter">
                <option value="all">Tüm Kullanıcı Dilimleri</option>
                <option value="tier-1-10">1-10 Kullanıcı</option>
                <option value="tier-11-20">11-20 Kullanıcı</option>
                <option value="tier-21-30">21-30 Kullanıcı</option>
                <option value="tier-31-40">31-40 Kullanıcı</option>
                <option value="tier-41-50">41-50 Kullanıcı</option>
                <option value="tier-50-100">50-100 Kullanıcı</option>
                <option value="tier-100plus">100+ Kullanıcı</option>
              </select>
            </div>

            <div class="super-filter-right">
              <span class="super-count-badge" id="superCompaniesCountBadge">Toplam 28 Firma</span>
            </div>
          </div>

          <!-- Companies Table Card -->
          <div class="super-table-card">
            <div class="super-table-responsive">
              <table class="super-table">
                <thead>
                  <tr>
                    <th>FİRMA &amp; SEKTÖR</th>
                    <th>YETKİLİ &amp; İLETİŞİM</th>
                    <th>KULLANICI DİLİMİ</th>
                    <th>KOTA (KULLANICI)</th>
                    <th>LİSANS BİTİŞ</th>
                    <th>AYLIK GELİR</th>
                    <th>DURUM</th>
                    <th class="text-right">İŞLEMLER</th>
                  </tr>
                </thead>
                <tbody id="superCompaniesTableBody">
                  <!-- Dinamik Firma Satırları -->
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- =======================================================
             SÜPER ADMİN GÖRÜNÜM 3: DEMO TALEPLERİ & ONAY HAVUZU
             ======================================================= -->
        <section class="super-view" id="viewSuperDemoRequests">
          
          <div class="super-view-header">
            <div>
              <h2 class="super-view-title">Demo Talepleri &amp; Aktivasyon Havuzu</h2>
              <p class="super-view-subtitle">Web sitenizden gelen demo başvurularını kontrol edin, tek tıkla 14 günlük kurumsal demo açın.</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="super-pill-stat bg-amber-soft">⏳ <strong>5</strong> Bekleyen</span>
              <span class="super-pill-stat bg-emerald-soft">✅ <strong>18</strong> Onaylanan</span>
            </div>
          </div>

          <!-- Filter Bar -->
          <div class="super-filter-bar">
            <div class="super-filter-left">
              <div class="super-search-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="superDemoSearch" placeholder="Firma adı, yetkili adı veya telefon ara...">
              </div>

              <select class="super-select" id="superDemoStatusFilter">
                <option value="all">Tüm Başvurular</option>
                <option value="pending" selected>⏳ Onay Bekleyenler (5)</option>
                <option value="approved">✅ Demo Açılanlar</option>
                <option value="rejected">❌ Reddedilenler</option>
              </select>
            </div>

            <div class="super-filter-right">
              <span class="super-count-badge" id="superDemosCountBadge">5 Talep Listeleniyor</span>
            </div>
          </div>

          <!-- Demo Requests Table -->
          <div class="super-table-card">
            <div class="super-table-responsive">
              <table class="super-table">
                <thead>
                  <tr>
                    <th>BAŞVURAN FİRMA</th>
                    <th>YETKİLİ &amp; E-POSTA</th>
                    <th>TELEFON</th>
                    <th>TALEP EDİLEN ÇALIŞAN</th>
                    <th>BAŞVURU TARİHİ</th>
                    <th>DURUM</th>
                    <th class="text-right">DEMO AKSİYONU</th>
                  </tr>
                </thead>
                <tbody id="superDemoTableBody">
                  <!-- Dinamik Demo Başvuru Satırları -->
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- =======================================================
             SÜPER ADMİN GÖRÜNÜM 4: ÇALIŞAN SAYISINA GÖRE FİYATLANDIRMA
             ======================================================= -->
        <section class="super-view" id="viewSuperPricing">
          
          <div class="super-view-header">
            <div>
              <h2 class="super-view-title">Kullanıcı Sayısına Göre Fiyatlandırma &amp; Dilimler</h2>
              <p class="super-view-subtitle">Personel adedi dilimlerine göre yıllık fiyat hesaplayıcısını ve dilim birim fiyatlarını yönetin.</p>
            </div>
            <button class="super-btn-primary" id="btnSaveAllPricingPlans">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
              <span>💾 Fiyat Dilimlerini Kaydet</span>
            </button>
          </div>

          <!-- 1. HERO DİNAMİK FİYAT & KOTA SİMÜLATÖRÜ -->
          <div class="super-pricing-simulator-card">
            <div class="sim-header">
              <div class="flex items-center gap-3">
                <span class="sim-icon">🧮</span>
                <div>
                  <h3 class="sim-title">İnteraktif Çalışan Sayısı Lisans Hesaplayıcısı</h3>
                  <p class="sim-subtitle">Müşteriye yıllık teklif verirken çalışan dilimini seçerek anlık lisans bedelini hesaplayın.</p>
                </div>
              </div>
            </div>

            <div class="sim-body">
              <!-- Slider & Input Controls -->
              <div class="sim-controls-row">
                <div class="sim-slider-wrap">
                  <div class="flex justify-between items-center mb-2">
                    <label class="sim-label">Seçilen Kullanıcı Dilimi:</label>
                    <div class="sim-number-box" style="min-width: 140px; justify-content: center;">
                      <span id="simSelectedTierDisplay" class="font-bold text-indigo-600 text-sm">21-30 Kullanıcı</span>
                    </div>
                  </div>
                  <input type="range" id="simEmployeeSlider" min="0" max="6" step="1" value="2" class="super-range-slider">
                  <div class="sim-range-marks">
                    <span class="range-step-mark" data-step="0">1-10</span>
                    <span class="range-step-mark" data-step="1">11-20</span>
                    <span class="range-step-mark" data-step="2">21-30</span>
                    <span class="range-step-mark" data-step="3">31-40</span>
                    <span class="range-step-mark" data-step="4">41-50</span>
                    <span class="range-step-mark" data-step="5">50-100</span>
                    <span class="range-step-mark" data-step="6">100+</span>
                  </div>
                </div>

                <!-- Live Calculated Summary Card -->
                <div class="sim-calc-summary-card" id="simResultCard">
                  <div class="sim-matched-plan-badge" id="simMatchedPlanBadge">
                    👑 21-30 Kullanıcı Dilimi Eşleşti
                  </div>
                  <div class="sim-price-details">
                    <div class="sim-price-row">
                      <span>Kişi Başı Yıllık Fiyat:</span>
                      <strong id="simUnitSeatPrice">₺1.490 / kişi / yıl</strong>
                    </div>
                    <div class="sim-price-row highlight-yearly">
                      <span>Yıllık Toplam Lisans Bedeli:</span>
                      <strong class="sim-total-arr" id="simTotalArr">₺44.700 / yıl</strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 2. KULLANICI DİLİMLERİ FİYAT YÖNETİM TABLOSU -->
          <div class="super-tier-pricing-card mt-4">
            <div class="super-tier-card-header">
              <div class="flex items-center gap-3">
                <span class="super-card-icon-pill bg-indigo-soft">⚙️</span>
                <div>
                  <h3 class="super-tier-card-title">Kullanıcı Sayısına Göre Fiyat Belirleme Alanı</h3>
                  <p class="super-tier-card-desc">Her bir personel dilimi için kişi başı yıllık liste fiyatını buradan düzenleyin.</p>
                </div>
              </div>
            </div>

            <div class="super-table-responsive mt-3">
              <table class="super-table super-tier-table">
                <thead>
                  <tr>
                    <th>KULLANICI DİLİMİ</th>
                    <th>AÇIKLAMA / HEDEF KİTLE</th>
                    <th>KİŞİ BAŞI YILLIK LİSANS FİYATI (₺)</th>
                    <th>DURUM</th>
                  </tr>
                </thead>
                <tbody id="superTierPricingTableBody">
                  <!-- Dinamik Fiyat Dilim Satırları app.js üzerinden yüklenecek -->
                </tbody>
              </table>
            </div>

            <div class="super-tier-card-footer mt-4 flex justify-end">
              <button class="super-btn-primary" id="btnSaveAllPricingPlansBottom">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                <span>💾 Fiyat Dilimlerini Kaydet</span>
              </button>
            </div>
          </div>

        </section>

        <!-- =======================================================
             SÜPER ADMİN GÖRÜNÜM 5: SAAS SİSTEM AYARLARI
             ======================================================= -->
        <section class="super-view" id="viewSuperSettings">
          
          <div class="super-view-header">
            <div>
              <h2 class="super-view-title">SaaS Platform Genel Yapılandırması</h2>
              <p class="super-view-subtitle">Platform markalaması, varsayılan demo kuralları, para birimi ve SaaS iletişim tercihleri.</p>
            </div>
            <button class="super-btn-primary" id="btnSaveSuperPlatformSettings">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
              <span>💾 Platform Ayarlarını Kaydet</span>
            </button>
          </div>

          <div class="super-settings-grid">
            
            <!-- Kart 1: SaaS Platform Bilgileri -->
            <div class="super-settings-card">
              <div class="settings-card-header">
                <span class="super-card-icon-pill bg-indigo-soft">🌐</span>
                <div>
                  <h3 class="settings-card-title">SaaS Marka &amp; İletişim Bilgileri</h3>
                  <p class="settings-card-desc">Sistem genelinde kullanılan platform adı ve destek kanalları.</p>
                </div>
              </div>

              <div class="styled-form mt-3">
                <div class="form-row">
                  <div class="form-group">
                    <label>SaaS Platform Adı</label>
                    <input type="text" id="settingPlatformName" value="MonaCard Multi-Tenant SaaS">
                  </div>
                  <div class="form-group">
                    <label>Platform Destek E-Postası</label>
                    <input type="email" id="settingSupportEmail" value="support@monacard.com">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label>SaaS Sahibi / Yönetici</label>
                    <input type="text" id="settingOwnerName" value="Muhiddin Öktem">
                  </div>
                  <div class="form-group">
                    <label>Varsayılan Para Birimi</label>
                    <select id="settingCurrency" class="super-select-field">
                      <option value="TRY" selected>₺ Türk Lirası (TRY)</option>
                      <option value="USD">$ Amerikan Doları (USD)</option>
                      <option value="EUR">€ Euro (EUR)</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Kart 2: Demo & Aktivasyon Kuralları -->
            <div class="super-settings-card">
              <div class="settings-card-header">
                <span class="super-card-icon-pill bg-amber-soft">⚡</span>
                <div>
                  <h3 class="settings-card-title">Demo &amp; Deneme Hesabı Kuralları</h3>
                  <p class="settings-card-desc">Yeni açılan kurumsal deneme hesaplarının otomatik kısıtları.</p>
                </div>
              </div>

              <div class="styled-form mt-3">
                <div class="form-row">
                  <div class="form-group">
                    <label>Varsayılan Demo Süresi (Gün)</label>
                    <input type="number" id="settingDefaultTrialDays" value="14" min="1" max="90">
                  </div>
                  <div class="form-group">
                    <label>Varsayılan Demo Çalışan Kotası (Kişi)</label>
                    <input type="number" id="settingDefaultTrialSeats" value="10" min="1" max="50">
                  </div>
                </div>

                <!-- Yenilenmiş Sol Hizalı Şık Checkbox Alanı -->
                <div class="super-clean-checkbox-row mt-4">
                  <label class="super-clean-chk-label" for="settingAutoDemoNotification">
                    <input type="checkbox" id="settingAutoDemoNotification" checked class="super-native-chk">
                    <span class="super-chk-box">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                    <span class="super-chk-text">Demo süresi dolmaya 3 gün kala firmaya otomatik yenileme hatırlatma e-postası gönder</span>
                  </label>
                </div>
              </div>
            </div>

          </div>

        </section>

      </div>
    </main>

  </div>

  <!-- =========================================================
       SÜPER ADMİN MODALLARI:
       1. Yeni Firma Ekle / Firma Düzenle Modalı
       2. Demo Talebi Onaylama & Demo Açma Modalı
       3. Demo Talebi Detay Modalı
       4. Lisans Süresi Uzatma & Kota Değiştirme Modalı
       ========================================================= -->

  <!-- 1. Firma Ekle / Düzenle Modalı -->
  <div class="modal-overlay" id="superCompanyModal" role="dialog" aria-modal="true" aria-labelledby="superCompModalTitle">
    <div class="modal-container super-modal-wide">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="superCompModalTitle">Yeni Firma Oluştur</h3>
          <p class="modal-subtitle">Kurumsal firma bilgilerini, yönetici hesabını, çalışan kotasını ve lisans süresini belirleyin.</p>
        </div>
        <button class="modal-close-btn" data-close="superCompanyModal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="superCompanyForm" class="styled-form">
          <input type="hidden" id="editSuperCompanyId" value="">
          
          <!-- Firma Temel Bilgileri -->
          <div class="form-row">
            <div class="form-group">
              <label for="superInputCompName">Firma / Kurum Resmi Adı *</label>
              <input type="text" id="superInputCompName" required placeholder="Örn: Atlas Finans A.Ş.">
            </div>
            <div class="form-group">
              <label for="superInputCompSector">Sektör *</label>
              <input type="text" id="superInputCompSector" required placeholder="Örn: Finans &amp; Bankacılık">
            </div>
          </div>

          <!-- Yönetici İletişim Bilgileri -->
          <div class="form-row">
            <div class="form-group">
              <label for="superInputCompManager">Yönetici / Yetkili Ad Soyad *</label>
              <input type="text" id="superInputCompManager" required placeholder="Örn: Selin Karaca">
            </div>
            <div class="form-group">
              <label for="superInputCompEmail">Yönetici E-Postası (Giriş İçin) *</label>
              <input type="email" id="superInputCompEmail" required placeholder="selin@atlasfinans.com">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="superInputCompPhone">İletişim Telefonu *</label>
              <input type="tel" id="superInputCompPhone" required placeholder="+90 532 000 00 00">
            </div>
            <div class="form-group">
              <label for="superInputCompPlan">Kullanıcı / Lisans Dilimi *</label>
              <select id="superInputCompPlan" class="super-select-field">
                <option value="tier-1-10">1-10 Kullanıcı</option>
                <option value="tier-11-20">11-20 Kullanıcı</option>
                <option value="tier-21-30" selected>21-30 Kullanıcı</option>
                <option value="tier-31-40">31-40 Kullanıcı</option>
                <option value="tier-41-50">41-50 Kullanıcı</option>
                <option value="tier-50-100">50-100 Kullanıcı</option>
                <option value="tier-100plus">100+ Kullanıcı (Kurumsal / Özel)</option>
              </select>
            </div>
          </div>

          <!-- Kota & Lisans Durumu -->
          <div class="form-divider">
            <span>Çalışan Kotası &amp; Lisans Süresi</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="superInputCompQuota">Çalışan Sayısı Kotası (Seat Limiti) *</label>
              <input type="number" id="superInputCompQuota" min="1" max="1000" value="25" required>
            </div>
            <div class="form-group">
              <label for="superInputCompStatus">Lisans Türü / Durum *</label>
              <select id="superInputCompStatus" class="super-select-field">
                <option value="active" selected>🟢 Aktif Yıllık Lisans</option>
                <option value="demo">⚡ 14 Günlük Demo</option>
                <option value="suspended">⏸️ Dondurulmuş</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="superInputCompBilling">Faturalandırma Periyodu</label>
              <select id="superInputCompBilling" class="super-select-field">
                <option value="yearly" selected>Yıllık Peşin (Önerilen - %20 İndirimli)</option>
                <option value="monthly">Aylık Düzenli</option>
              </select>
            </div>
            <div class="form-group">
              <label for="superInputCompExpiry">Lisans Bitiş Tarihi *</label>
              <input type="date" id="superInputCompExpiry" required>
            </div>
          </div>

          <div class="modal-btn-row mt-4">
            <button type="submit" class="super-btn-primary full-width" id="btnSaveSuperCompany">
              <span>💾 Firmayı Kaydet &amp; Yetkilendir</span>
            </button>
            <button type="button" class="btn-outline full-width" data-close="superCompanyModal">İptal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- 2. Demo Onaylama & Demo Açma Modalı -->
  <div class="modal-overlay" id="superDemoApproveModal" role="dialog" aria-modal="true" aria-labelledby="superDemoApproveTitle">
    <div class="modal-container">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="superDemoApproveTitle">Kurumsal Demo Aç</h3>
          <p class="modal-subtitle">Demo talebini onaylayarak 14 günlük kurumsal hesabı anında aktifleştirin.</p>
        </div>
        <button class="modal-close-btn" data-close="superDemoApproveModal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="super-demo-approve-box">
          <div class="demo-approve-company-tag" id="demoApproveCompName">Firma Adı</div>
          
          <div class="demo-approve-meta-stack">
            <div class="demo-meta-row">
              <span class="meta-label">Yetkili:</span>
              <strong id="demoApproveContact">-</strong>
            </div>
            <div class="demo-meta-row">
              <span class="meta-label">E-Posta:</span>
              <strong id="demoApproveEmail">-</strong>
            </div>
            <div class="demo-meta-row">
              <span class="meta-label">Talep Edilen Kota:</span>
              <strong id="demoApproveSeats">-</strong>
            </div>
          </div>

          <div class="form-divider">
            <span>Demo Aktivasyon Parametreleri</span>
          </div>

          <div class="form-stack">
            <div class="form-group">
              <label>Demo Süresi (Gün)</label>
              <input type="number" id="approveDemoDays" value="14" min="3" max="60" class="super-input-field">
            </div>
            <div class="form-group">
              <label>Tanımlanacak Çalışan Kotası</label>
              <div class="super-fixed-tier-pill">
                <span class="super-tier-badge">1-10 Kullanıcı</span>
                <span class="text-xs text-muted font-bold">(Standart Demo Paketi)</span>
                <input type="hidden" id="approveDemoQuota" value="10">
              </div>
            </div>
          </div>

          <div class="demo-generated-credentials-box hidden" id="demoGeneratedCredentials">
            <span class="font-bold text-xs text-emerald-700 block mb-1">🎉 Demo Hesabı Başarıyla Oluşturuldu!</span>
            <div class="credential-row">
              <span>Giriş Linki:</span>
              <code id="demoGenLink">https://monacard.com/login?demo=1</code>
            </div>
            <div class="credential-row">
              <span>Geçici Şifre:</span>
              <code id="demoGenPass">MonaDemo2026!</code>
            </div>
          </div>
        </div>

        <div class="modal-btn-row mt-4">
          <button type="button" class="super-btn-primary flex-1" id="btnConfirmApproveDemo">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Demoyu Aç &amp; Firmayı Başlat</span>
          </button>
          <button type="button" class="btn-outline btn-modal-cancel" data-close="superDemoApproveModal">Kapat</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Demo Detay Modalı -->
  <div class="modal-overlay" id="superDemoDetailsModal" role="dialog" aria-modal="true" aria-labelledby="superDemoDetTitle">
    <div class="modal-container">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="superDemoDetTitle">Demo Talep Detayı</h3>
          <p class="modal-subtitle">Web başvuru formu üzerinden iletilen tam mesaj ve bilgiler.</p>
        </div>
        <button class="modal-close-btn" data-close="superDemoDetailsModal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="super-detail-summary-card">
          <div class="detail-row">
            <span class="detail-lbl">Firma Adı:</span>
            <strong id="detDemoCompName">-</strong>
          </div>
          <div class="detail-row">
            <span class="detail-lbl">Sektör:</span>
            <span id="detDemoSector">-</span>
          </div>
          <div class="detail-row">
            <span class="detail-lbl">Yetkili Kişi:</span>
            <span id="detDemoContact">-</span>
          </div>
          <div class="detail-row">
            <span class="detail-lbl">E-Posta:</span>
            <a href="#" id="detDemoEmail" class="text-indigo-600 font-bold">-</a>
          </div>
          <div class="detail-row">
            <span class="detail-lbl">Telefon:</span>
            <a href="#" id="detDemoPhone" class="font-mono">-</a>
          </div>
          <div class="detail-row">
            <span class="detail-lbl">Talep Edilen Personel:</span>
            <strong id="detDemoSeats" class="text-indigo-600">-</strong>
          </div>
          <div class="detail-row">
            <span class="detail-lbl">Başvuru Tarihi:</span>
            <span id="detDemoDate">-</span>
          </div>
          <div class="detail-note-box mt-3">
            <span class="text-xs font-bold text-slate-500 block mb-1">Müşteri Notu / İhtiyaç Özeti:</span>
            <p id="detDemoMessage" class="text-sm text-slate-800">-</p>
          </div>
        </div>

        <div class="modal-btn-row mt-4">
          <button type="button" class="super-btn-primary flex-1" id="btnDetApproveDemoAction">
            <span>Demoyu Aç</span>
          </button>
          <button type="button" class="btn-outline btn-modal-cancel" data-close="superDemoDetailsModal">Kapat</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Lisans Süresi Uzat / Kota Artır Modalı -->
  <div class="modal-overlay" id="superExtendLicenseModal" role="dialog" aria-modal="true" aria-labelledby="superExtendTitle">
    <div class="modal-container">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="superExtendTitle">Lisans &amp; Kota Yönetimi</h3>
          <p class="modal-subtitle" id="superExtendSubtitle">Firma lisans süresini uzatın veya çalışan kotasını artırın.</p>
        </div>
        <button class="modal-close-btn" data-close="superExtendLicenseModal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="superExtendForm" class="styled-form">
          <input type="hidden" id="extendCompId" value="">

          <div class="form-group">
            <label>Yeni Çalışan Kotası (Kullanıcı Dilimi)</label>
            <select id="extendQuotaInput" class="super-select-field" required>
              <option value="10">1-10 Kullanıcı</option>
              <option value="20">11-20 Kullanıcı</option>
              <option value="30">21-30 Kullanıcı</option>
              <option value="40">31-40 Kullanıcı</option>
              <option value="50">41-50 Kullanıcı</option>
              <option value="100">50-100 Kullanıcı</option>
              <option value="150">100+ Kullanıcı (Kurumsal Özel)</option>
            </select>
          </div>

          <div class="form-group">
            <label>Yeni Lisans Bitiş Tarihi</label>
            <input type="date" id="extendExpiryInput" class="super-input-field" required>
          </div>

          <div class="form-group">
            <label>Durum Güncelle</label>
            <select id="extendStatusSelect" class="super-select-field">
              <option value="active">🟢 Aktif Lisanslı</option>
              <option value="demo">⚡ 14 Günlük Demo</option>
              <option value="suspended">⏸️ Dondurulmuş</option>
            </select>
          </div>

          <div class="modal-btn-row mt-4">
            <button type="submit" class="super-btn-primary flex-1">
              <span>💾 Lisansı Güncelle &amp; Kaydet</span>
            </button>
            <button type="button" class="btn-outline btn-modal-cancel" data-close="superExtendLicenseModal">Kapat</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- =========================================================
       ADMIN MODALLAR:
       0. Personel Ekleme Yöntemi Seçim Modalı (3 Seçenekli Şık Açılır Kart)
       0.1 Davet Linki & QR Modalı
       0.2 Excel / CSV ile Toplu Yükleme Modalı
       1. Yeni Personel / Lider Ekleme & Düzenleme Modalı (Manuel Form)
       2. Personel Performans Detay Modalı
       3. Yönetici Özel Toplantı Ekleme Modalı
       ========================================================= -->

  <!-- 0. Personel Ekleme Yöntemi Seçim Modalı (3 Seçenekli Şık Açılır Kart) -->
  <div class="modal-overlay" id="adminStaffAddChoiceModal" role="dialog" aria-modal="true" aria-labelledby="staffChoiceModalTitle">
    <div class="modal-container staff-choice-modal-container">
      <div class="modal-header">
        <div>
          <div class="staff-choice-header-badge">
            <span>✨ Zahmetsiz &amp; Hızlı Onboarding</span>
          </div>
          <h3 class="modal-title" id="staffChoiceModalTitle">Personel Ekleme Yöntemini Seçin</h3>
          <p class="modal-subtitle">Ekibinizi MonaCard sistemine dahil etmek için size en uygun yöntemi belirleyin.</p>
        </div>
        <button class="modal-close-btn" data-close="adminStaffAddChoiceModal">&times;</button>
      </div>

      <div class="modal-body staff-choice-modal-body">
        <div class="staff-choice-cards-grid">
          
          <!-- Seçenek 1: Davet Bağlantısı & QR ile Kayıt -->
          <div class="staff-choice-card" id="btnChoiceInviteLink" role="button" tabindex="0">
            <div class="staff-choice-card-inner">
              <div class="staff-choice-badge badge-purple">
                <span>🚀 En Kolay &amp; Otomatik</span>
              </div>
              <div class="staff-choice-icon-wrap icon-purple">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                  <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>
              </div>
              <h4 class="staff-choice-title">Davet Linki &amp; QR ile Paylaş</h4>
              <p class="staff-choice-desc">
                WhatsApp, Slack, Teams veya E-posta grubunuzda tek tıkla paylaşın. Personeller kendi bilgilerini, fotoğraflarını ve şifrelerini saniyeler içinde kendileri doldursun.
              </p>
              <div class="staff-choice-footer">
                <span class="staff-choice-action-btn btn-purple-soft">
                  <span>Davet Et &amp; Paylaş</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
              </div>
            </div>
          </div>

          <!-- Seçenek 2: Excel / CSV ile Toplu Yükleme -->
          <div class="staff-choice-card" id="btnChoiceExcelImport" role="button" tabindex="0">
            <div class="staff-choice-card-inner">
              <div class="staff-choice-badge badge-emerald">
                <span>📊 Toplu Ekipler İçin</span>
              </div>
              <div class="staff-choice-icon-wrap icon-emerald">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="8" y1="13" x2="16" y2="13"></line>
                  <line x1="8" y1="17" x2="16" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
              </div>
              <h4 class="staff-choice-title">Excel / CSV ile Toplu Yükle</h4>
              <p class="staff-choice-desc">
                Hazır Excel şablonunu indirin, personel listenizi yapıştırıp tek tıkla yükleyin. Onlarca personel anında sisteme kaydedilsin.
              </p>
              <div class="staff-choice-footer">
                <span class="staff-choice-action-btn btn-emerald-soft">
                  <span>Excel Yükle</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
              </div>
            </div>
          </div>

          <!-- Seçenek 3: Tek Tek Manuel Form ile Ekle -->
          <div class="staff-choice-card" id="btnChoiceSingleManual" role="button" tabindex="0">
            <div class="staff-choice-card-inner">
              <div class="staff-choice-badge badge-blue">
                <span>✍️ Hızlı Tekil Kayıt</span>
              </div>
              <div class="staff-choice-icon-wrap icon-blue">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="8.5" cy="7" r="4"></circle>
                  <line x1="20" y1="8" x2="20" y2="14"></line>
                  <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
              </div>
              <h4 class="staff-choice-title">Manuel Form ile Ekle</h4>
              <p class="staff-choice-desc">
                Tek bir personeli veya takım liderini doğrudan yönetici panelinden bilgilerini, ünvanını ve satış hedefini girerek ekleyin.
              </p>
              <div class="staff-choice-footer">
                <span class="staff-choice-action-btn btn-blue-soft">
                  <span>Manuel Formu Aç</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- 0.1 Davet Linki & QR Modalı -->
  <div class="modal-overlay" id="modalStaffInviteLink" role="dialog" aria-modal="true" aria-labelledby="inviteLinkModalTitle">
    <div class="modal-container" style="max-width: 580px;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="inviteLinkModalTitle">🔗 Personel Davet Bağlantısı</h3>
          <p class="modal-subtitle">Bu bağlantıyı personellerinize ileterek MonaCard profillerini kendilerinin oluşturmasını sağlayın.</p>
        </div>
        <button class="modal-close-btn" data-close="modalStaffInviteLink">&times;</button>
      </div>
      <div class="modal-body" style="padding: 1.5rem;">
        
        <div class="invite-banner-card">
          <div class="invite-banner-icon">✨</div>
          <div class="invite-banner-text">
            <strong>Nasıl Çalışır?</strong> Personel bu bağlantıyı açtığında isim, e-posta, telefon ve fotoğrafını girip kendi şifresini belirler. Kayıt tamamlandığında otomatik olarak şirketinizin ekibine dahil olur.
          </div>
        </div>

        <div class="form-group mt-4">
          <label class="invite-label">Şirket Özel Katılım Bağlantısı</label>
          <div class="invite-input-copy-group">
            <input type="text" id="staffInviteLinkInput" readonly value="http://monacard2.test/join/vedubox?token=vdx-78492" class="invite-link-field">
            <button type="button" class="btn-copy-link" id="btnCopyStaffInviteLink">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
              <span id="copyLinkBtnText">Kopyala</span>
            </button>
          </div>
        </div>

        <div class="invite-share-channels-row mt-4">
          <button type="button" class="invite-channel-btn btn-wa" id="btnShareInviteWhatsApp">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
            <span>WhatsApp ile Paylaş</span>
          </button>
          <button type="button" class="invite-channel-btn btn-slack" id="btnShareInviteSlack">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6 15a2 2 0 0 1-2 2 2 2 0 0 1-2-2 2 2 0 0 1 2-2h2v2zm1 0a2 2 0 0 1 2-2 2 2 0 0 1 2 2v5a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-5zm2-8a2 2 0 0 1-2-2 2 2 0 0 1 2-2 2 2 0 0 1 2 2v2H9zm0 1a2 2 0 0 1 2 2 2 2 0 0 1-2 2H4a2 2 0 0 1-2-2 2 2 0 0 1 2-2h5zm8 2a2 2 0 0 1 2-2 2 2 0 0 1 2 2 2 2 0 0 1-2 2h-2v-2zm-1 0a2 2 0 0 1-2 2 2 2 0 0 1-2-2V5a2 2 0 0 1 2-2 2 2 0 0 1 2 2v5zm-2 8a2 2 0 0 1 2 2 2 2 0 0 1-2 2 2 2 0 0 1-2-2v-2h2zm0-1a2 2 0 0 1-2-2 2 2 0 0 1 2-2h5a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-5z"/></svg>
            <span>Slack / Teams Duyurusu</span>
          </button>
          <button type="button" class="invite-channel-btn btn-telegram" id="btnShareInviteTelegram">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18.718-1.52 7.154-2.208 10.428-.29 1.385-.802 1.848-1.298 1.894-.972.089-1.71-.643-2.652-1.261-1.474-.967-2.306-1.569-3.738-2.513-1.654-1.09-.582-1.689.361-2.668.247-.256 4.536-4.159 4.62-4.512.01-.044.02-.208-.077-.295-.098-.086-.242-.057-.346-.033-.148.034-2.508 1.595-7.078 4.68-.67.46-1.277.685-1.82.673-.599-.013-1.751-.339-2.607-.617-1.05-.341-1.886-.521-1.813-1.099.038-.301.453-.61 1.246-.926 4.883-2.127 8.14-3.53 9.771-4.209 4.654-1.938 5.621-2.275 6.251-2.286.139-.002.449.033.65.197.17.138.217.324.239.454.022.13.048.423.028.654z"/></svg>
            <span>Telegram ile Gönder</span>
          </button>
          <button type="button" class="invite-channel-btn btn-mail" id="btnShareInviteEmail">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <span>E-Posta ile Gönder</span>
          </button>
        </div>

        <div class="invite-qr-section mt-4">
          <div class="invite-qr-card">
            <div class="invite-qr-preview" id="staffInviteQrPreview">
              <svg width="84" height="84" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="text-indigo-600 dark:text-indigo-400"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="7" y="7" width="1" height="1"></rect><rect x="18" y="7" width="1" height="1"></rect><rect x="7" y="18" width="1" height="1"></rect><rect x="11" y="11" width="2" height="2"></rect></svg>
            </div>
            <div class="invite-qr-info">
              <h5 class="invite-qr-title">Şirket İçi Ekran / QR Kod</h5>
              <p class="invite-qr-sub">Ofiste veya toplantıda ekrana yansıtarak personellerin akıllı telefonlarıyla okutmasını sağlayabilirsiniz.</p>
              <button type="button" class="btn-xs-outline mt-2" id="btnDownloadInviteQR">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>QR Kodu İndir</span>
              </button>
            </div>
          </div>
        </div>

        <div class="modal-btn-row mt-4">
          <button type="button" class="btn-outline" data-close="modalStaffInviteLink">Kapat</button>
          <button type="button" class="btn-primary" id="btnBackToChoiceFromInvite">&larr; Başka Yöntem Seç</button>
        </div>

      </div>
    </div>
  </div>

  <!-- 0.2 Excel / CSV ile Toplu Yükleme Modalı -->
  <div class="modal-overlay" id="modalStaffExcelImport" role="dialog" aria-modal="true" aria-labelledby="excelModalTitle">
    <div class="modal-container" style="max-width: 740px;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="excelModalTitle">📁 Excel / CSV ile Toplu Personel Yükleme</h3>
          <p class="modal-subtitle">Personel listenizi içeren CSV veya Excel dosyasını yükleyin, saniyeler içinde sisteme aktarın.</p>
        </div>
        <button class="modal-close-btn" data-close="modalStaffExcelImport">&times;</button>
      </div>
      <div class="modal-body" style="padding: 1.5rem;">
        
        <!-- Step 1: Download Template -->
        <div class="excel-step-banner mb-4">
          <div class="excel-step-badge">Adım 1</div>
          <div class="excel-step-content">
            <h5 class="excel-step-title">Örnek CSV / Excel Şablonunu İndirin</h5>
            <p class="excel-step-sub">Ad Soyad, Ünvan, E-Posta, Telefon ve Aylık Hedef sütunlarını içeren hazır şablonu indirin ve personellerinizi yazın.</p>
          </div>
          <button type="button" class="btn-download-template" id="btnDownloadStaffTemplate">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            <span>Şablonu İndir (.CSV)</span>
          </button>
        </div>

        <!-- Step 2: Upload Zone -->
        <div class="excel-upload-zone" id="excelDropZone">
          <input type="file" id="excelStaffFileInput" accept=".csv, .xlsx, .xls, text/csv" style="display: none;">
          <div class="excel-upload-icon-circle">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
          </div>
          <h4 class="excel-drop-title">Doldurduğunuz Dosyayı Buraya Sürükleyin</h4>
          <p class="excel-drop-sub">veya bilgisayarınızdan seçmek için aşağıdaki butona tıklayın (.CSV, .XLSX)</p>
          <button type="button" class="btn-browse-file mt-3" id="btnBrowseExcelFile">
            📁 Bilgisayardan Dosya Seç
          </button>
          <div class="text-xs text-slate-500 mt-2 font-medium" id="selectedExcelFileName"></div>
        </div>

        <!-- Step 3: Live Preview Table (Initially hidden) -->
        <div id="excelPreviewSection" style="display: none;" class="mt-4">
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
              <span class="font-bold text-sm text-slate-800 dark:text-slate-100">Önizleme &amp; Kontrol:</span>
              <span class="excel-count-pill" id="excelParsedCount">0 Personel Bulundu</span>
            </div>
            <button type="button" class="text-xs text-red-500 hover:underline font-semibold" id="btnClearExcelParsed">Temizle &amp; Yeniden Yükle</button>
          </div>
          <div class="excel-preview-scroll">
            <table class="excel-preview-table">
              <thead>
                <tr>
                  <th>Ad Soyad</th>
                  <th>Ünvan</th>
                  <th>E-Posta</th>
                  <th>Telefon</th>
                  <th>Hedef</th>
                  <th>Rol</th>
                </tr>
              </thead>
              <tbody id="excelPreviewTableBody">
                <!-- Parsed rows injected here -->
              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-btn-row mt-4">
          <button type="button" class="btn-outline" data-close="modalStaffExcelImport">İptal</button>
          <button type="button" class="btn-primary" id="btnConfirmExcelImport" disabled>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span id="btnConfirmExcelImportText">0 Personeli Sisteme Aktar</span>
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- 1. Personel Ekle / Düzenle / Lider Ata Modalı (Manuel Form) -->
  <div class="modal-overlay" id="adminStaffModal" role="dialog" aria-modal="true" aria-labelledby="staffModalTitle">
    <div class="modal-container">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="staffModalTitle">Yeni Personel Ekle</h3>
          <p class="modal-subtitle">Personel bilgilerini girin, liderlik rolü veya bağlı lider atayın.</p>
        </div>
        <button class="modal-close-btn" data-close="adminStaffModal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="adminStaffForm" class="styled-form">
          <input type="hidden" id="editStaffId" value="">
          
          <div class="form-row">
            <div class="form-group">
              <label for="staffInputName">Ad Soyad *</label>
              <input type="text" id="staffInputName" required placeholder="Örn: Ayşe Yılmaz">
            </div>
            <div class="form-group">
              <label for="staffInputTitle">Ünvan / Pozisyon *</label>
              <input type="text" id="staffInputTitle" required placeholder="Örn: Kıdemli Müşteri Temsilcisi">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="staffInputEmail">E-Posta *</label>
              <input type="email" id="staffInputEmail" required placeholder="ayse@vedubox.com">
            </div>
            <div class="form-group">
              <label for="staffInputPhone">Telefon *</label>
              <input type="tel" id="staffInputPhone" required placeholder="+90 5XX XXX XX XX">
            </div>
          </div>

          <!-- Liderlik & Takım Atama (3. Madde) -->
          <div class="form-divider">
            <span>Takım &amp; Hedef Yapılandırması</span>
          </div>

          <div class="form-group" id="staffLeaderSelectGroup">
            <label for="staffInputLeaderId">Bağlı Olduğu Takım Lideri</label>
            <select id="staffInputLeaderId" class="admin-select-field">
              <option value="">-- Bağımsız / Doğrudan Genel Müdüre Bağlı --</option>
              <!-- Dinamik Takım Liderleri Listelenir -->
            </select>
          </div>

          <div class="form-group">
            <label for="staffInputMonthlyTarget">Aylık Görüşme Hedefi (Adet) *</label>
            <input type="number" id="staffInputMonthlyTarget" required min="1" max="500" value="15" placeholder="Örn: 15">
          </div>

          <div class="form-group">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" id="staffInputIsLeader" style="width:18px; height:18px; accent-color: var(--admin-primary);">
              <span class="font-bold text-slate-800">👑 Bu Personel Bir "Takım Lideri" (Team Lead) olsun</span>
            </label>
            <p class="text-xs text-muted mt-1">Takım liderleri kendi altındaki personellerin müşteri havuzunu ve performansını takip edebilir.</p>
          </div>

          <div class="modal-btn-row mt-4">
            <button type="button" class="btn-outline" data-close="adminStaffModal">İptal</button>
            <button type="submit" class="btn-primary" id="btnSaveStaffModal">Personeli Kaydet</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- 2. Hızlı Hedef Belirleme Modalı -->
  <div class="modal-overlay" id="adminStaffTargetModal" role="dialog" aria-modal="true" aria-labelledby="targetModalTitle">
    <div class="modal-container" style="max-width: 440px;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="targetModalTitle">🎯 Personel Hedefi Belirle</h3>
          <p class="modal-subtitle" id="targetModalSubtitle">Aylık müşteri kazanım ve görüşme hedefi</p>
        </div>
        <button class="modal-close-btn" data-close="adminStaffTargetModal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="adminTargetForm" class="styled-form">
          <input type="hidden" id="targetStaffId" value="">
          <div class="form-group">
            <label for="targetGoalInput">Aylık Hedeflenen Görüşme Adedi *</label>
            <input type="number" id="targetGoalInput" min="1" max="1000" required value="15" placeholder="Örn: 20">
          </div>
          <p class="text-xs text-muted">Belirlenen hedef, personelin performans karnesinde ve takım hedef panosunda canlı olarak güncellenir.</p>
          <div class="modal-btn-row mt-3">
            <button type="button" class="btn-outline" data-close="adminStaffTargetModal">İptal</button>
            <button type="submit" class="btn-primary" style="background:#4F46E5;">Hedefi Kaydet &amp; Uygula</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- 2.1. Bağlı Personeller Listesi Modalı -->
  <div class="modal-overlay" id="staffSubordinatesModal" role="dialog" aria-modal="true" aria-labelledby="subordinatesModalTitle">
    <div class="modal-container" style="max-width: 580px;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="subordinatesModalTitle">👥 Bağlı Personeller Listesi</h3>
          <p class="modal-subtitle" id="subordinatesModalSubtitle">Bu takım liderine bağlı çalışan ekip üyeleri</p>
        </div>
        <button class="modal-close-btn" data-close="staffSubordinatesModal">&times;</button>
      </div>
      <div class="modal-body" id="subordinatesModalBody" style="padding: 16px 20px;">
        <!-- Dinamik Alt Personel Kartları -->
      </div>
    </div>
  </div>

  <!-- 2.2. Müşteri & Veri Aktarma Modalı (Madde 5) -->
  <div class="modal-overlay" id="transferCustomersModal" role="dialog" aria-modal="true" aria-labelledby="transferModalTitle">
    <div class="modal-container" style="max-width: 520px;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="transferModalTitle">🔄 Müşteri &amp; Verileri Aktar</h3>
          <p class="modal-subtitle" id="transferModalSubtitle">Personelin tüm müşteri, görüşme ve notlarını aktaracağınız personeli seçin.</p>
        </div>
        <button class="modal-close-btn" data-close="transferCustomersModal">&times;</button>
      </div>
      <div class="modal-body" style="padding: 16px 20px;">
        <!-- Source Staff Summary Banner -->
        <div class="transfer-source-banner" id="transferSourceBanner">
          <div class="source-avatar" id="transferSourceAvatar">AR</div>
          <div class="source-info">
            <span class="source-label">Kaynak Personel (Verileri Aktarılacak)</span>
            <strong class="source-name" id="transferSourceName">Ali Rıza Çelik</strong>
            <span class="source-stats" id="transferSourceStats">28 Müşteri • 14 Sıcak • 8 Ilık • 6 Soğuk</span>
          </div>
        </div>

        <!-- Search Box (Icon Inside & Clean Spacing) -->
        <div class="form-group transfer-search-group" style="margin-top: 18px; margin-bottom: 22px;">
          <label for="transferStaffSearchInput" style="font-weight: 700; font-size: 13px; color: var(--admin-text-main); margin-bottom: 8px; display: block;">Hedef Personel Ara</label>
          <div class="transfer-search-input-wrap">
            <svg class="transfer-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="transferStaffSearchInput" class="transfer-search-input" placeholder="İsim veya departman yazın..." autocomplete="off">
          </div>
        </div>

        <!-- Target Staff Selection List -->
        <label style="font-weight: 700; font-size: 12px; color: var(--admin-text-sub); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 10px; display: block;">Aktarılacak Hedef Personeli Seçin</label>
        <div class="transfer-staff-list" id="transferStaffListContainer">
          <!-- Dinamik Hedef Personel Kartları -->
        </div>

        <div class="modal-btn-row mt-4" style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 24px;">
          <button type="button" class="btn-outline" data-close="transferCustomersModal" style="min-width: 100px; height: 44px; border-radius: 12px; font-weight: 600;">Vazgeç</button>
          <button type="button" class="btn-primary" id="btnConfirmTransfer" disabled style="min-width: 270px; height: 44px; padding: 0 22px; white-space: nowrap; border-radius: 12px; font-weight: 700; background: #4F46E5; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Tüm Müşterileri Aktar &amp; Onayla</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Personel Performans Detay Modalı -->
  <div class="modal-overlay" id="adminStaffDetailModal" role="dialog" aria-modal="true" aria-labelledby="staffDetModalTitle">
    <div class="modal-container" style="max-width: 650px;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="staffDetModalTitle">Personel Performans Raporu</h3>
          <p class="modal-subtitle" id="staffDetModalSubtitle">Müşteri kazanım ve görüşme istatistikleri</p>
        </div>
        <button class="modal-close-btn" data-close="adminStaffDetailModal">&times;</button>
      </div>
      <div class="modal-body" id="staffDetModalBody">
        <!-- Dinamik İstatistikler & Getirdiği Müşteriler -->
      </div>
    </div>
  </div>

  <!-- 3. Şirket Marka & Ürün Ekle / Düzenle Modalı -->
  <div class="modal-overlay" id="adminProductModal" role="dialog" aria-modal="true" aria-labelledby="adminProductModalTitle">
    <div class="modal-container" style="max-width: 480px;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="adminProductModalTitle">Yeni Marka / Ürün Ekle</h3>
          <p class="modal-subtitle">Tüm dijital kartvizitlerde yayınlanacak marka/ürün detaylarını girin.</p>
        </div>
        <button class="modal-close-btn" data-close="adminProductModal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="adminProductForm" class="styled-form">
          <input type="hidden" id="editProductId" value="">
          
          <div class="form-group">
            <label for="prodInputName">Marka / Ürün Adı *</label>
            <input type="text" id="prodInputName" required placeholder="Örn: Vedubox">
          </div>

          <div class="form-group">
            <label for="prodInputSubtitle">Kısa Açıklama / Slogan *</label>
            <input type="text" id="prodInputSubtitle" required placeholder="Örn: Online Eğitim & Akademi Platformu">
          </div>

          <div class="form-group">
            <label for="prodInputUrl">Web Sitesi / Yönlendirme Bağlantısı (URL) *</label>
            <input type="url" id="prodInputUrl" required placeholder="https://vedubox.com">
          </div>

          <div class="form-group">
            <label>Logo / Marka Görseli</label>
            <input type="hidden" id="prodInputLogo" value="">
            <input type="file" id="prodInputLogoFile" accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp" style="display:none;">
            
            <!-- Upload Dropzone (Browse) -->
            <div class="logo-upload-dropzone" id="prodLogoDropzone" onclick="document.getElementById('prodInputLogoFile').click()">
              <div class="logo-upload-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              </div>
              <div class="logo-upload-text">
                <strong>Logo Seç (Browse)</strong> veya buraya sürükleyin
              </div>
              <span class="logo-upload-hint">PNG, JPG, SVG veya WebP (Önerilen: 200x200px veya yatay logo)</span>
            </div>

            <!-- Preview Card (Seçilen görsel önizlemesi) -->
            <div class="logo-preview-card" id="prodLogoPreviewCard" style="display:none;">
              <div class="logo-preview-thumb">
                <img id="prodLogoPreviewImg" src="" alt="Logo Önizleme">
              </div>
              <div class="logo-preview-details">
                <span class="logo-preview-name" id="prodLogoFileName">Logo Yüklendi</span>
                <span class="logo-preview-status text-xs text-muted">Görsel hazır</span>
              </div>
              <div class="logo-preview-actions">
                <button type="button" class="btn-xs-outline" onclick="document.getElementById('prodInputLogoFile').click()" title="Görseli Değiştir">Değiştir</button>
                <button type="button" class="btn-xs-danger" id="btnRemoveProdLogo" title="Görseli Kaldır">Kaldır</button>
              </div>
            </div>
          </div>

          <div class="modal-btn-row mt-4">
            <button type="button" class="btn-outline" data-close="adminProductModal">İptal</button>
            <button type="submit" class="btn-primary" id="btnSaveProductSubmit">Kaydet &amp; Yayınla</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- =========================================================
       MODALLAR: KARTVİZİT BİLGİSİ AL MODALI (+ Butonu Tıklanınca Açılır)
       ========================================================= -->
  <div class="modal-overlay" id="cardCaptureModal" role="dialog" aria-modal="true" aria-labelledby="cardCapTitle">
    <div class="modal-container">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="cardCapTitle">Müşteri Kartvizit Bilgisi Al</h3>
          <p class="modal-subtitle">Bilgileri içeri aktarmak için bir okuma yöntemi seçin</p>
        </div>
        <button class="modal-close-btn" data-close="cardCaptureModal">&times;</button>
      </div>
      <div class="modal-body">
        
        <div class="card-capture-options">
          <!-- NFC ile Oku -->
          <button class="capture-opt-card" id="btnCaptureNfc">
            <div class="capture-opt-icon nfc-icon-bg">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8.32a7.43 7.43 0 0 1 0 7.36"/>
                <path d="M9.46 6.21a11.76 11.76 0 0 1 0 11.58"/>
                <path d="M12.91 4.1a15.91 15.91 0 0 1 0 15.8"/>
                <path d="M16.37 2a20.16 20.16 0 0 1 0 20"/>
              </svg>
            </div>
            <div class="capture-opt-text">
              <h4>NFC ile Oku</h4>
              <p>Müşterinin dijital kartını telefonun arkasına dokundurun.</p>
            </div>
            <span class="capture-arrow">➔</span>
          </button>

          <!-- QR Kod Oku -->
          <button class="capture-opt-card" id="btnCaptureQr">
            <div class="capture-opt-icon qr-icon-bg">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
                <line x1="18" y1="7" x2="18.01" y2="7"/>
                <line x1="7" y1="18" x2="7.01" y2="18"/>
                <line x1="18" y1="18" x2="18.01" y2="18"/>
              </svg>
            </div>
            <div class="capture-opt-text">
              <h4>QR Kod Oku</h4>
              <p>Kameranızı müşterinin kartvizit QR koduna doğrultun.</p>
            </div>
            <span class="capture-arrow">➔</span>
          </button>

          <!-- OCR ile Oku -->
          <button class="capture-opt-card" id="btnCaptureOcr">
            <div class="capture-opt-icon ocr-icon-bg">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                <circle cx="12" cy="13" r="4"/>
              </svg>
            </div>
            <div class="capture-opt-text">
              <h4>OCR ile Oku</h4>
              <p>Fiziksel kağıt kartviziti tarayıp metinleri otomatik kaydedin.</p>
            </div>
            <span class="capture-arrow">➔</span>
          </button>

          <!-- Manuel Ekle -->
          <button class="capture-opt-card" id="btnCaptureManual">
            <div class="capture-opt-icon manual-icon-bg">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </div>
            <div class="capture-opt-text">
              <h4>Manuel Ekle</h4>
              <p>Müşteri adını, şirketini ve iletişim bilgilerini elle girin.</p>
            </div>
            <span class="capture-arrow">➔</span>
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- QR Kod Modalı (Personelin Müşteriye Gösterdiği QR) -->
  <div class="modal-overlay" id="qrModal" role="dialog" aria-modal="true" aria-labelledby="qrModalTitle">
    <div class="modal-container">
      <div class="modal-header">
        <h3 class="modal-title" id="qrModalTitle">MonaCard Karekod</h3>
        <button class="modal-close-btn" data-close="qrModal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <p class="modal-desc">Müşteriniz kamerasını bu koda doğrulttuğunda doğrudan kartvizit profilinize ulaşır.</p>
        <div class="qr-box" id="qrCodeContainer"></div>
        <div class="nfc-badge">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2"><path d="M6 8.32a7.43 7.43 0 0 1 0 7.36"/><path d="M9.46 6.21a11.76 11.76 0 0 1 0 11.58"/><path d="M12.91 4.1a15.91 15.91 0 0 1 0 15.8"/><path d="M16.37 2a20.16 20.16 0 0 1 0 20"/></svg>
          <span>NFC &amp; QR MonaCard Desteği</span>
        </div>
        <div class="modal-btn-row">
          <button class="btn-outline" id="btnDownloadQr">QR İndir</button>
          <button class="btn-primary" id="btnCopyProfileUrl">Bağlantıyı Kopyala</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Paylaş Modalı -->
  <div class="modal-overlay" id="shareModal" role="dialog" aria-modal="true" aria-labelledby="shareModalTitle">
    <div class="modal-container">
      <div class="modal-header">
        <h3 class="modal-title" id="shareModalTitle">Kartviziti Paylaş</h3>
        <button class="modal-close-btn" data-close="shareModal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="share-options-grid">
          <button class="share-opt-btn" id="shareWhatsApp">
            <div class="share-opt-icon whatsapp-bg">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </div>
            <span>WhatsApp</span>
          </button>
          <button class="share-opt-btn" id="shareTelegram">
            <div class="share-opt-icon telegram-bg">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </div>
            <span>Telegram</span>
          </button>
          <button class="share-opt-btn" id="shareLinkedIn">
            <div class="share-opt-icon linkedin-bg">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
            </div>
            <span>LinkedIn</span>
          </button>
          <button class="share-opt-btn" id="shareCopyLink">
            <div class="share-opt-icon copy-bg">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            </div>
            <span>Kopyala</span>
          </button>
        </div>
        <div class="copy-link-input-group">
          <input type="text" readonly id="profileLinkInput" value="https://monacard.com/muhiddinoktem" class="link-input">
          <button class="btn-copy" id="btnCopyInline">Kopyala</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Yorum Bırak Modalı -->
  <div class="modal-overlay" id="reviewModal" role="dialog" aria-modal="true" aria-labelledby="reviewModalTitle">
    <div class="modal-container">
      <div class="modal-header">
        <h3 class="modal-title" id="reviewModalTitle">Google'da Değerlendir</h3>
        <button class="modal-close-btn" data-close="reviewModal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <p class="modal-desc">Muhiddin Öktem ve Vedubox ile olan deneyiminizi değerlendirin.</p>
        <div class="interactive-stars" id="starRatingSelector">
          <button type="button" class="star-rating-btn active" data-score="1" aria-label="1 Yıldız">
            <svg class="star-svg" width="36" height="36" viewBox="0 0 24 24">
              <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46-5.8-3.05-5.8 3.05 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
            </svg>
          </button>
          <button type="button" class="star-rating-btn active" data-score="2" aria-label="2 Yıldız">
            <svg class="star-svg" width="36" height="36" viewBox="0 0 24 24">
              <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46-5.8-3.05-5.8 3.05 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
            </svg>
          </button>
          <button type="button" class="star-rating-btn active" data-score="3" aria-label="3 Yıldız">
            <svg class="star-svg" width="36" height="36" viewBox="0 0 24 24">
              <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46-5.8-3.05-5.8 3.05 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
            </svg>
          </button>
          <button type="button" class="star-rating-btn active" data-score="4" aria-label="4 Yıldız">
            <svg class="star-svg" width="36" height="36" viewBox="0 0 24 24">
              <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46-5.8-3.05-5.8 3.05 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
            </svg>
          </button>
          <button type="button" class="star-rating-btn active" data-score="5" aria-label="5 Yıldız">
            <svg class="star-svg" width="36" height="36" viewBox="0 0 24 24">
              <path d="M12 2.5l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46-5.8-3.05-5.8 3.05 1.11-6.46-4.7-4.58 6.49-.94L12 2.5z"/>
            </svg>
          </button>
        </div>
        <textarea id="reviewComment" class="review-textarea" rows="3" placeholder="Yorumunuzu ve deneyiminizi yazın..."></textarea>
        <div class="modal-btn-row">
          <button class="btn-outline" data-close="reviewModal">Vazgeç</button>
          <button class="btn-primary" id="btnSubmitReview">Yorumu Yayınla</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Hızlı Planlama & Hatırlatıcı Modalı (Tab Menülü) -->
  <div class="modal-overlay" id="quickScheduleModal" role="dialog" aria-modal="true" aria-labelledby="quickSchedModalTitle">
    <div class="modal-container" style="max-width: 480px; max-height: 92vh; overflow-y: auto;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="quickSchedModalTitle">📅 Yeni Planlama Ekle</h3>
          <p class="modal-subtitle" id="quickSchedModalSubtitle">Seçilen gün ve saat dilimi için etkinlik oluşturun</p>
        </div>
        <button class="modal-close-btn" data-close="quickScheduleModal">&times;</button>
      </div>

      <!-- Üst Tab Menüsü: Toplantı vs Hatırlatıcı -->
      <div class="quick-sched-tab-nav" style="display: flex; gap: 6px; margin: 12px 0 20px; background: #F1F5F9; padding: 4px; border-radius: 12px;">
        <button type="button" class="quick-sched-tab-btn active" id="tabQuickMeeting" style="flex: 1; padding: 10px 12px; font-size: 13px; font-weight: 700; border-radius: 9px; border: none; background: #FFFFFF; color: #1E293B; box-shadow: 0 2px 8px rgba(0,0,0,0.06); cursor: pointer; transition: all 0.2s ease;">
          📅 Toplantı Planla
        </button>
        <button type="button" class="quick-sched-tab-btn" id="tabQuickReminder" style="flex: 1; padding: 10px 12px; font-size: 13px; font-weight: 700; border-radius: 9px; border: none; background: transparent; color: #64748B; cursor: pointer; transition: all 0.2s ease;">
          ⏰ Hatırlatıcı Ekle
        </button>
      </div>

      <div class="modal-body" style="padding: 0;">
        <!-- TAB 1: TOPLANTI PANELİ -->
        <div class="quick-sched-panel" id="panelQuickMeeting">
          <form id="formQuickMeeting">
            <div class="form-group mb-4">
              <label class="modal-edit-label" id="lblQuickMeetTitle">📝 Toplantı Başlığı / Konusu</label>
              <input type="text" id="quickMeetTitle" class="modal-input-field" placeholder="Örn: Sprint Planlaması, Müşteri Demo Sunumu" required>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="modal-edit-label" id="lblQuickMeetDate">📅 Tarih</label>
                <input type="date" id="quickMeetDate" class="modal-input-field" required>
              </div>
              <div class="form-group">
                <label class="modal-edit-label" id="lblQuickMeetTime">⏰ Saat</label>
                <input type="time" id="quickMeetTime" class="modal-input-field" required>
              </div>
            </div>

            <div class="form-group mb-4">
              <label class="modal-edit-label" id="lblQuickMeetType">🌐 Toplantı Ortamı</label>
              <select id="quickMeetType" class="modal-select-field">
                <option value="Google Meet">Google Meet (Online)</option>
                <option value="Zoom Meet">Zoom Meet (Online)</option>
                <option value="Microsoft Teams">Microsoft Teams (Online)</option>
                <option value="Bizim Ofis">Bizim Ofis</option>
                <option value="Müşteri Ofisi">Müşteri Ofisi</option>
              </select>
            </div>

            <div class="form-group mb-4">
              <label class="modal-edit-label" id="lblQuickMeetCust">👥 Katılımcı / Müşteri</label>
              <select id="quickMeetCustomer" class="modal-select-field">
                <option value="">-- Müşteri / Katılımcı Seçin --</option>
              </select>
            </div>

            <div class="form-group mb-4">
              <label class="modal-edit-label" id="lblQuickMeetNote">💬 Toplantı Notu (İsteğe Bağlı)</label>
              <textarea id="quickMeetNote" class="modal-input-field" rows="2" placeholder="Gündem maddeleri ve notlar..."></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
              <button type="button" class="btn-outline btn-sm btn-quick-sched-cancel" data-close="quickScheduleModal">İptal</button>
              <button type="submit" class="btn-primary btn-sm" id="btnSubmitQuickMeeting" style="padding: 10px 18px; font-weight: 700;">
                <span>📅 Toplantıyı Takvime Ekle</span>
              </button>
            </div>
          </form>
        </div>

        <!-- TAB 2: HATIRLATICI PANELİ -->
        <div class="quick-sched-panel hidden" id="panelQuickReminder">
          <form id="formQuickReminder">
            <div class="form-group mb-4">
              <label class="modal-edit-label" id="lblQuickRemTitle">⏰ Hatırlatıcı / Görev Başlığı</label>
              <input type="text" id="quickRemTitle" class="modal-input-field" placeholder="Örn: Teklif sözleşmesini kontrol et, Müşteriyi ara" required>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="modal-edit-label" id="lblQuickRemDate">📅 Tarih</label>
                <input type="date" id="quickRemDate" class="modal-input-field" required>
              </div>
              <div class="form-group">
                <label class="modal-edit-label" id="lblQuickRemTime">⏰ Saat</label>
                <input type="time" id="quickRemTime" class="modal-input-field" value="10:00">
              </div>
            </div>

            <div class="form-group mb-4">
              <label class="modal-edit-label" id="lblQuickRemPriority">⚡ Öncelik Seviyesi</label>
              <select id="quickRemPriority" class="modal-select-field">
                <option value="medium">Normal Öncelik</option>
                <option value="high">Acil / Yüksek Öncelik</option>
                <option value="low">Düşük Öncelik</option>
              </select>
            </div>

            <div class="form-group mb-4">
              <label class="modal-edit-label" id="lblQuickRemDesc">📝 Ek Açıklama (İsteğe Bağlı)</label>
              <textarea id="quickRemDesc" class="modal-input-field" rows="2" placeholder="Görevin detayları ve notlar..."></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
              <button type="button" class="btn-outline btn-sm btn-quick-sched-cancel" data-close="quickScheduleModal">İptal</button>
              <button type="submit" class="btn-primary btn-sm" id="btnSubmitQuickReminder" style="padding: 10px 18px; font-weight: 700; background: #3B82F6;">
                <span>⏰ Hatırlatıcıyı Kaydet</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Toplantı Detay & Katılımcı Hatırlatıcı Modalı -->
  <div class="modal-overlay" id="meetingDetailModal" role="dialog" aria-modal="true" aria-labelledby="meetDetTitle">
    <div class="modal-container" style="max-width: 460px; max-height: 92vh; overflow-y: auto;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="meetDetTitle">Toplantı Detayı &amp; Düzenle</h3>
          <p class="modal-subtitle">Tarih, katılımcı yönetimi ve durum güncelleme</p>
        </div>
        <button class="modal-close-btn" data-close="meetingDetailModal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="meeting-detail-card-box">
          <!-- Platform Rozeti & Başlık Düzenleme (Kusursuz Aynı Hizada) -->
          <div class="meeting-detail-header-row">
            <div class="meeting-title-row-aligned">
              <div class="meeting-platform-logo-box" id="meetDetPlatformLogo"></div>
              <input type="text" id="editMeetTitle" class="modal-input-title" placeholder="Toplantı Konusu / Başlığı" required>
            </div>
            <div class="flex items-center gap-2 mt-2" id="meetDetStatusBadgeWrapper">
              <span class="meeting-status-badge" id="meetDetStatusBadge">Planlandı</span>
            </div>
          </div>
          
          <!-- Düzenleme Alanları -->
          <div class="meeting-edit-fields-group mt-3">
            <!-- Tarih ve Saat -->
            <div class="form-row">
              <div class="form-group" style="flex: 1;">
                <label class="modal-edit-label">📅 Tarih</label>
                <input type="date" id="editMeetDate" class="modal-input-field" required>
              </div>
              <div class="form-group" style="flex: 1;">
                <label class="modal-edit-label">⏰ Saat</label>
                <input type="time" id="editMeetTime" class="modal-input-field" required>
              </div>
            </div>

            <!-- Toplantı Ortamı -->
            <div class="form-group mb-2">
              <label class="modal-edit-label">🌐 Toplantı Ortamı</label>
              <select id="editMeetType" class="modal-select-field">
                <option value="Google Meet">Google Meet (Online)</option>
                <option value="Zoom Meet">Zoom Meet (Online)</option>
                <option value="Microsoft Teams">Microsoft Teams (Online)</option>
                <option value="Bizim Ofis">Bizim Ofis</option>
                <option value="Müşteri Ofisi">Müşteri Ofisi</option>
              </select>
            </div>

            <!-- Katılımcılar Çoklu Seçim Dropdown (Select ile) -->
            <div class="form-group mb-3">
              <label class="modal-edit-label">👥 Katılımcılar (Çoklu Seçim)</label>
              <div class="customer-multiselect-container" id="editMeetParticipantsContainer">
                <div class="multiselect-trigger-box" id="editMeetParticipantsToggle">
                  <span id="editMeetParticipantsPlaceholder" class="placeholder-text">Katılımcı seçin...</span>
                  <span class="multiselect-count-badge hidden" id="editMeetSelectedCount">0</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="multiselect-dropdown-menu hidden" id="editMeetParticipantsDropdown">
                  <!-- Şirketlere göre gruplanmış checkbox listesi -->
                </div>
                <div class="selected-participant-chips" id="editMeetSelectedChips"></div>
              </div>
            </div>

            <!-- Değişiklikleri Kaydet Butonu -->
            <button type="button" class="btn-save-edit-meeting" id="btnSaveMeetingChanges">
              <span>💾 Değişiklikleri Kaydet</span>
            </button>
          </div>
        </div>

        <!-- Alt Butonlar: Katılımcılara Hatırlat, İptal Et & Kapat -->
        <div class="meeting-detail-actions-row">
          <button type="button" class="btn-primary btn-meeting-action" id="btnSendMeetingReminderMail">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span>Katılımcılara Hatırlat</span>
          </button>
          
          <button type="button" class="btn-danger-soft btn-meeting-action" id="btnCancelMeetingModal" title="Toplantıyı İptal Et ve Katılımcılara E-Posta Gönder">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="15" y1="9" x2="9" y2="15"></line>
              <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span>Toplantıyı İptal Et</span>
          </button>

          <button type="button" class="btn-outline btn-meeting-action" data-close="meetingDetailModal">Kapat</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Hatırlatıcı Detay Modalı -->
  <div class="modal-overlay" id="reminderDetailModal" role="dialog" aria-modal="true" aria-labelledby="remDetTitle">
    <div class="modal-container">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="remDetTitle">Hatırlatıcı Detayı</h3>
          <p class="modal-subtitle">Ajanda görevi ve takip durumu</p>
        </div>
        <button class="modal-close-btn" data-close="reminderDetailModal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="reminder-detail-box">
          <div class="reminder-detail-tags-row">
            <span class="rem-status-pill" id="remDetStatusBadge">⏳ Bekliyor</span>
            <span class="reminder-modal-priority-badge" id="remDetPriorityBadge">Düşük Öncelik</span>
          </div>

          <div class="reminder-detail-section">
            <span class="reminder-section-label">Görev Açıklaması:</span>
            <h4 class="reminder-detail-headline" id="remDetHeadline">Hatırlatıcı Başlığı</h4>
          </div>

          <div class="reminder-detail-section reminder-date-section">
            <span class="reminder-section-label">📅 Planlanan Tarih:</span>
            <span class="detail-info-value font-bold" id="remDetDate">-</span>
          </div>
        </div>
        <div class="modal-btn-row mt-4">
          <button class="btn-primary full-width flex items-center justify-center gap-2" id="btnToggleReminderModal">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>Tamamlandı</span>
          </button>
          <button class="btn-outline full-width" data-close="reminderDetailModal">Kapat</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Yeni Müşteri Ekleme Modalı (Dashboard & CRM) -->
  <div class="modal-overlay" id="modalAddCustomer" role="dialog" aria-modal="true" aria-labelledby="addCustModalTitle">
    <div class="modal-container" style="max-width: 480px;">
      <div class="modal-header">
        <div>
          <h3 class="modal-title" id="addCustModalTitle">Yeni Müşteri Ekle</h3>
          <p class="modal-subtitle">Müşteri bilgilerini ve durumunu CRM havuzuna kaydedin.</p>
        </div>
        <button class="modal-close-btn" data-close="modalAddCustomer">&times;</button>
      </div>
      <div class="modal-body">
        <form id="formAddCustomerModal" class="styled-form">
          <div class="form-group">
            <label for="modalCustName">Müşteri Ad Soyad *</label>
            <input type="text" id="modalCustName" required placeholder="Örn: Zeynep Kaya">
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="modalCustCompany">Firma / Şirket Adı</label>
              <input type="text" id="modalCustCompany" placeholder="Örn: Kaya Mimarlık">
            </div>
            <div class="form-group">
              <label for="modalCustTitle">Ünvan / Pozisyon</label>
              <input type="text" id="modalCustTitle" placeholder="Örn: Proje Direktörü">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="modalCustPhone">Telefon Numarası *</label>
              <input type="tel" id="modalCustPhone" required placeholder="+90 5XX XXX XX XX">
            </div>
            <div class="form-group">
              <label for="modalCustEmail">E-Posta Adresi</label>
              <input type="email" id="modalCustEmail" placeholder="zeynep@kayamimarlik.com">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="modalCustStage">Müşteri Aşaması *</label>
              <select id="modalCustStage" class="styled-select" style="width:100%; height:44px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:0 12px; font-size:13.5px;">
                <option value="hot">🔥 Sıcak Müşteri (Yüksek İlgi)</option>
                <option value="warm" selected>⚡ Ilık Müşteri (Takipte)</option>
                <option value="cold">❄️ Soğuk Müşteri (Yeni Tanışma)</option>
              </select>
            </div>
            <div class="form-group">
              <label for="modalCustStaff">İlgilenen Personel</label>
              <select id="modalCustStaff" class="styled-select" style="width:100%; height:44px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:0 12px; font-size:13.5px;">
                <!-- Dinamik doldurulur -->
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="modalCustNote">İlk Görüşme Notu / Talep</label>
            <textarea id="modalCustNote" rows="2" placeholder="Görüşme detayları, teklif talebi vb." style="width:100%; border:1px solid #E2E8F0; border-radius:10px; padding:10px; font-size:13.5px;"></textarea>
          </div>
          <div class="modal-btn-row mt-4">
            <button type="button" class="btn-outline" data-close="modalAddCustomer">İptal</button>
            <button type="submit" class="btn-primary" style="background: #00A86B; color: #FFFFFF; font-weight: 700; border-radius: 12px; box-shadow: 0 4px 14px rgba(0, 168, 107, 0.35);">Müşteriyi Kaydet</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Toast Container -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- SheetJS for Excel (.xlsx, .xls, .csv) Import -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

  <!-- Laravel Initial Database Data -->
  <script>
    window.__INITIAL_DATA__ = {
      currentUser: @json($currentUser ?? null),
      company: @json($company ?? null),
      card: @json($card ?? null),
      products: @json($products ?? []),
      staffMembers: @json($staffMembers ?? []),
      customers: @json($customers ?? []),
      meetings: @json($meetings ?? []),
      reminders: @json($reminders ?? []),
      allCompanies: @json($allCompanies ?? []),
      demoRequests: @json($demoRequests ?? []),
      pricingTiers: @json($pricingTiers ?? [])
    };
  </script>

  <!-- JavaScript -->
  <script src="{{ asset('app.js') }}?v={{ file_exists(public_path('app.js')) ? filemtime(public_path('app.js')) : time() }}"></script>
</body>
</html>
