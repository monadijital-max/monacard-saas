<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <title>Ücretsiz Firma Hesabı Aç | MonaCard</title>
  <meta name="description" content="Firmanızı MonaCard platformuna kaydedin, akıllı dijital kartvizit ve CRM ekosisteminizi başlatın.">
  <meta name="theme-color" content="#00A86B">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle at 50% 20%, #0c2b20 0%, #071510 50%, #040907 100%);
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      padding: 24px 16px;
      color: #F8FAFC;
      position: relative;
      overflow-x: hidden;
    }

    .bg-glow-1 {
      position: absolute;
      top: 10%;
      left: 50%;
      transform: translateX(-50%);
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(0, 168, 107, 0.2) 0%, rgba(0, 168, 107, 0) 70%);
      filter: blur(60px);
      pointer-events: none;
      z-index: 0;
    }

    .register-container {
      width: 100%;
      max-width: 480px;
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 24px;
      padding: 36px 32px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 35px rgba(0, 168, 107, 0.12);
      position: relative;
      z-index: 1;
    }

    .brand-header {
      text-align: center;
      margin-bottom: 24px;
    }

    .brand-logo-wrap {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 52px;
      height: 52px;
      background: linear-gradient(135deg, rgba(0, 168, 107, 0.2) 0%, rgba(0, 168, 107, 0.05) 100%);
      border: 1px solid rgba(0, 168, 107, 0.4);
      border-radius: 14px;
      margin-bottom: 12px;
      box-shadow: 0 0 20px rgba(0, 168, 107, 0.25);
    }

    .brand-logo-wrap svg {
      width: 28px;
      height: 28px;
    }

    .brand-title {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 22px;
      font-weight: 700;
      color: #FFFFFF;
      letter-spacing: -0.5px;
    }

    .brand-title span {
      color: #00A86B;
    }

    .brand-subtitle {
      font-size: 13px;
      color: #94A3B8;
      margin-top: 5px;
      font-weight: 500;
    }

    /* Alert / Error Box */
    .alert-box {
      display: none;
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #FCA5A5;
      padding: 12px 16px;
      border-radius: 12px;
      font-size: 13px;
      margin-bottom: 18px;
    }

    .alert-box.show {
      display: block;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      color: #E2E8F0;
      margin-bottom: 6px;
    }

    .form-input, .form-select {
      width: 100%;
      height: 44px;
      background: rgba(2, 6, 23, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      padding: 0 14px;
      color: #FFFFFF;
      font-size: 14px;
      font-family: inherit;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-input::placeholder {
      color: #64748B;
    }

    .form-input:focus, .form-select:focus {
      border-color: #00A86B;
      background: rgba(2, 6, 23, 0.85);
      box-shadow: 0 0 0 3px rgba(0, 168, 107, 0.18);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    @media (max-width: 480px) {
      .form-row {
        grid-template-columns: 1fr;
      }
    }

    .btn-submit {
      width: 100%;
      height: 48px;
      background: linear-gradient(135deg, #00A86B 0%, #008F5A 100%);
      border: none;
      border-radius: 13px;
      color: #FFFFFF;
      font-size: 14.5px;
      font-weight: 700;
      font-family: inherit;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 20px;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 18px rgba(0, 168, 107, 0.4);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 22px rgba(0, 168, 107, 0.55);
      background: linear-gradient(135deg, #00B875 0%, #009960 100%);
    }

    .btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .login-footer {
      text-align: center;
      margin-top: 22px;
      font-size: 13px;
      color: #94A3B8;
    }

    .login-footer a {
      color: #00A86B;
      text-decoration: none;
      font-weight: 700;
      margin-left: 4px;
    }

    .login-footer a:hover {
      text-decoration: underline;
    }

    .plan-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(0, 168, 107, 0.15);
      border: 1px solid rgba(0, 168, 107, 0.3);
      color: #00A86B;
      font-size: 11.5px;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 20px;
      margin-bottom: 12px;
    }
  </style>
</head>
<body>

  <div class="bg-glow-1"></div>

  <div class="register-container">
    <div class="brand-header">
      <div class="brand-logo-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="16" rx="3"></rect>
          <line x1="7" y1="8" x2="17" y2="8"></line>
          <line x1="7" y1="12" x2="13" y2="12"></line>
          <circle cx="16" cy="14" r="2"></circle>
        </svg>
      </div>
      <h1 class="brand-title">Mona<span>Card</span></h1>
      <p class="brand-subtitle">Kurumsal Dijital Kartvizit & CRM Ekosistemi</p>
    </div>

    <!-- Alert Box -->
    <div id="registerAlert" class="alert-box">
      <span>Kayıt sırasında bir hata oluştu.</span>
    </div>

    <form id="registerForm">
      <div style="text-align:center;">
        <span class="plan-badge">✨ 14 Gün Ücretsiz Deneme (10 Personel Lisansı)</span>
      </div>

      <div class="form-group">
        <label class="form-label" for="companyName">Firma / Şirket Adı *</label>
        <input type="text" id="companyName" class="form-input" placeholder="Örn: ABC Teknoloji Ltd. Şti." required autofocus>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="fullName">Yönetici Ad Soyad *</label>
          <input type="text" id="fullName" class="form-input" placeholder="Örn: Hakan Yavuz" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="title">Yönetici Pozisyonu / Ünvanı</label>
          <input type="text" id="title" class="form-input" placeholder="Örn: Kurucu & Yönetici">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="phone">İletişim Telefonu</label>
          <input type="tel" id="phone" class="form-input" placeholder="+90 5XX XXX XX XX">
        </div>
        <div class="form-group">
          <label class="form-label" for="email">Kurumsal E-Posta *</label>
          <input type="email" id="email" class="form-input" placeholder="yonetici@firmaniz.com" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Yönetici Giriş Şifresi *</label>
        <input type="password" id="password" class="form-input" placeholder="En az 6 karakter" minlength="6" required>
      </div>

      <button type="submit" id="btnSubmit" class="btn-submit">
        <span>Firma Hesabını Oluştur</span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </form>

    <div class="login-footer">
      <span>Zaten bir şirket hesabınız var mı?</span>
      <a href="{{ route('login') }}">Giriş Yap</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('registerForm');
      const companyNameInput = document.getElementById('companyName');
      const fullNameInput = document.getElementById('fullName');
      const titleInput = document.getElementById('title');
      const phoneInput = document.getElementById('phone');
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const btnSubmit = document.getElementById('btnSubmit');
      const alertBox = document.getElementById('registerAlert');

      function showError(msg) {
        alertBox.textContent = msg;
        alertBox.classList.add('show');
      }

      function hideError() {
        alertBox.classList.remove('show');
      }

      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideError();

        const company_name = companyNameInput.value.trim();
        const name = fullNameInput.value.trim();
        const title = titleInput.value.trim();
        const phone = phoneInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        if (!company_name || !name || !email || !password) {
          showError('Lütfen zorunlu alanları doldurun.');
          return;
        }

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span>Hesap Oluşturuluyor...</span>';

        try {
          const res = await fetch('/api/auth/register', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              company_name,
              name,
              title,
              phone,
              email,
              password,
              sector: ''
            })
          });

          const data = await res.json();

          if (res.ok && data.status === 'success') {
            const token = data.data.token;
            const user = data.data.user;

            localStorage.setItem('monacard_token', token);
            localStorage.setItem('monacard_user', JSON.stringify(user));
            localStorage.setItem('monacard_role', 'admin');

            // Clear old demo tenant cache so new company starts completely clean
            localStorage.removeItem('monacard_admin_staff');
            localStorage.removeItem('monacard_admin_settings');
            localStorage.removeItem('monacard_crm_customers');
            localStorage.removeItem('monacard_meetings');
            localStorage.removeItem('monacard_reminders');
            localStorage.removeItem('monacard_staff_profile');

            window.location.href = '/?role=admin';
          } else {
            const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Kayıt sırasında bir hata oluştu.');
            showError(errorMsg);
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<span>Firma Hesabını Oluştur</span>';
          }
        } catch (err) {
          showError('Sunucu bağlantısı kurulamadı. Lütfen internetinizi veya sunucu durumunu kontrol edin.');
          btnSubmit.disabled = false;
          btnSubmit.innerHTML = '<span>Firma Hesabını Oluştur</span>';
        }
      });
    });
  </script>
</body>
</html>
