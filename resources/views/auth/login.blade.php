<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <title>Giriş Yap | MonaCard</title>
  <meta name="description" content="MonaCard kurumsal portalına giriş yapın.">
  <meta name="theme-color" content="#00A86B">
  
  <!-- Google Fonts: Space Grotesk & Plus Jakarta Sans -->
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

    /* Ambient background glow */
    .bg-glow-1 {
      position: absolute;
      top: 10%;
      left: 50%;
      transform: translateX(-50%);
      width: 450px;
      height: 450px;
      background: radial-gradient(circle, rgba(0, 168, 107, 0.22) 0%, rgba(0, 168, 107, 0) 70%);
      filter: blur(60px);
      pointer-events: none;
      z-index: 0;
    }

    .login-container {
      width: 100%;
      max-width: 440px;
      background: rgba(15, 23, 42, 0.75);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 24px;
      padding: 38px 32px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 35px rgba(0, 168, 107, 0.12);
      position: relative;
      z-index: 1;
    }

    .brand-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .brand-logo-wrap {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 56px;
      height: 56px;
      background: linear-gradient(135deg, rgba(0, 168, 107, 0.2) 0%, rgba(0, 168, 107, 0.05) 100%);
      border: 1px solid rgba(0, 168, 107, 0.4);
      border-radius: 16px;
      margin-bottom: 16px;
      box-shadow: 0 0 20px rgba(0, 168, 107, 0.25);
    }

    .brand-logo-wrap svg {
      width: 30px;
      height: 30px;
    }

    .brand-title {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 24px;
      font-weight: 700;
      color: #FFFFFF;
      letter-spacing: -0.5px;
    }

    .brand-title span {
      color: #00A86B;
    }

    .brand-subtitle {
      font-size: 13.5px;
      color: #94A3B8;
      margin-top: 6px;
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
      margin-bottom: 20px;
      align-items: center;
      gap: 10px;
    }

    .alert-box.show {
      display: flex;
    }

    /* Form controls */
    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #E2E8F0;
      margin-bottom: 8px;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #64748B;
      transition: color 0.2s ease;
      pointer-events: none;
    }

    .form-input {
      width: 100%;
      height: 48px;
      background: rgba(2, 6, 23, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 14px;
      padding: 0 16px 0 44px;
      color: #FFFFFF;
      font-size: 14.5px;
      font-family: inherit;
      outline: none;
      transition: all 0.2s ease;
    }

    .form-input::placeholder {
      color: #64748B;
    }

    .form-input:focus {
      border-color: #00A86B;
      background: rgba(2, 6, 23, 0.85);
      box-shadow: 0 0 0 4px rgba(0, 168, 107, 0.18);
    }

    .input-wrap:focus-within svg {
      color: #00A86B;
    }

    .form-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
      font-size: 13px;
    }

    .remember-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      color: #94A3B8;
      user-select: none;
    }

    .remember-wrap input[type="checkbox"] {
      width: 16px;
      height: 16px;
      accent-color: #00A86B;
      border-radius: 4px;
      cursor: pointer;
    }

    .forgot-link {
      color: #00A86B;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .forgot-link:hover {
      color: #34D399;
      text-decoration: underline;
    }

    /* Submit Button */
    .btn-submit {
      width: 100%;
      height: 50px;
      background: linear-gradient(135deg, #00A86B 0%, #008F5A 100%);
      border: none;
      border-radius: 14px;
      color: #FFFFFF;
      font-size: 15px;
      font-weight: 700;
      font-family: inherit;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 20px rgba(0, 168, 107, 0.4);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(0, 168, 107, 0.55);
      background: linear-gradient(135deg, #00B875 0%, #009960 100%);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* Demo Quick Logins */
    .demo-divider {
      display: flex;
      align-items: center;
      margin: 28px 0 20px 0;
      color: #64748B;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .demo-divider::before,
    .demo-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: rgba(255, 255, 255, 0.1);
    }

    .demo-divider span {
      padding: 0 12px;
    }

    .quick-roles {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .quick-role-btn {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 10px 12px;
      color: #E2E8F0;
      font-size: 12.5px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 2px;
      text-align: left;
      transition: all 0.2s ease;
    }

    .quick-role-btn:hover {
      background: rgba(0, 168, 107, 0.12);
      border-color: rgba(0, 168, 107, 0.4);
      color: #FFFFFF;
      transform: translateY(-1px);
    }

    .quick-role-btn .role-title {
      color: #00A86B;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 700;
    }

    .quick-role-btn.full-width {
      grid-column: span 2;
    }

    .register-footer {
      text-align: center;
      margin-top: 26px;
      font-size: 13.5px;
      color: #94A3B8;
    }

    .register-footer a {
      color: #00A86B;
      text-decoration: none;
      font-weight: 700;
      margin-left: 5px;
    }

    .register-footer a:hover {
      text-decoration: underline;
    }

    .back-home-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      color: #64748B;
      text-decoration: none;
      margin-top: 20px;
      transition: color 0.2s;
    }

    .back-home-link:hover {
      color: #94A3B8;
    }
  </style>
</head>
<body>

  <div class="bg-glow-1"></div>

  <div class="login-container">
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
      <p class="brand-subtitle">Kurumsal Dijital Kartvizit & CRM Platformu</p>
    </div>

    <!-- Alert Box -->
    <div id="loginAlert" class="alert-box">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span id="alertMessage">Giriş bilgileri hatalı.</span>
    </div>

    <!-- Login Form -->
    <form id="loginForm">
      <div class="form-group">
        <label class="form-label" for="email">E-Posta Adresi</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <input type="email" id="email" class="form-input" placeholder="ornek@firmaniz.com" required autofocus>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Şifre</label>
        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input type="password" id="password" class="form-input" placeholder="••••••••" required>
        </div>
      </div>

      <div class="form-meta">
        <label class="remember-wrap">
          <input type="checkbox" id="remember" checked>
          <span>Beni Hatırla</span>
        </label>
        <a href="javascript:void(0)" onclick="alert('Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.')" class="forgot-link">Şifremi Unuttum</a>
      </div>

      <button type="submit" id="btnSubmit" class="btn-submit">
        <span>Giriş Yap</span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </form>

    <!-- Register Link -->
    <div class="register-footer">
      <span>Henüz bir şirket hesabınız yok mu?</span>
      <a href="{{ route('register') }}">Ücretsiz Kayıt Ol</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('loginForm');
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const btnSubmit = document.getElementById('btnSubmit');
      const alertBox = document.getElementById('loginAlert');
      const alertMessage = document.getElementById('alertMessage');

      function showError(msg) {
        alertMessage.textContent = msg;
        alertBox.classList.add('show');
      }

      function hideError() {
        alertBox.classList.remove('show');
      }

      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        hideError();

        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        if (!email || !password) {
          showError('Lütfen e-posta ve şifrenizi girin.');
          return;
        }

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span>Giriş Yapılıyor...</span>';

        try {
          const res = await fetch('/api/auth/login', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
          });

          const data = await res.json();

          if (res.ok && data.status === 'success') {
            const token = data.data.token;
            const user = data.data.user;

            localStorage.setItem('monacard_token', token);
            localStorage.setItem('monacard_user', JSON.stringify(user));

            // Clean legacy unscoped keys
            const legacyKeys = [
              'monacard_admin_staff', 'monacard_admin_settings', 'monacard_crm_customers',
              'monacard_meetings', 'monacard_reminders', 'monacard_staff_profile'
            ];
            legacyKeys.forEach(k => localStorage.removeItem(k));

            let targetRole = 'staff';
            if (user.role === 'superadmin') {
              targetRole = 'superadmin';
            } else if (user.role === 'company_admin') {
              targetRole = 'admin';
            }

            localStorage.setItem('monacard_role', targetRole);
            window.location.href = `/?role=${targetRole}`;
          } else {
            const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Geçersiz e-posta veya şifre.');
            showError(errorMsg);
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<span>Giriş Yap</span>';
          }
        } catch (err) {
          showError('Sunucu bağlantısı kurulamadı. Lütfen internetinizi veya sunucu durumunu kontrol edin.');
          btnSubmit.disabled = false;
          btnSubmit.innerHTML = '<span>Giriş Yap</span>';
        }
      });
    });
  </script>
</body>
</html>
