/**
 * MonaCard Digital Business Card Application
 * Roles: 
 * 1. Müşteri Ekranı (Public View - No bottom bar, no profile icon, no + button, clean card)
 * 2. Personel Paneli (Staff View - Page-based: Profil, Toplantı, Takvim, CRM + Voice Notes & HubSpot sync)
 */

document.addEventListener('DOMContentLoaded', () => {

  // =========================================================
  // BACKEND API CLIENT (Laravel 11 RESTful Integration)
  // =========================================================
  const MonaCardAPI = {
    baseUrl: '/api',
    async fetchCard(slug = 'muhiddin-oktem') {
      try {
        const res = await fetch(`${this.baseUrl}/cards/${slug}`);
        if (!res.ok) return null;
        const json = await res.json();
        return json.data;
      } catch (e) {
        return null;
      }
    },
    async saveCrmCustomer(customerData) {
      try {
        const res = await fetch(`${this.baseUrl}/staff/crm`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify(customerData)
        });
        return await res.json();
      } catch (e) {
        return null;
      }
    },
    async saveNote(customerId, noteData) {
      try {
        const res = await fetch(`${this.baseUrl}/staff/crm/${customerId}/note`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify(noteData)
        });
        return await res.json();
      } catch (e) {
        return null;
      }
    },
    async login(email, password) {
      try {
        const res = await fetch(`${this.baseUrl}/auth/login`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ email, password })
        });
        return await res.json();
      } catch (e) {
        return { status: 'error', message: 'API sunucusuna erişilemedi.' };
      }
    },
    async register(registerData) {
      try {
        const res = await fetch(`${this.baseUrl}/auth/register`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify(registerData)
        });
        return await res.json();
      } catch (e) {
        return { status: 'error', message: 'API sunucusuna erişilemedi.' };
      }
    }
  };

  // =========================================================
  // 0. BILINGUAL SYSTEM (i18n: TR / ENG)
  // =========================================================
  const i18n = {
    tr: {
      role_view: "Görünüm:",
      role_customer: "1. Müşteri Ekranı",
      role_staff: "2. Personel Paneli",
      role_admin: "3. Firma Yöneticisi",
      role_super: "4. Süper Admin (SaaS Sahibi)",

      btn_save_contact: "Rehbere Kaydet",
      btn_share: "Paylaş",
      btn_qr: "Karekod",
      btn_cancel: "Vazgeç",
      btn_save: "Kaydet",
      btn_close: "Kapat",
      btn_apply: "Uygula",
      btn_back_to_staff: "Personellere Dön",
      btn_set_target: "🎯 Hedef Belirle / Düzenle",
      btn_add_new_staff: "+ Yeni Personel Ekle",
      btn_add_new_client: "+ Yeni Müşteri Ekle",
      btn_create_meeting: "Toplantı Oluştur",
      btn_transfer_clients: "👥 Müşterileri Aktar",
      btn_cancel_card: "Bu Kartı İptal Et",
      btn_reactivate_card: "Kartı Yeniden Aktif Et",

      search_admin: "Personel, müşteri veya rapor ara...",
      search_super: "Firma adı, yetkili, demo talebi veya paket ara...",
      search_crm: "İsim, şirket veya telefon ile ara...",
      search_staff: "Personel ara (İsim, Ünvan, Departman)...",

      admin_greeting: "Hoş geldin,",
      admin_greeting_sub: "İşte şirketinizin ve ekibinizin bugünkü performans özeti.",
      live_crm_stream: "Canlı CRM & Görüşme Akışı",
      card_hot_clients: "Sıcak Müşteriler",
      card_warm_clients: "Ilık Müşteri",
      card_total_clients: "Toplam Müşteri",
      card_meeting_rate: "Görüşme Oranı",
      vs_last_week: "vs geçen hafta",
      in_negotiation: "Görüşme sürecinde",
      active_crm_portfolio: "Aktif CRM Portföyü",
      meeting_efficiency: "Görüşme Verimliliği",

      chart_weekly_progress: "Haftalık Görüşme & Müşteri İlerlemesi",
      chart_completed_meetings: "Tamamlanan Görüşmeler",
      chart_new_hot_clients: "Yeni Sıcak Müşteri",
      chart_client_distribution: "Müşteri Durum Dağılımı",

      top_performers_title: "En İyi Performans Gösteren Personeller",
      top_performers_sub: "Aylık en yüksek müşteri kazanımı ve görüşme performansı",
      metric_hot_meeting: "SICAK GÖRÜŞME",
      metric_records: "KAYIT",
      metric_people: "Kişi",

      schedule_timeline_title: "Görüşme & Toplantı Takvimi",
      select_day: "Günü Seçin:",

      th_staff_name: "Personel Adı / Ünvan",
      th_role_auth: "Rol & Yetki",
      th_total_clients: "Toplam Müşteri",
      th_hot_meetings: "Sıcak Görüşme",
      th_monthly_target: "Aylık Hedef",
      th_actions: "İşlemler",
      role_team_leader: "👑 Takım Lideri",
      role_staff_member: "Personel",
      btn_detail_perf: "Detay & Performans",

      chip_total_meetings: "🗣️ Toplam Görüşme",
      chip_hot_client: "🔥 Sıcak Müşteri",
      chip_warm_client: "⚡ Ilık Müşteri",
      chip_cold_client: "❄️ Soğuk Müşteri",
      chip_goal_complete: "🎯 Hedef Tamamlama",
      sub_completed: "%100 Tamamlandı",
      sub_hot_prob: "Yüksek Anlaşma İhtimali",
      sub_warm_stage: "Teklif Aşamasında",
      sub_cold_wait: "Takip Bekleyen",
      sub_meeting_goal: "Görüşme Hedefi",

      card_contact_phone: "İLETİŞİM & TELEFON",
      card_assigned_staff: "Bağlı Personeller",
      card_corp_email: "KURUMSAL E-POSTA",
      card_leadership_team: "LİDERLİK & EKİP",
      card_weekly_logs: "Haftalık İşlem Logları",
      card_meeting_progress: "Görüşme & Aktivite İlerlemesi",
      card_scheduled_meetings: "Ayarladığı Toplantılar & Randevular",
      status_card_cancelled: "Kart İptal Edildi",
      status_card_cancelled_desc: "Bu personel dijital kartvizitine ve panele erişemez.",

      modal_transfer_title: "👥 Müşterileri Başka Personele Aktar",
      modal_transfer_sub: "Ayrılan veya görev değişikliği olan personelin tüm müşteri kayıtlarını ve görüşme geçmişini başka bir ekip üyesine devredin.",
      modal_search_target_staff: "Hedef Personel Ara",
      modal_select_target_staff: "Aktarılacak Hedef Personeli Seçin",
      btn_confirm_transfer: "Tüm Müşterileri Aktar & Onayla",

      modal_target_title: "🎯 Personel Hedefi Belirle",
      modal_target_sub: "Aylık müşteri kazanım ve görüşme hedefi",
      label_monthly_target_count: "Aylık Hedeflenen Görüşme Adedi *",
      btn_save_target: "Hedefi Kaydet & Uygula",

      meeting_mgmt_title: "Toplantı Yönetimi",
      meeting_mgmt_sub: "Müşterilerinizle yeni toplantılar planlayın ve ajandanızı yönetin.",
      new_meeting_title: "Yeni Toplantı Düzenle",
      lbl_meeting_subject: "Toplantı Konusu *",
      lbl_participants: "Katılımcı / Müşteri (Çoklu Seçim) *",
      lbl_meeting_type: "Toplantı Ortamı *",
      lbl_date: "Tarih *",
      lbl_time: "Saat *",
      upcoming_meetings: "Yaklaşan Toplantılar",

      crm_title: "Müşteri Portföyü",
      crm_sub: "Görüştüğünüz kişileri, şirketleri ve etkileşim durumlarını buradan takip edin.",
      crm_stage_all: "Tüm Durumlar",
      crm_stage_hot: "🔥 Sıcak Müşteri",
      crm_stage_warm: "⚡ Ilık Müşteri",
      crm_stage_cold: "❄️ Soğuk Müşteri",

      super_greeting_title: "Kontrol Merkezi",
      super_greeting_sub: "Tüm kayıtlı kurumsal firmaların, demo taleplerinin ve lisans gelirlerinin anlık özeti.",
      btn_new_company: "+ Yeni Firma Ekle",
      btn_view_demos: "Demo Taleplerini Gör",
      card_active_companies: "Aktif Firma Sayısı",
      card_pending_demos: "Bekleyen Demolar",
      card_total_users: "Toplam Kullanıcı",
      card_mrr: "Aylık Gelir (MRR)"
    },
    en: {
      role_view: "View:",
      role_customer: "1. Client View",
      role_staff: "2. Staff Portal",
      role_admin: "3. Company Admin",
      role_super: "4. Super Admin (SaaS Owner)",

      btn_save_contact: "Save Contact",
      btn_share: "Share",
      btn_qr: "QR Code",
      btn_cancel: "Cancel",
      btn_save: "Save",
      btn_close: "Close",
      btn_apply: "Apply",
      btn_back_to_staff: "Back to Staff",
      btn_set_target: "🎯 Set / Edit Goal",
      btn_add_new_staff: "+ Add New Staff",
      btn_add_new_client: "+ Add New Client",
      btn_create_meeting: "Create Meeting",
      btn_transfer_clients: "👥 Transfer Clients",
      btn_cancel_card: "Deactivate Card",
      btn_reactivate_card: "Reactivate Card",

      search_admin: "Search staff, clients, or reports...",
      search_super: "Search company, contact, demo, or plan...",
      search_crm: "Search by name, company, or phone...",
      search_staff: "Search staff (Name, Title, Department)...",

      admin_greeting: "Welcome,",
      admin_greeting_sub: "Here is today's performance summary for your company and team.",
      live_crm_stream: "Live CRM & Meeting Stream",
      card_hot_clients: "Hot Leads",
      card_warm_clients: "Warm Leads",
      card_total_clients: "Total Clients",
      card_meeting_rate: "Meeting Rate",
      vs_last_week: "vs last week",
      in_negotiation: "In active negotiation",
      active_crm_portfolio: "Active CRM Portfolio",
      meeting_efficiency: "Meeting Efficiency",

      chart_weekly_progress: "Weekly Meeting & Client Progress",
      chart_completed_meetings: "Completed Meetings",
      chart_new_hot_clients: "New Hot Leads",
      chart_client_distribution: "Lead Status Distribution",

      top_performers_title: "Top Performing Staff",
      top_performers_sub: "Monthly top client acquisition and meeting performance",
      metric_hot_meeting: "HOT MEETINGS",
      metric_records: "RECORDS",
      metric_people: "People",

      schedule_timeline_title: "Meeting & Appointment Schedule",
      select_day: "Select Day:",

      th_staff_name: "Staff Name / Title",
      th_role_auth: "Role & Authority",
      th_total_clients: "Total Clients",
      th_hot_meetings: "Hot Meetings",
      th_monthly_target: "Monthly Goal",
      th_actions: "Actions",
      role_team_leader: "👑 Team Leader",
      role_staff_member: "Staff",
      btn_detail_perf: "Detail & Performance",

      chip_total_meetings: "🗣️ Total Meetings",
      chip_hot_client: "🔥 Hot Leads",
      chip_warm_client: "⚡ Warm Leads",
      chip_cold_client: "❄️ Cold Leads",
      chip_goal_complete: "🎯 Goal Completion",
      sub_completed: "100% Completed",
      sub_hot_prob: "High Deal Probability",
      sub_warm_stage: "Under Proposal",
      sub_cold_wait: "Pending Follow-up",
      sub_meeting_goal: "Meeting Goal",

      card_contact_phone: "CONTACT & PHONE",
      card_assigned_staff: "Assigned Subordinates",
      card_corp_email: "CORPORATE EMAIL",
      card_leadership_team: "LEADERSHIP & TEAM",
      card_weekly_logs: "Weekly Activity Logs",
      card_meeting_progress: "Meeting & Activity Progress",
      card_scheduled_meetings: "Scheduled Meetings & Appointments",
      status_card_cancelled: "Card Deactivated",
      status_card_cancelled_desc: "This staff member cannot access their digital card or the portal.",

      modal_transfer_title: "👥 Transfer Clients to Another Staff",
      modal_transfer_sub: "Reassign all client records, meetings, and interaction logs of a departed or reassigned staff to another team member.",
      modal_search_target_staff: "Search Target Staff",
      modal_select_target_staff: "Select Target Staff for Transfer",
      btn_confirm_transfer: "Transfer All Clients & Confirm",

      modal_target_title: "🎯 Set Staff Target",
      modal_target_sub: "Monthly client acquisition and meeting goal",
      label_monthly_target_count: "Monthly Target Meetings *",
      btn_save_target: "Save & Apply Goal",

      meeting_mgmt_title: "Meeting Management",
      meeting_mgmt_sub: "Schedule new meetings with clients and manage your agenda.",
      new_meeting_title: "Schedule New Meeting",
      lbl_meeting_subject: "Meeting Subject *",
      lbl_participants: "Participants / Clients (Multi-Select) *",
      lbl_meeting_type: "Meeting Platform *",
      lbl_date: "Date *",
      lbl_time: "Time *",
      upcoming_meetings: "Upcoming Meetings",

      crm_title: "Client Portfolio",
      crm_sub: "Track contacts, companies, and interaction stages from here.",
      crm_stage_all: "All Stages",
      crm_stage_hot: "🔥 Hot Leads",
      crm_stage_warm: "⚡ Warm Leads",
      crm_stage_cold: "❄️ Cold Leads",

      super_greeting_title: "Control Center",
      super_greeting_sub: "Real-time summary of registered companies, demo requests, and subscription revenues.",
      btn_new_company: "+ Add New Company",
      btn_view_demos: "View Demo Requests",
      card_active_companies: "Active Companies",
      card_pending_demos: "Pending Demos",
      card_total_users: "Total Users",
      card_mrr: "Monthly Revenue (MRR)"
    }
  };

  let currentLang = localStorage.getItem('monacard_lang') || 'tr';

  function t(key) {
    return (i18n[currentLang] && i18n[currentLang][key]) || (i18n['tr'] && i18n['tr'][key]) || key;
  }

  function updateStaticTranslations() {
    const isEn = currentLang === 'en';

    // 1. Role switcher
    const roleLabel = document.querySelector('.role-switcher-label');
    if (roleLabel) roleLabel.textContent = isEn ? 'View:' : 'Görünüm:';
    const rCust = document.querySelector('#btnRoleCustomer span');
    if (rCust) rCust.textContent = isEn ? '1. Client View' : '1. Müşteri Ekranı';
    const rStaff = document.querySelector('#btnRoleStaff span');
    if (rStaff) rStaff.textContent = isEn ? '2. Staff Portal' : '2. Personel Paneli';
    const rAdmin = document.querySelector('#btnRoleAdmin span');
    if (rAdmin) rAdmin.textContent = isEn ? '3. Company Admin' : '3. Firma Yöneticisi';
    const rSuper = document.querySelector('#btnRoleSuperAdmin span');
    if (rSuper) rSuper.textContent = isEn ? '4. Super Admin (SaaS Owner)' : '4. Süper Admin (SaaS Sahibi)';

    // 2. Public / Mobile Digital Business Card (PageHome)
    const btnSaveContactSpan = document.querySelector('#btnSaveContact span');
    if (btnSaveContactSpan) btnSaveContactSpan.textContent = isEn ? 'Save Contact' : 'Rehbere Kaydet';
    const btnShareModal = document.getElementById('btnShareModal');
    if (btnShareModal) btnShareModal.title = isEn ? 'Share Card' : 'Paylaş';
    const btnQrModal = document.getElementById('btnQrModal');
    if (btnQrModal) btnQrModal.title = isEn ? 'QR Code' : 'Karekod';
    const reviewPlatform = document.querySelector('.review-platform');
    if (reviewPlatform) reviewPlatform.textContent = isEn ? 'Google Reviews' : 'Google Değerlendirmeleri';
    const reviewCount = document.querySelector('.review-count');
    if (reviewCount) reviewCount.textContent = isEn ? '(48 Reviews)' : '(48 Yorum)';

    const sectionTitles = document.querySelectorAll('.section-title');
    sectionTitles.forEach(st => {
      const txt = st.textContent.trim();
      if (txt.includes('İletişim') || txt.includes('Contact')) st.textContent = isEn ? 'Contact Channels' : 'İletişim Kanalları';
      else if (txt.includes('Hakkımda') || txt.includes('About')) st.textContent = isEn ? 'About Me' : 'Hakkımda';
      else if (txt.includes('Ürünler') || txt.includes('Products') || txt.includes('Markalarımız')) st.textContent = isEn ? 'Products & Solutions' : 'Ürünler & Çözümler';
      else if (txt.includes('Sosyal') || txt.includes('Social')) st.textContent = isEn ? 'Social Networks & Links' : 'Sosyal Ağlar & İletişim';
    });

    const bioText = document.getElementById('displayBio');
    if (bioText) {
      if (isEn) {
        bioText.textContent = "Designing enterprise user experiences (UX/UI), digital product architectures, and modern web solutions for 10+ years. Leading product development for innovative digital business cards and CRM ecosystems at Vedubox.";
      } else {
        bioText.textContent = "Kullanıcı deneyimi (UX/UI), dijital ürün mimarisi ve modern web teknolojileri üzerine 10+ yıldır kurumsal çözümler üretiyorum. Vedubox bünyesinde yenilikçi dijital kartvizit ve CRM ekosistemlerinin ürün liderliğini yürütüyorum.";
      }
    }

    const statusBadge = document.querySelector('.status-badge.active');
    if (statusBadge) statusBadge.innerHTML = `<span class="pulse-dot"></span>${isEn ? 'Active' : 'Aktif'}`;
    const platformCount = document.querySelector('.platform-count');
    if (platformCount) platformCount.textContent = isEn ? '6 Platforms' : '6 Platform';

    // Contact labels
    document.querySelectorAll('.contact-item').forEach(item => {
      const lbl = item.querySelector('.contact-label');
      if (lbl) {
        const text = lbl.textContent.trim();
        if (text.includes('Telefon') || text.includes('Phone')) lbl.textContent = isEn ? 'Phone' : 'Telefon';
        else if (text.includes('E-Posta') || text.includes('Email')) lbl.textContent = isEn ? 'Email' : 'E-Posta';
        else if (text.includes('Web') || text.includes('Website')) lbl.textContent = isEn ? 'Website' : 'Web Sitesi';
        else if (text.includes('Adres') || text.includes('Address')) lbl.textContent = isEn ? 'Address' : 'Adres';
      }
    });

    // Mobile Bottom Bar Navigation
    const navItemSpans = document.querySelectorAll('.bottom-bar .nav-item span');
    if (navItemSpans.length >= 4) {
      navItemSpans[0].textContent = isEn ? 'Home' : 'Anasayfa';
      navItemSpans[1].textContent = isEn ? 'Meetings' : 'Toplantılar';
      navItemSpans[2].textContent = isEn ? 'Calendar' : 'Takvim';
      navItemSpans[3].textContent = isEn ? 'CRM' : 'CRM';
    }

    // 3. Staff Subpages (Profile, Meetings, Calendar, CRM)
    const profileSubTitle = document.querySelector('#pageProfile .subpage-title');
    if (profileSubTitle) profileSubTitle.textContent = isEn ? 'Edit Profile Information' : 'Profil Bilgilerini Düzenle';
    const profileSubDesc = document.querySelector('#pageProfile .subpage-desc');
    if (profileSubDesc) profileSubDesc.textContent = isEn ? 'Update all contact and profile details displayed on your MonaCard.' : 'MonaCard kartvizitinizde görünen tüm iletişim ve profil detaylarını güncelleyin.';
    const btnCancelProf = document.getElementById('btnCancelProfileEdit');
    if (btnCancelProf) btnCancelProf.textContent = isEn ? 'Cancel' : 'İptal';
    const btnSaveProf = document.querySelector('#profileEditForm button[type="submit"] span');
    if (btnSaveProf) btnSaveProf.textContent = isEn ? 'Save Changes' : 'Bilgileri Kaydet';

    const meetSubTitle = document.querySelector('#pageMeetings .page-subpage-title') || document.querySelector('#pageMeetings h2');
    if (meetSubTitle) meetSubTitle.textContent = isEn ? 'Meeting Management' : 'Toplantı Yönetimi';
    const meetBoxTitle = document.querySelector('#pageMeetings .card-box-title');
    if (meetBoxTitle) {
      meetBoxTitle.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg> ${isEn ? 'Schedule New Meeting' : 'Yeni Toplantı Düzenle'}`;
    }
    const btnMeetCreate = document.querySelector('.btn-meeting-create span');
    if (btnMeetCreate) btnMeetCreate.textContent = isEn ? 'Create Meeting' : 'Toplantı Oluştur';
    const meetPlaceholder = document.getElementById('newMeetParticipantsPlaceholder');
    if (meetPlaceholder && (!newMeetingSelectedCustomers || newMeetingSelectedCustomers.length === 0)) {
      meetPlaceholder.textContent = isEn ? 'Select participants (Multi-select)...' : 'Katılımcıları seçin (Çoklu seçim)...';
    }
    const upcomingMeetHeader = document.querySelector('#pageMeetings .section-subtitle') || document.querySelector('#pageMeetings .card-box-title.upcoming');
    if (upcomingMeetHeader) upcomingMeetHeader.textContent = isEn ? 'Upcoming Meetings' : 'Yaklaşan Toplantılar';

    const calSubTitle = document.querySelector('#pageCalendar h2');
    if (calSubTitle) calSubTitle.textContent = isEn ? 'Calendar & Agenda' : 'Ajanda & Takvim';
    const crmSubTitle = document.querySelector('#pageCrm h2');
    if (crmSubTitle) crmSubTitle.textContent = isEn ? 'Client Portfolio' : 'Müşteri Portföyü';
    const crmSubDesc = document.querySelector('#pageCrm .page-subpage-desc');
    if (crmSubDesc) crmSubDesc.textContent = isEn ? 'Track contacts, companies, and interaction stages from here.' : 'Görüştüğünüz kişileri, şirketleri ve etkileşim durumlarını buradan takip edin.';
    const btnCrmAdd = document.getElementById('btnOpenAddCustomerModal');
    if (btnCrmAdd) {
      const sp = btnCrmAdd.querySelector('span');
      if (sp) sp.textContent = isEn ? '+ Add New Client' : '+ Yeni Müşteri Ekle';
    }

    // CRM Filter Tabs
    const crmFilterBtns = document.querySelectorAll('#pageCrm .crm-filter-btn');
    if (crmFilterBtns.length >= 4) {
      crmFilterBtns[0].textContent = isEn ? 'All' : 'Tümü';
      crmFilterBtns[1].textContent = isEn ? '🔥 Hot' : '🔥 Sıcak';
      crmFilterBtns[2].textContent = isEn ? '⚡ Warm' : '⚡ Ilık';
      crmFilterBtns[3].textContent = isEn ? '❄️ Cold' : '❄️ Soğuk';
    }

    // 4. Cancelled Card Overlay
    const canTitle = document.querySelector('#staffCancelledCardOverlay .cancelled-title');
    if (canTitle) canTitle.textContent = isEn ? 'Card Deactivated' : 'Kartvizit İptal Edildi';
    const canDesc = document.querySelector('#staffCancelledCardOverlay .cancelled-desc');
    if (canDesc) canDesc.textContent = isEn ? 'This digital business card and account have been disabled by the company administrator. You do not have access authorization.' : 'Bu dijital kartvizit ve hesap firma yöneticisi tarafından kullanıma kapatılmıştır. Giriş yetkiniz bulunmamaktadır.';
    const canPill = document.querySelector('#staffCancelledCardOverlay .cancelled-info-pill span');
    if (canPill) canPill.innerHTML = `${isEn ? 'Status:' : 'Durum:'} <strong>${isEn ? 'Access Revoked' : 'Erişim İptal Edildi'}</strong>`;
    const btnSwitchCan = document.querySelector('#btnSwitchAdminFromCancelled span');
    if (btnSwitchCan) btnSwitchCan.textContent = isEn ? '🏢 Switch to Company Admin' : '🏢 Firma Yöneticisi Paneline Geç';

    // 5. Admin Topbar & Sidebar
    const admSrch = document.getElementById('adminGlobalSearch');
    if (admSrch) admSrch.placeholder = isEn ? 'Search staff, clients, or reports...' : 'Personel, müşteri veya rapor ara...';
    const supSrch = document.getElementById('superGlobalSearch');
    if (supSrch) supSrch.placeholder = isEn ? 'Search company, contact, demo, or plan...' : 'Firma adı, yetkili, demo talebi veya paket ara...';
    const crmSrch = document.getElementById('adminCrmSearchInput');
    if (crmSrch) crmSrch.placeholder = isEn ? 'Search by name, company, or phone...' : 'İsim, şirket veya telefon ile ara...';
    const stfSrch = document.getElementById('staffSearchInput');
    if (stfSrch) stfSrch.placeholder = isEn ? 'Search staff (Name, Title, Department)...' : 'Personel ara (İsim, Ünvan, Departman)...';
    const adminHeaderRole = document.getElementById('adminHeaderRole');
    if (adminHeaderRole) adminHeaderRole.textContent = isEn ? 'Company Admin' : 'Firma Yöneticisi';

    // Topbar Date Filter
    const admDateFilter = document.getElementById('adminDateFilter');
    if (admDateFilter && admDateFilter.options.length >= 4) {
      admDateFilter.options[0].text = isEn ? 'Today' : 'Bugün';
      admDateFilter.options[1].text = isEn ? 'This Week' : 'Bu Hafta';
      admDateFilter.options[2].text = isEn ? 'This Month' : 'Bu Ay';
      admDateFilter.options[3].text = isEn ? 'All Time' : 'Tüm Zamanlar';
    }

    // Sidebar titles
    const nDash = document.getElementById('navAdminDashboard');
    if (nDash) nDash.title = isEn ? 'Dashboard' : 'Genel Bakış (Dashboard)';
    const nStaff = document.getElementById('navAdminStaff');
    if (nStaff) nStaff.title = isEn ? 'Staff & Leaders' : 'Personeller & Liderler';
    const nCrm = document.getElementById('navAdminCrm');
    if (nCrm) nCrm.title = isEn ? 'Client CRM' : 'Müşteri Havuzu (CRM)';
    const nCal = document.getElementById('navAdminCalendar');
    if (nCal) nCal.title = isEn ? 'Meetings & Calendar' : 'Toplantı & Takvim';
    const nSet = document.getElementById('navAdminSettings');
    if (nSet) nSet.title = isEn ? 'Settings & Interface' : 'Ayarlar & Arayüz Yönetimi';
    const nProf = document.getElementById('navAdminProfile');
    if (nProf) nProf.title = isEn ? 'Executive Profile' : 'Firma & Yönetici Profili';
    const btnAdminSwitchStaffEl = document.getElementById('btnAdminSwitchStaff');
    if (btnAdminSwitchStaffEl) btnAdminSwitchStaffEl.title = isEn ? 'Quick Switch to Staff View' : 'Personel Ekranına Hızlı Geçiş';

    // 6. Admin Greeting & Dashboard Stat Cards
    const greetingWelcome = document.querySelector('.greeting-title');
    if (greetingWelcome) {
      const gName = document.getElementById('adminGreetingName');
      const nameText = gName ? gName.textContent : 'Yönetici';
      greetingWelcome.innerHTML = `${isEn ? 'Welcome,' : 'Hoş geldin,'} <span class="highlight-name" id="adminGreetingName">${nameText}</span> <span class="wave-emoji">👋</span>`;
    }
    const greetingSub = document.querySelector('.greeting-subtitle');
    if (greetingSub) greetingSub.textContent = isEn ? 'Here is today’s performance summary for your company and team.' : 'İşte şirketinizin ve ekibinizin bugünkü performans özeti.';
    const liveBadge = document.querySelector('.admin-live-pulse-badge');
    if (liveBadge) liveBadge.innerHTML = `<span class="pulse-dot"></span> ${isEn ? 'Live CRM & Meeting Stream' : 'Canlı CRM & Görüşme Akışı'}`;

    const statTitles = document.querySelectorAll('.admin-stat-card .stat-card-title');
    if (statTitles.length >= 4) {
      statTitles[0].textContent = isEn ? 'Hot Leads' : 'Sıcak Müşteriler';
      statTitles[1].textContent = isEn ? 'Warm Leads' : 'Ilık Müşteri';
      statTitles[2].textContent = isEn ? 'Total Clients' : 'Toplam Müşteri';
      statTitles[3].textContent = isEn ? 'Meeting Rate' : 'Görüşme Oranı';
    }

    const statTrends = document.querySelectorAll('.admin-stat-card .trend-label');
    if (statTrends.length >= 4) {
      statTrends[0].textContent = isEn ? 'vs last week' : 'vs geçen hafta';
      statTrends[1].textContent = isEn ? 'vs last week' : 'vs geçen hafta';
      statTrends[2].textContent = isEn ? 'vs last week' : 'vs geçen hafta';
      statTrends[3].textContent = isEn ? 'vs last month' : 'vs geçen ay';
    }

    // Chart titles & Legends
    const chartWeeklyTitle = document.querySelector('#viewAdminDashboard .admin-card-header .admin-card-title');
    if (chartWeeklyTitle) chartWeeklyTitle.textContent = isEn ? 'Weekly Meeting & Client Progress' : 'Haftalık Görüşme & Müşteri İlerlemesi';
    const chartWeeklySub = document.querySelector('#viewAdminDashboard .admin-card-header .admin-card-subtitle');
    if (chartWeeklySub) chartWeeklySub.textContent = isEn ? 'Real-time daily completed meetings and new leads' : 'Günlük tamamlanan toplantılar ve kazanılan yeni müşteriler';

    const legendItems = document.querySelectorAll('.chart-legend-item span');
    if (legendItems.length >= 2) {
      legendItems[0].textContent = isEn ? 'Completed Meetings' : 'Tamamlanan Görüşmeler';
      legendItems[1].textContent = isEn ? 'New Hot Leads' : 'Yeni Sıcak Müşteri';
    }

    const donutCardTitle = document.querySelector('.admin-donut-card .admin-card-title');
    if (donutCardTitle) donutCardTitle.textContent = isEn ? 'Lead Status Distribution' : 'Müşteri Durum Dağılımı';
    const donutCardSub = document.querySelector('.admin-donut-card .admin-card-subtitle');
    if (donutCardSub) donutCardSub.textContent = isEn ? 'Hot, warm, and cold leads ratio (%100)' : 'Sıcak, ılık ve soğuk müşteri oranı (%100)';

    const donutLegendLabels = document.querySelectorAll('.donut-legend-info .donut-legend-name');
    if (donutLegendLabels.length >= 3) {
      donutLegendLabels[0].textContent = isEn ? '🔥 Hot Leads' : '🔥 Sıcak Müşteri';
      donutLegendLabels[1].textContent = isEn ? '⚡ Warm Leads' : '⚡ Ilık Müşteri';
      donutLegendLabels[2].textContent = isEn ? '❄️ Cold Leads' : '❄️ Soğuk Müşteri';
    }

    const topPerfTitle = document.querySelector('.admin-top-performers-card .admin-card-title');
    if (topPerfTitle) topPerfTitle.textContent = isEn ? 'Top Performing Staff' : 'En İyi Performans Gösteren Personeller';
    const topPerfSub = document.querySelector('.admin-top-performers-card .admin-card-subtitle');
    if (topPerfSub) topPerfSub.textContent = isEn ? 'Top 3 team members with highest client acquisition and hot meeting performance' : 'En yüksek müşteri kazanımı ve sıcak görüşme performansına sahip ilk 3 ekip üyesi';
    const topPerfViewAll = document.querySelector('.btn-view-all-staff');
    if (topPerfViewAll) topPerfViewAll.textContent = isEn ? 'View All ›' : 'Tümünü Gör ›';

    // 7-Day Weekly Timeline Widget Day Names
    const dayNamesList = isEn ? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] : ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
    document.querySelectorAll('.weekly-day-item .weekly-day-name').forEach((el, idx) => {
      if (dayNamesList[idx]) el.textContent = dayNamesList[idx];
    });

    // Staff Table page
    const staffPageTitle = document.querySelector('#viewAdminStaff .admin-header-title') || document.querySelector('#viewAdminStaff .admin-view-title');
    if (staffPageTitle) staffPageTitle.textContent = isEn ? 'Staff & Leader Performance Management' : 'Personel & Lider Performans Yönetimi';
    const staffPageSub = document.querySelector('#viewAdminStaff .admin-header-subtitle') || document.querySelector('#viewAdminStaff .admin-view-subtitle');
    if (staffPageSub) staffPageSub.textContent = isEn ? 'Track your team’s digital card usage, client acquisitions, and monthly goals in real-time.' : 'Ekibinizin dijital kartvizit kullanımını, müşteri kazanımlarını ve aylık hedeflerini canlı takip edin.';
    const btnStaffAddSpan = document.querySelector('#btnStaffAddModal span') || document.querySelector('#btnAdminAddStaff span');
    if (btnStaffAddSpan) btnStaffAddSpan.textContent = isEn ? '+ Add New Staff' : '+ Yeni Personel Ekle';

    // Status Filter Pills
    const statusPillTitles = document.querySelectorAll('.status-pill-box .status-pill-title');
    if (statusPillTitles.length >= 4) {
      statusPillTitles[0].textContent = isEn ? 'All Staff' : 'Tüm Personeller';
      statusPillTitles[1].textContent = isEn ? '👑 Team Leaders' : '👑 Takım Liderleri';
      statusPillTitles[2].textContent = isEn ? '⚡ Top Performers' : '⚡ En Çok Müşteri Ekleyenler';
      statusPillTitles[3].textContent = isEn ? '🎯 Goal Approaching' : '🎯 Hedefi Yaklaşanlar';
    }

    // Sort Select
    const stfSort = document.getElementById('staffSortSelect');
    if (stfSort && stfSort.options.length >= 3) {
      stfSort.options[0].text = isEn ? 'Sort: Highest Performance' : 'Sıralama: En Yüksek Performans';
      stfSort.options[1].text = isEn ? 'Sort: Most Clients' : 'Sıralama: En Çok Müşteri';
      stfSort.options[2].text = isEn ? 'Sort: Name (A-Z)' : 'Sıralama: İsim (A-Z)';
    }

    // Staff Table Headers
    const staffTableThs = document.querySelectorAll('#staffDataTable th');
    if (staffTableThs.length >= 7) {
      staffTableThs[0].textContent = isEn ? 'Staff' : 'Personel';
      staffTableThs[1].textContent = isEn ? 'Reporting Leader' : 'Bağlı Olduğu Lider';
      staffTableThs[2].textContent = isEn ? 'Registered Clients' : 'Kayıtlı Müşteri';
      staffTableThs[3].textContent = isEn ? 'Client Distribution' : 'Müşteri Dağılımı';
      staffTableThs[4].textContent = isEn ? 'Monthly Goal' : 'Aylık Hedef';
      staffTableThs[5].textContent = isEn ? 'Performance' : 'Performans';
      staffTableThs[6].textContent = isEn ? 'Actions' : 'İşlemler';
    }

    // 7. Staff Detail Page
    const btnBackStaffSpan = document.querySelector('#btnBackToStaffList span');
    if (btnBackStaffSpan) btnBackStaffSpan.textContent = isEn ? 'Back to Staff' : 'Personellere Dön';
    const btnEditTargetSpan = document.querySelector('#btnEditStaffTargetModal span');
    if (btnEditTargetSpan) btnEditTargetSpan.textContent = isEn ? '🎯 Set / Edit Goal' : '🎯 Hedef Belirle / Düzenle';

    const detTimeFilter = document.getElementById('staffDetailTimeFilter') || document.getElementById('staffDetTimeFilter');
    if (detTimeFilter && detTimeFilter.options.length >= 5) {
      detTimeFilter.options[0].text = isEn ? 'This Week' : 'Bu Hafta';
      detTimeFilter.options[1].text = isEn ? 'This Month' : 'Bu Ay';
      detTimeFilter.options[2].text = isEn ? 'Last 30 Days' : 'Son 30 Gün';
      detTimeFilter.options[3].text = isEn ? 'Last 3 Months' : 'Son 3 Ay';
      detTimeFilter.options[4].text = isEn ? 'All Time' : 'Tüm Zamanlar';
    }

    const chipLabels = document.querySelectorAll('.peoplexio-stat-chips-row .metric-chip-label');
    if (chipLabels.length >= 5) {
      chipLabels[0].textContent = isEn ? '🗣️ Total Meetings' : '🗣️ Toplam Görüşme';
      chipLabels[1].textContent = isEn ? '🔥 Hot Leads' : '🔥 Sıcak Müşteri';
      chipLabels[2].textContent = isEn ? '⚡ Warm Leads' : '⚡ Ilık Müşteri';
      chipLabels[3].textContent = isEn ? '❄️ Cold Leads' : '❄️ Soğuk Müşteri';
      chipLabels[4].textContent = isEn ? '🎯 Goal Completion' : '🎯 Hedef Tamamlama';
    }
    const chipSubs = document.querySelectorAll('.peoplexio-stat-chips-row .metric-chip-sub');
    if (chipSubs.length >= 4) {
      chipSubs[0].textContent = isEn ? '100% Completed' : '%100 Tamamlandı';
      chipSubs[1].textContent = isEn ? 'High Deal Probability' : 'Yüksek Anlaşma İhtimali';
      chipSubs[2].textContent = isEn ? 'Under Proposal' : 'Teklif Aşamasında';
      chipSubs[3].textContent = isEn ? 'Pending Follow-up' : 'Takip Bekleyen';
    }

    const detSecTitles = document.querySelectorAll('.peoplexio-details-title');
    detSecTitles.forEach(t => {
      const txt = t.textContent.trim();
      if (txt.includes('İLETİŞİM') || txt.includes('CONTACT')) t.textContent = isEn ? 'CONTACT & PHONE' : 'İLETİŞİM & TELEFON';
      else if (txt.includes('KURUMSAL') || txt.includes('EMAIL')) t.textContent = isEn ? 'CORPORATE EMAIL' : 'KURUMSAL E-POSTA';
      else if (txt.includes('LİDERLİK') || txt.includes('LEADERSHIP')) t.textContent = isEn ? 'LEADERSHIP & TEAM' : 'LİDERLİK & EKİP';
    });

    const btnTransSpan = document.querySelector('#btnTransferStaffClients span');
    if (btnTransSpan) btnTransSpan.textContent = isEn ? 'Transfer Clients' : 'Müşterileri Aktar';

    const actProgTitle = document.querySelector('.peoplexio-chart-card h4');
    if (actProgTitle) actProgTitle.textContent = isEn ? 'Meeting & Activity Progress' : 'Görüşme & Aktivite İlerlemesi';
    const schedTitle = document.querySelector('.peoplexio-tasks-card h4');
    if (schedTitle) schedTitle.textContent = isEn ? 'Scheduled Meetings & Appointments' : 'Ayarladığı Toplantılar & Randevular';
    const weeklyLogTitle = document.querySelector('.peoplexio-dark-task-card h4');
    if (weeklyLogTitle) weeklyLogTitle.textContent = isEn ? 'Weekly Activity Logs' : 'Haftalık İşlem Logları';

    // 8. Admin CRM page
    const crmPageTitle = document.querySelector('#viewAdminCrm .admin-header-title') || document.querySelector('#viewAdminCrm .admin-view-title');
    if (crmPageTitle) crmPageTitle.textContent = isEn ? 'Corporate Client CRM' : 'Kurumsal Müşteri Havuzu (CRM)';
    const crmPageSub = document.querySelector('#viewAdminCrm .admin-header-subtitle') || document.querySelector('#viewAdminCrm .admin-view-subtitle');
    if (crmPageSub) crmPageSub.textContent = isEn ? 'Manage all clients, pipeline stages, and voice notes registered by all staff in one place.' : 'Tüm personellerin kaydettiği müşterileri, aşamalarını ve sesli notlarını tek merkezden yönetin.';

    const crmStageSelect = document.getElementById('adminCrmStageFilter');
    if (crmStageSelect && crmStageSelect.options.length >= 4) {
      crmStageSelect.options[0].text = isEn ? 'All Stages' : 'Tüm Aşamalar';
      crmStageSelect.options[1].text = isEn ? '🔥 Hot Leads' : '🔥 Sıcak Müşteri';
      crmStageSelect.options[2].text = isEn ? '⚡ Warm Leads' : '⚡ Ilık Müşteri';
      crmStageSelect.options[3].text = isEn ? '❄️ Cold Leads' : '❄️ Soğuk Müşteri';
    }

    const crmTypeSelect = document.getElementById('adminCrmTypeFilter');
    if (crmTypeSelect && crmTypeSelect.options.length >= 3) {
      crmTypeSelect.options[0].text = isEn ? 'All Record Types' : 'Tüm Kayıt Türleri';
      crmTypeSelect.options[1].text = isEn ? '✨ New Clients' : '✨ Yeni Müşteri';
      crmTypeSelect.options[2].text = isEn ? '📂 Existing Portfolio' : '📂 Eski Portföy';
    }

    const crmTableThs = document.querySelectorAll('#viewAdminCrm th');
    if (crmTableThs.length >= 8) {
      crmTableThs[0].textContent = isEn ? 'Client' : 'Müşteri';
      crmTableThs[1].textContent = isEn ? 'Company & Title' : 'Şirket & Ünvan';
      crmTableThs[2].textContent = isEn ? 'Assigned Staff' : 'İlgilenen Personel';
      crmTableThs[3].textContent = isEn ? 'Client Status' : 'Müşteri Durumu';
      crmTableThs[4].textContent = isEn ? 'Record Type' : 'Kayıt Türü';
      crmTableThs[5].textContent = isEn ? 'Meeting Notes' : 'Görüşme Notları';
      crmTableThs[6].textContent = isEn ? 'Date' : 'Tarih';
      crmTableThs[7].textContent = isEn ? 'Action' : 'Aksiyon';
    }

    // 9. Admin Calendar page
    const calPageTitle = document.querySelector('#viewAdminCalendar .admin-header-title') || document.querySelector('#viewAdminCalendar .admin-view-title');
    if (calPageTitle) calPageTitle.textContent = isEn ? 'Meeting & Calendar Schedule' : 'Toplantı & Randevu Takvimi';
    const calPageSub = document.querySelector('#viewAdminCalendar .admin-header-subtitle') || document.querySelector('#viewAdminCalendar .admin-view-subtitle');
    if (calPageSub) calPageSub.textContent = isEn ? 'All Google Meet, Zoom, and in-person client meetings scheduled across the team.' : 'Ekip genelinde planlanan tüm Google Meet, Zoom ve yüz yüze müşteri görüşmeleri.';
    const btnAdminCreateMeet = document.getElementById('btnAdminCreateMeeting');
    if (btnAdminCreateMeet) {
      const sp = btnAdminCreateMeet.querySelector('span');
      if (sp) sp.textContent = isEn ? '+ Create New Meeting' : '+ Yeni Toplantı Oluştur';
    }

    const tabAllMeet = document.getElementById('tabAdminAllMeetings');
    if (tabAllMeet) tabAllMeet.textContent = isEn ? 'All Team Meetings' : 'Tüm Ekip Toplantıları';
    const tabPersMeet = document.getElementById('tabAdminPersonalMeetings');
    if (tabPersMeet) tabPersMeet.textContent = isEn ? 'Personal Agenda' : 'Şahsi Ajandam';
    const btnCalMonth = document.getElementById('btnCalViewMonth');
    if (btnCalMonth) btnCalMonth.textContent = isEn ? 'Month View' : 'Ay Görünümü';
    const btnCalCards = document.getElementById('btnCalViewCards');
    if (btnCalCards) btnCalCards.textContent = isEn ? 'Cards' : 'Kartlar';

    // 10. Admin Settings page
    const setPageTitle = document.querySelector('#viewAdminSettings .admin-header-title') || document.querySelector('#viewAdminSettings .admin-view-title');
    if (setPageTitle) setPageTitle.textContent = isEn ? 'Company Settings & Interface Management' : 'Firma Ayarları & Arayüz Yönetimi';
    const setPageSub = document.querySelector('#viewAdminSettings .admin-header-subtitle') || document.querySelector('#viewAdminSettings .admin-view-subtitle');
    if (setPageSub) setPageSub.textContent = isEn ? 'Configure digital card branding, corporate colors, staff permissions, and integrations.' : 'Kartvizit arayüzünü, kurumsal renkleri, personel yetkilerini ve entegrasyonları yapılandırın.';
    const btnSaveSetSpan = document.querySelector('#btnSaveAdminSettings span') || document.querySelector('#btnSaveCompanySettings span');
    if (btnSaveSetSpan) btnSaveSetSpan.textContent = isEn ? 'Save All Company Settings' : 'Tüm Firma Ayarlarını Kaydet';

    const setLangLbl = document.getElementById('settingsLanguageLabel');
    if (setLangLbl) setLangLbl.textContent = isEn ? 'System Interface Language:' : 'Sistem Arayüz Dili (Interface Language):';
    const lblLangTrT = document.getElementById('lblLangTrTitle');
    if (lblLangTrT) lblLangTrT.textContent = isEn ? 'Turkish (Türkçe)' : 'Türkçe (Turkish)';
    const lblLangTrD = document.getElementById('lblLangTrDesc');
    if (lblLangTrD) lblLangTrD.textContent = isEn ? 'Complete interface and admin panel in Turkish' : 'Tüm arayüz ve yönetim paneli Türkçe';
    const lblLangEnT = document.getElementById('lblLangEnTitle');
    if (lblLangEnT) lblLangEnT.textContent = isEn ? 'English' : 'English';
    const lblLangEnD = document.getElementById('lblLangEnDesc');
    if (lblLangEnD) lblLangEnD.textContent = isEn ? 'Complete interface and admin panel in English' : 'Tüm arayüz ve yönetim paneli İngilizce';

    const setThemeLbl = document.getElementById('settingsThemeModeLabel');
    if (setThemeLbl) setThemeLbl.textContent = isEn ? 'Default Appearance Mode:' : 'Varsayılan Görünüm Modu:';
    const lblThemeLightT = document.getElementById('lblThemeLightTitle');
    if (lblThemeLightT) lblThemeLightT.textContent = isEn ? 'Light Mode' : 'Aydınlık Mod (Light)';
    const lblThemeLightD = document.getElementById('lblThemeLightDesc');
    if (lblThemeLightD) lblThemeLightD.textContent = isEn ? 'Bright and clean interface' : 'Ferah ve temiz arayüz';
    const lblThemeDarkT = document.getElementById('lblThemeDarkTitle');
    if (lblThemeDarkT) lblThemeDarkT.textContent = isEn ? 'Dark Mode' : 'Karanlık Mod (Dark)';
    const lblThemeDarkD = document.getElementById('lblThemeDarkDesc');
    if (lblThemeDarkD) lblThemeDarkD.textContent = isEn ? 'Modern eye-friendly dark theme' : 'Göz yormayan modern koyu tema';

    // 11. Admin Profile page
    const profPageTitle = document.querySelector('#viewAdminProfile .admin-header-title') || document.querySelector('#viewAdminProfile .admin-view-title');
    if (profPageTitle) profPageTitle.textContent = isEn ? 'Executive & Company Profile' : 'Yönetici & Firma Profili';
    const profPageSub = document.querySelector('#viewAdminProfile .admin-header-subtitle') || document.querySelector('#viewAdminProfile .admin-view-subtitle');
    if (profPageSub) profPageSub.textContent = isEn ? 'Company administrator account information and corporate identity details.' : 'Firma yöneticisi hesap bilgileri ve kurumsal kimlik detayları.';
    const btnProfSave = document.getElementById('btnAdminSaveAllProfile');
    if (btnProfSave) {
      const sp = btnProfSave.querySelector('span');
      if (sp) sp.textContent = isEn ? 'Save Profile' : 'Profili Kaydet';
    }

    // 12. Super Admin Views
    const superGreeting = document.querySelector('.super-greeting-header .greeting-title');
    if (superGreeting) superGreeting.innerHTML = `${isEn ? 'Control Center' : 'Kontrol Merkezi'} <span class="wave-emoji">🚀</span>`;
    const superGreetingSub = document.querySelector('.super-greeting-header .greeting-subtitle');
    if (superGreetingSub) superGreetingSub.textContent = isEn ? 'Real-time summary of registered corporate clients, demo requests, and SaaS revenue.' : 'Tüm kayıtlı kurumsal firmaların, demo taleplerinin ve lisans gelirlerinin anlık özeti.';
    const btnNewCoSpan = document.querySelector('#btnDashNewCompany span');
    if (btnNewCoSpan) btnNewCoSpan.textContent = isEn ? '+ Add New Company' : '+ Yeni Firma Ekle';
    const btnViewDemoSpan = document.querySelector('#btnDashViewDemos span');
    if (btnViewDemoSpan) {
      const cnt = document.getElementById('dashPendingDemoCount');
      const cVal = cnt ? cnt.textContent : '5';
      btnViewDemoSpan.innerHTML = isEn ? `View Demo Requests (<span id="dashPendingDemoCount">${cVal}</span>)` : `Demo Taleplerini Gör (<span id="dashPendingDemoCount">${cVal}</span>)`;
    }

    const superStatTitles = document.querySelectorAll('.super-stat-card .super-stat-title');
    if (superStatTitles.length >= 4) {
      superStatTitles[0].textContent = isEn ? 'Active Companies' : 'Aktif Firma Sayısı';
      superStatTitles[1].textContent = isEn ? 'Pending Demos' : 'Bekleyen Demolar';
      superStatTitles[2].textContent = isEn ? 'Total Users' : 'Toplam Kullanıcı';
      superStatTitles[3].textContent = isEn ? 'Monthly Revenue (MRR)' : 'Aylık Gelir (MRR)';
    }

    // Super Admin Sidebar
    const supNavItems = document.querySelectorAll('.super-nav-item');
    if (supNavItems.length >= 5) {
      supNavItems[0].title = isEn ? 'Dashboard' : 'Genel Bakış';
      supNavItems[1].title = isEn ? 'Registered Companies' : 'Kayıtlı Firmalar';
      supNavItems[2].title = isEn ? 'Demo Requests' : 'Demo Talepleri';
      supNavItems[3].title = isEn ? 'Plans & Pricing' : 'Paketler & Fiyatlar';
      supNavItems[4].title = isEn ? 'System Logs' : 'Sistem Logları';
    }

    // 13. Modals
    const targetModTitle = document.getElementById('targetModalTitle');
    if (targetModTitle) targetModTitle.textContent = isEn ? '🎯 Set Staff Goal' : '🎯 Personel Hedefi Belirle';
    const targetModSub = document.getElementById('targetModalSubtitle');
    if (targetModSub) targetModSub.textContent = isEn ? 'Monthly client acquisition and meeting goal' : 'Aylık müşteri kazanım ve görüşme hedefi';
    const targetInputLbl = document.querySelector('label[for="targetGoalInput"]');
    if (targetInputLbl) targetInputLbl.textContent = isEn ? 'Monthly Target Meetings *' : 'Aylık Hedeflenen Görüşme Adedi *';
    const btnTargetSave = document.querySelector('#targetGoalForm button[type="submit"]');
    if (btnTargetSave) btnTargetSave.textContent = isEn ? 'Save & Apply Goal' : 'Hedefi Kaydet & Uygula';

    const transferModTitle = document.getElementById('transferModalTitle');
    if (transferModTitle) transferModTitle.textContent = isEn ? '👥 Transfer Clients to Another Staff' : '👥 Müşterileri Başka Personele Aktar';
    const transferModSub = document.getElementById('transferModalSubtitle');
    if (transferModSub) transferModSub.textContent = isEn ? 'Reassign all client records, meetings, and interaction logs of a departed or reassigned staff to another team member.' : 'Ayrılan veya görev değişikliği olan personelin tüm müşteri kayıtlarını ve görüşme geçmişini başka bir ekip üyesine devredin.';
    const btnConfirmTrans = document.getElementById('btnConfirmTransfer');
    if (btnConfirmTrans) btnConfirmTrans.textContent = isEn ? 'Transfer All Clients & Confirm' : 'Tüm Müşterileri Aktar & Onayla';
    const btnCancelTrans = document.getElementById('btnCancelTransferModal');
    if (btnCancelTrans) btnCancelTrans.textContent = isEn ? 'Cancel' : 'Vazgeç';
    const transferSearchLbl = document.querySelector('label[for="transferStaffSearchInput"]');
    if (transferSearchLbl) transferSearchLbl.textContent = isEn ? 'Search Target Staff' : 'Hedef Personel Ara';
    const transferSearchInp = document.getElementById('transferStaffSearchInput');
    if (transferSearchInp) transferSearchInp.placeholder = isEn ? 'Type name or department...' : 'İsim veya departman yazın...';

    const subordTitle = document.getElementById('subordinatesModalTitle');
    if (subordTitle) subordTitle.textContent = isEn ? '👑 Assigned Subordinates' : '👑 Bağlı Personeller';
    const subordSub = document.getElementById('subordinatesModalSubtitle');
    if (subordSub) subordSub.textContent = isEn ? 'Team members working under this team leader.' : 'Bu takım liderine bağlı olarak çalışan ekip üyeleri.';

    // Sync radio controls in settings
    const rLangTr = document.getElementById('radioLangTr');
    const rLangEn = document.getElementById('radioLangEn');
    if (rLangTr && rLangEn) {
      if (isEn) {
        rLangEn.checked = true;
      } else {
        rLangTr.checked = true;
      }
    }
  }

  function setLanguage(lang) {
    currentLang = (lang === 'en' || lang === 'eng') ? 'en' : 'tr';
    localStorage.setItem('monacard_lang', currentLang);
    document.documentElement.lang = currentLang;

    // Update active class on all language switcher pills
    document.querySelectorAll('.lang-switcher-pill').forEach(pill => {
      pill.querySelectorAll('.lang-btn').forEach(btn => {
        const bLang = btn.getAttribute('data-lang');
        if (bLang === currentLang || (bLang === 'eng' && currentLang === 'en')) {
          btn.classList.add('active');
        } else {
          btn.classList.remove('active');
        }
      });
    });

    updateStaticTranslations();

    // Re-render dynamic components based on active role
    if (typeof renderAdminAll === 'function') renderAdminAll();
    if (typeof renderSuperAll === 'function') renderSuperAll();
    if (typeof renderMeetingsList === 'function') renderMeetingsList();
    if (typeof renderCrmList === 'function') renderCrmList();
    if (typeof renderRemindersList === 'function') renderRemindersList();

    const detPage = document.getElementById('viewStaffPerformanceDetail');
    if (detPage && detPage.classList.contains('active') && typeof renderStaffDetailPage === 'function') {
      renderStaffDetailPage();
    }

    showToast(currentLang === 'en' ? '🌐 English interface activated! ✨' : '🌐 Türkçe arayüz aktif edildi! ✨');
  }

  // Bind Language Switcher Buttons
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const lang = btn.getAttribute('data-lang');
      if (lang) {
        setLanguage(lang);
      }
    });
  });

  // Bind Admin Settings Language Radio Buttons
  const radioLangTr = document.getElementById('radioLangTr');
  const radioLangEn = document.getElementById('radioLangEn');
  if (radioLangTr) {
    radioLangTr.addEventListener('change', () => {
      if (radioLangTr.checked) setLanguage('tr');
    });
  }
  if (radioLangEn) {
    radioLangEn.addEventListener('change', () => {
      if (radioLangEn.checked) setLanguage('en');
    });
  }

  // =========================================================
  // 0. ADMIN / COMPANY SETTINGS & DATA STRUCTURES
  // =========================================================
  const defaultAdminSettings = {
    brandColor: '#00A86B',
    themeMode: 'light',
    staffFeatures: {
      hubspot: true,
      voiceNotes: true,
      products: true,
      socialLinks: true,
      reviews: true,
      vcard: true
    },
    companyProfile: {
      name: 'Vedubox Bilişim & Eğitim Teknolojileri',
      sector: 'Eğitim Teknolojileri & SaaS Yazılım',
      website: 'https://vedubox.com',
      managerName: 'Muhiddin Öktem',
      managerTitle: 'Genel Müdür / CEO',
      email: 'muhiddinoktem@vedubox.com',
      phone: '+90 536 255 64 24',
      phone2: '+90 212 900 00 00',
      address: 'Dalgıç Sk. Yeşilce Mh. No 3 Kağıthane / İstanbul',
      socialLinks: {
        whatsapp: '905362556424',
        telegram: 'muhiddinoktem',
        twitter: 'muhiddinoktem',
        linkedin: 'muhiddinoktem',
        facebook: 'muhiddinoktem',
        instagram: 'muhiddinoktem',
        youtube: 'vedubox'
      },
      products: [
        { id: 'prod-1', name: 'Vedubox', subtitle: 'Online Eğitim & LMS Platformu', url: 'https://vedubox.com', logo: 'vedubox.png' },
        { id: 'prod-2', name: 'Etgigrup', subtitle: 'Bilişim & Eğitim Teknolojileri', url: 'https://etgigrup.com', logo: 'etgigrup.png' }
      ]
    },
    integrations: {
      google: {
        calendarSync: true,
        meetAuto: true,
        reviewUrl: 'https://g.page/r/vedubox/review',
        clientId: '782910482910-vedubox.apps.googleusercontent.com'
      },
      zoom: {
        autoLink: true,
        waitingRoom: true,
        accountId: 'zoom_acc_98412894',
        clientId: 'zm_client_849201948',
        clientSecret: 'zm_secret_k84920f92j1923'
      },
      hubspot: {
        autoSyncContacts: true,
        autoSyncVoice: true,
        portalId: '4829104',
        stage: 'lead',
        apiKey: 'demo_hubspot_token_placeholder'
      }
    }
  };

  let adminSettings = { ...defaultAdminSettings };
  try {
    const savedAdmin = localStorage.getItem('monacard_admin_settings');
    if (savedAdmin) {
      adminSettings = { ...defaultAdminSettings, ...JSON.parse(savedAdmin) };
    }
  } catch (e) {
    console.warn('Admin settings storage error', e);
  }

  function saveAdminSettingsToStorage() {
    try {
      localStorage.setItem('monacard_admin_settings', JSON.stringify(adminSettings));
    } catch (e) {}
  }

  // =========================================================
  // 1. STAFF PROFILE DATA & LOCAL STORAGE
  // =========================================================
  const defaultProfile = {
    fullName: "Muhiddin Öktem",
    company: "Vedubox",
    title: "Senior Product Designer & Creative Technologist",
    phone: "+90 536 255 64 24",
    phoneClean: "+905362556424",
    email: "muhiddinoktem@vedubox.com",
    website: "https://vedubox.com",
    address: "Dalgıç Sk. Yeşilce Mh.\nNo 3 Kağıthane / İstanbul",
    whatsapp: "905362556424",
    telegram: "muhiddinoktem",
    linkedin: "muhiddinoktem",
    twitter: "muhiddinoktem",
    facebook: "muhiddinoktem",
    instagram: "muhiddinoktem",
    prodVeduboxUrl: "https://vedubox.com",
    prodEtgigrupUrl: "https://etgigrup.com"
  };

  let staffProfile = { ...defaultProfile };
  try {
    const saved = localStorage.getItem('monacard_staff_profile');
    if (saved) {
      staffProfile = { ...defaultProfile, ...JSON.parse(saved) };
    }
  } catch (e) {
    console.warn('Profile storage error', e);
  }

  function applyProfileToUI() {
    const nameEl = document.getElementById('displayFullName');
    const titleEl = document.getElementById('displayTitle');
    const phoneEl = document.getElementById('displayPhone');
    const emailEl = document.getElementById('displayEmail');
    const websiteEl = document.getElementById('displayWebsite');
    const addressEl = document.getElementById('displayAddress');

    if (nameEl) nameEl.textContent = staffProfile.fullName;
    if (titleEl) titleEl.textContent = staffProfile.title;
    if (phoneEl) {
      phoneEl.textContent = staffProfile.phone;
      phoneEl.href = `tel:${staffProfile.phoneClean}`;
    }
    if (emailEl) {
      emailEl.textContent = staffProfile.email;
      emailEl.href = `mailto:${staffProfile.email}`;
    }
    if (websiteEl) {
      const cleanWeb = staffProfile.website.replace(/^https?:\/\//, '');
      websiteEl.textContent = cleanWeb;
      websiteEl.href = staffProfile.website.startsWith('http') ? staffProfile.website : `https://${staffProfile.website}`;
    }
    if (addressEl) {
      addressEl.innerHTML = staffProfile.address.replace(/\n/g, '<br>');
    }

    // Direct action links
    // Direct action links
    const linkCallAction = document.getElementById('linkCallAction');
    if (linkCallAction) linkCallAction.href = `tel:${staffProfile.phoneClean}`;
    const linkSmsAction = document.getElementById('linkSmsAction');
    if (linkSmsAction) linkSmsAction.href = `sms:${staffProfile.phoneClean}`;
    const linkEmailAction = document.getElementById('linkEmailAction');
    if (linkEmailAction) linkEmailAction.href = `mailto:${staffProfile.email}`;
    const linkWebsiteAction = document.getElementById('linkWebsiteAction');
    if (linkWebsiteAction) linkWebsiteAction.href = staffProfile.website;

    // Corporate Social Channels (Inherited automatically from Company Profile if empty on staff)
    const compSoc = (typeof adminSettings !== 'undefined' && adminSettings.companyProfile && adminSettings.companyProfile.socialLinks)
      ? adminSettings.companyProfile.socialLinks
      : { whatsapp: '905362556424', telegram: 'muhiddinoktem', twitter: 'muhiddinoktem', linkedin: 'muhiddinoktem', facebook: 'muhiddinoktem', instagram: 'muhiddinoktem', youtube: 'vedubox' };

    const linkWhatsApp = document.getElementById('linkWhatsApp');
    const waVal = staffProfile.whatsapp || compSoc.whatsapp || '905362556424';
    if (linkWhatsApp) linkWhatsApp.href = `https://wa.me/${waVal.replace(/\D/g, '')}`;

    const linkTelegram = document.getElementById('linkTelegram');
    const tgVal = staffProfile.telegram || compSoc.telegram || 'muhiddinoktem';
    if (linkTelegram) linkTelegram.href = `https://t.me/${tgVal}`;

    const linkTwitter = document.getElementById('linkTwitter');
    const twVal = staffProfile.twitter || compSoc.twitter || 'muhiddinoktem';
    if (linkTwitter) linkTwitter.href = `https://x.com/${twVal}`;

    const linkLinkedIn = document.getElementById('linkLinkedIn');
    const liVal = staffProfile.linkedin || compSoc.linkedin || 'muhiddinoktem';
    if (linkLinkedIn) linkLinkedIn.href = liVal.startsWith('http') ? liVal : `https://linkedin.com/in/${liVal}`;

    const linkFacebook = document.getElementById('linkFacebook');
    const fbVal = staffProfile.facebook || compSoc.facebook || 'muhiddinoktem';
    if (linkFacebook) linkFacebook.href = fbVal.startsWith('http') ? fbVal : `https://facebook.com/${fbVal}`;

    const linkInstagram = document.getElementById('linkInstagram');
    const igVal = staffProfile.instagram || compSoc.instagram || 'muhiddinoktem';
    if (linkInstagram) linkInstagram.href = `https://instagram.com/${igVal}`;

    // Render Dynamic Products Vitrin on Cards
    renderCardProducts();
  }

  // Dynamic Product Rendering for Digital Business Cards (Inherited to all staff & manager)
  function renderCardProducts() {
    const grid = document.getElementById('cardProductsGrid');
    const countBadge = document.getElementById('cardProductsCountBadge');
    const prods = (typeof adminSettings !== 'undefined' && adminSettings.companyProfile && adminSettings.companyProfile.products)
      ? adminSettings.companyProfile.products
      : [
          { id: 'prod-1', name: 'Vedubox', subtitle: 'Online Eğitim & Akademi', url: 'https://vedubox.com', logo: 'vedubox.png' },
          { id: 'prod-2', name: 'Etgigrup', subtitle: 'Bilişim & Eğitim Teknolojileri', url: 'https://etgigrup.com', logo: 'etgigrup.png' }
        ];

    if (countBadge) {
      countBadge.textContent = `${prods.length} Marka / Ürün`;
    }

    if (grid) {
      if (prods.length === 0) {
        grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:16px;color:#94A3B8;font-size:12.5px;">Henüz şirket ürünü eklenmedi.</div>`;
        return;
      }

      grid.innerHTML = prods.map(p => {
        const isImg = p.logo && (p.logo.includes('.') || p.logo.startsWith('http'));
        return `
          <a href="${p.url || '#'}" target="_blank" rel="noopener noreferrer" class="product-card">
            <div class="product-card-top">
              <div class="product-logo-box">
                ${isImg 
                  ? `<img src="${p.logo}" alt="${p.name} Logo" class="product-brand-logo" onerror="this.parentElement.innerHTML='<span style=\\'font-weight:800;font-size:15px;color:var(--brand-color,#00A86B);\\'>${(p.name || 'M')[0]}</span>'">` 
                  : `<span style="font-weight:800;font-size:15px;color:var(--brand-color,#00A86B);">${(p.name || 'M').substring(0, 2).toUpperCase()}</span>`}
              </div>
              <span class="product-arrow">↗</span>
            </div>
            <span class="product-name">${p.name}</span>
            <span class="product-subtitle">${p.subtitle || ''}</span>
          </a>
        `;
      }).join('');
    }
  }

  applyProfileToUI();

  // =========================================================
  // 2. SAYFA GEZİNME SİSTEMİ (Page Navigation - Modal Değil!)
  // =========================================================
  const pages = {
    pageHome: document.getElementById('pageHome'),
    pageProfile: document.getElementById('pageProfile'),
    pageMeetings: document.getElementById('pageMeetings'),
    pageCalendar: document.getElementById('pageCalendar'),
    pageCrm: document.getElementById('pageCrm'),
    pageCrmDetail: document.getElementById('pageCrmDetail')
  };

  const headerBackBtn = document.getElementById('headerBackBtn');
  const headerSubpageTitle = document.getElementById('headerSubpageTitle');
  const navTabs = document.querySelectorAll('.nav-tab');
  const pagesViewport = document.getElementById('pagesViewport');

  // Lightweight Modals Helper
  const allModals = document.querySelectorAll('.modal-overlay');

  function openModal(modal) {
    if (!modal) return;
    closeAllModals();
    modal.classList.add('active');
  }

  function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('active');
  }

  function closeAllModals() {
    if (allModals) {
      allModals.forEach(m => m.classList.remove('active'));
    }
  }

  let navigationHistory = ['pageHome'];

  function navigateToPage(pageId, pushHistory = true) {
    if (!pages[pageId]) return;

    // Close any open modals
    closeAllModals();

    // Toggle active page view
    Object.values(pages).forEach(p => {
      if (p) p.classList.remove('active');
    });
    pages[pageId].classList.add('active');

    // Scroll viewport to top
    if (pagesViewport) pagesViewport.scrollTop = 0;

    if (pushHistory && pageId !== navigationHistory[navigationHistory.length - 1]) {
      navigationHistory.push(pageId);
    }

    // Update Header (Sadece Logo.svg gösterilir, Anasayfada MonaCard yazısı yok)
    if (pageId === 'pageHome') {
      if (headerBackBtn) headerBackBtn.classList.add('hidden');
      if (headerSubpageTitle) {
        headerSubpageTitle.classList.add('hidden');
        headerSubpageTitle.textContent = '';
      }
    } else {
      if (headerBackBtn) headerBackBtn.classList.remove('hidden');
      if (headerSubpageTitle) {
        headerSubpageTitle.classList.remove('hidden');
        if (pageId === 'pageProfile') headerSubpageTitle.textContent = 'Profil Düzenle';
        else if (pageId === 'pageMeetings') headerSubpageTitle.textContent = 'Toplantılar';
        else if (pageId === 'pageCalendar') headerSubpageTitle.textContent = 'Takvim';
        else if (pageId === 'pageCrm') headerSubpageTitle.textContent = 'CRM';
        else if (pageId === 'pageCrmDetail') headerSubpageTitle.textContent = 'Müşteri Kartı';
      }
    }

    // Update Bottom Nav Active State
    navTabs.forEach(tab => {
      const targetPage = tab.getAttribute('data-page');
      if (targetPage === pageId || (pageId === 'pageCrmDetail' && targetPage === 'pageCrm')) {
        tab.classList.add('active');
      } else {
        tab.classList.remove('active');
      }
    });
  }

  // Header Back Button
  if (headerBackBtn) {
    headerBackBtn.addEventListener('click', () => {
      if (navigationHistory.length > 1) {
        navigationHistory.pop(); // remove current
        const previousPage = navigationHistory[navigationHistory.length - 1];
        navigateToPage(previousPage, false);
      } else {
        navigateToPage('pageHome', false);
      }
    });
  }

  // Bottom Nav Bar click handlers
  navTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const targetPage = tab.getAttribute('data-page');
      if (targetPage) {
        if (targetPage === 'pageMeetings') renderMeetingsList();
        else if (targetPage === 'pageCalendar') renderRemindersList();
        else if (targetPage === 'pageCrm') renderCrmList();
        navigateToPage(targetPage);
      }
    });
  });

  // Header Profile Icon click -> Go to pageProfile
  const headerProfileBtn = document.getElementById('headerProfileBtn');
  if (headerProfileBtn) {
    headerProfileBtn.addEventListener('click', () => {
      // Populate profile edit form
      document.getElementById('editFullName').value = staffProfile.fullName;
      document.getElementById('editCompany').value = staffProfile.company;
      document.getElementById('editTitle').value = staffProfile.title;
      document.getElementById('editPhone').value = staffProfile.phone;
      document.getElementById('editEmail').value = staffProfile.email;
      document.getElementById('editWebsite').value = staffProfile.website;
      document.getElementById('editAddress').value = staffProfile.address;
      document.getElementById('editWhatsapp').value = staffProfile.whatsapp;
      document.getElementById('editTelegram').value = staffProfile.telegram;
      document.getElementById('editLinkedin').value = staffProfile.linkedin;
      document.getElementById('editTwitter').value = staffProfile.twitter;
      document.getElementById('editFacebook').value = staffProfile.facebook || '';
      document.getElementById('editInstagram').value = staffProfile.instagram || '';
      if (document.getElementById('editProdVedubox')) document.getElementById('editProdVedubox').value = staffProfile.prodVeduboxUrl || '';
      if (document.getElementById('editProdEtgigrup')) document.getElementById('editProdEtgigrup').value = staffProfile.prodEtgigrupUrl || '';

      navigateToPage('pageProfile');
    });
  }

  // Profile Edit Form Submit
  const profileEditForm = document.getElementById('profileEditForm');
  const btnCancelProfileEdit = document.getElementById('btnCancelProfileEdit');

  if (profileEditForm) {
    profileEditForm.addEventListener('submit', (e) => {
      e.preventDefault();
      staffProfile.fullName = document.getElementById('editFullName').value.trim();
      staffProfile.company = document.getElementById('editCompany').value.trim();
      staffProfile.title = document.getElementById('editTitle').value.trim();
      staffProfile.phone = document.getElementById('editPhone').value.trim();
      staffProfile.phoneClean = staffProfile.phone.replace(/[^\d+]/g, '');
      staffProfile.email = document.getElementById('editEmail').value.trim();
      staffProfile.website = document.getElementById('editWebsite').value.trim();
      staffProfile.address = document.getElementById('editAddress').value.trim();
      staffProfile.whatsapp = document.getElementById('editWhatsapp').value.trim();
      staffProfile.telegram = document.getElementById('editTelegram').value.trim();
      staffProfile.linkedin = document.getElementById('editLinkedin').value.trim();
      staffProfile.twitter = document.getElementById('editTwitter').value.trim();
      staffProfile.facebook = document.getElementById('editFacebook').value.trim();
      staffProfile.instagram = document.getElementById('editInstagram').value.trim();
      if (document.getElementById('editProdVedubox')) staffProfile.prodVeduboxUrl = document.getElementById('editProdVedubox').value.trim() || 'https://vedubox.com';
      if (document.getElementById('editProdEtgigrup')) staffProfile.prodEtgigrupUrl = document.getElementById('editProdEtgigrup').value.trim() || 'https://etgigrup.com';

      try {
        localStorage.setItem('monacard_staff_profile', JSON.stringify(staffProfile));
      } catch (err) {
        console.warn(err);
      }

      applyProfileToUI();
      navigateToPage('pageHome');
      showToast('Profil bilgileriniz başarıyla güncellendi! ✨');
    });
  }

  if (btnCancelProfileEdit) {
    btnCancelProfileEdit.addEventListener('click', () => {
      navigateToPage('pageHome');
    });
  }

  // =========================================================
  // ADMIN STAFF & EXECUTIVE DATA STRUCTURES
  // =========================================================
  const initialAdminStaff = [
    {
      id: "staff-1",
      name: "Muhiddin Öktem",
      title: "Senior Product Designer & Tech Lead",
      email: "muhiddinoktem@vedubox.com",
      phone: "+90 536 255 64 24",
      avatar: "avatar_clean.png",
      status: "active",
      isLeader: true,
      leaderId: "",
      monthlyTarget: 15,
      newLeads: 24,
      existingLeads: 10,
      totalContacts: 34,
      hotCount: 18,
      warmCount: 10,
      coldCount: 6,
      convertedCount: 12,
      revenue: 148500,
      satisfactionRate: 4.9,
      score: 96
    },
    {
      id: "staff-2",
      name: "Ali Rıza Çelik",
      title: "Kurumsal Müşteri Direktörü",
      email: "aliriza@vedubox.com",
      phone: "+90 532 987 65 43",
      avatar: "",
      status: "active",
      isLeader: true,
      leaderId: "",
      monthlyTarget: 15,
      newLeads: 20,
      existingLeads: 8,
      totalContacts: 28,
      hotCount: 14,
      warmCount: 8,
      coldCount: 6,
      convertedCount: 9,
      revenue: 215000,
      satisfactionRate: 4.8,
      score: 92
    },
    {
      id: "staff-3",
      name: "Ayşe Kaya",
      title: "B2B Portföy Yöneticisi",
      email: "ayse@vedubox.com",
      phone: "+90 533 123 45 67",
      avatar: "",
      status: "active",
      isLeader: false,
      leaderId: "staff-2",
      monthlyTarget: 12,
      newLeads: 15,
      existingLeads: 7,
      totalContacts: 22,
      hotCount: 9,
      warmCount: 8,
      coldCount: 5,
      convertedCount: 6,
      revenue: 94000,
      satisfactionRate: 4.7,
      score: 85
    },
    {
      id: "staff-4",
      name: "Burak Yılmaz",
      title: "Eğitim Teknolojileri Danışmanı",
      email: "burak@vedubox.com",
      phone: "+90 535 234 56 78",
      avatar: "",
      status: "active",
      isLeader: false,
      leaderId: "staff-2",
      monthlyTarget: 10,
      newLeads: 12,
      existingLeads: 7,
      totalContacts: 19,
      hotCount: 7,
      warmCount: 7,
      coldCount: 5,
      convertedCount: 5,
      revenue: 78000,
      satisfactionRate: 4.8,
      score: 82
    },
    {
      id: "staff-5",
      name: "Zeynep Arslan",
      title: "Akademi Projeleri Lideri",
      email: "zeynep@vedubox.com",
      phone: "+90 530 345 67 89",
      avatar: "",
      status: "active",
      isLeader: true,
      leaderId: "",
      monthlyTarget: 10,
      newLeads: 10,
      existingLeads: 6,
      totalContacts: 16,
      hotCount: 6,
      warmCount: 6,
      coldCount: 4,
      convertedCount: 4,
      revenue: 162000,
      satisfactionRate: 5.0,
      score: 89
    },
    {
      id: "staff-6",
      name: "Mert Demir",
      title: "Saha Müşteri Temsilcisi",
      email: "mert@vedubox.com",
      phone: "+90 536 456 78 90",
      avatar: "",
      status: "active",
      isLeader: false,
      leaderId: "staff-5",
      monthlyTarget: 8,
      newLeads: 9,
      existingLeads: 4,
      totalContacts: 13,
      hotCount: 5,
      warmCount: 5,
      coldCount: 3,
      convertedCount: 3,
      revenue: 64000,
      satisfactionRate: 4.6,
      score: 78
    },
    {
      id: "staff-7",
      name: "Elif Şahin",
      title: "Müşteri Başarı Uzmanı",
      email: "elif@vedubox.com",
      phone: "+90 537 567 89 01",
      avatar: "",
      status: "active",
      isLeader: false,
      leaderId: "staff-1",
      monthlyTarget: 8,
      newLeads: 8,
      existingLeads: 2,
      totalContacts: 10,
      hotCount: 4,
      warmCount: 4,
      coldCount: 2,
      convertedCount: 3,
      revenue: 48000,
      satisfactionRate: 4.9,
      score: 80
    },
    {
      id: "staff-8",
      name: "Caner Öz",
      title: "İş Geliştirme & Ortaklıklar",
      email: "caner@vedubox.com",
      phone: "+90 538 678 90 12",
      avatar: "",
      status: "active",
      isLeader: false,
      leaderId: "staff-1",
      monthlyTarget: 8,
      newLeads: 6,
      existingLeads: 2,
      totalContacts: 8,
      hotCount: 3,
      warmCount: 3,
      coldCount: 2,
      convertedCount: 2,
      revenue: 36000,
      satisfactionRate: 4.7,
      score: 75
    }
  ];

  let adminStaffList = [...initialAdminStaff];
  try {
    const savedStaff = localStorage.getItem('monacard_admin_staff');
    if (savedStaff) {
      const parsed = JSON.parse(savedStaff);
      // Ensure monthlyTarget exists for each
      adminStaffList = parsed.map((s, idx) => ({
        ...s,
        monthlyTarget: s.monthlyTarget || (initialAdminStaff[idx] ? initialAdminStaff[idx].monthlyTarget : 15)
      }));
    }
  } catch (e) {
    console.warn('Admin staff storage error', e);
  }

  function saveStaffToStorage() {
    try {
      localStorage.setItem('monacard_admin_staff', JSON.stringify(adminStaffList));
    } catch (e) {}
  }

  function saveAdminSettingsToStorage() {
    try {
      localStorage.setItem('monacard_admin_settings', JSON.stringify(adminSettings));
    } catch (e) {}
  }

  // Manager Personal VIP Meetings
  let adminPersonalMeetings = [
    {
      id: "meet-admin-1",
      title: "VIP Yatırımcı & Yönetim Kurulu Sunumu",
      date: "2026-09-24",
      time: "10:30",
      type: "Google Meet",
      participants: "Dr. Selim Bayraktar, Ayşe Hanım",
      isPast: false,
      isPersonal: true
    },
    {
      id: "meet-admin-2",
      title: "Bölge Satış Strateji Değerlendirmesi",
      date: "2026-09-26",
      time: "15:00",
      type: "Yüz Yüze",
      participants: "Ali Rıza Çelik, Zeynep Arslan",
      isPast: false,
      isPersonal: true
    }
  ];

  // 1. Apply Company Settings to Document Root & Themes
  function applyCompanySettingsToApp() {
    const root = document.documentElement;
    const color = adminSettings.brandColor || '#00A86B';
    root.style.setProperty('--brand-color', color);
    root.style.setProperty('--primary', color);
    root.style.setProperty('--admin-primary', color);

    // Apply dark/light mode class
    if (adminSettings.themeMode === 'dark') {
      document.body.classList.add('theme-dark');
      const sunIcon = document.querySelector('.sun-icon');
      const moonIcon = document.querySelector('.moon-icon');
      if (sunIcon) sunIcon.classList.add('hidden');
      if (moonIcon) moonIcon.classList.remove('hidden');
    } else {
      document.body.classList.remove('theme-dark');
      const sunIcon = document.querySelector('.sun-icon');
      const moonIcon = document.querySelector('.moon-icon');
      if (sunIcon) sunIcon.classList.remove('hidden');
      if (moonIcon) moonIcon.classList.add('hidden');
    }
  }

  // 2. Staff Interface Visibility Controller (Requirement 2)
  function applyStaffInterfaceVisibility() {
    const feat = adminSettings.staffFeatures;
    if (!feat) return;

    // HubSpot Sync
    document.querySelectorAll('.hubspot-sync-badge, .hubspot-sync-status-box').forEach(el => {
      el.style.display = feat.hubspot ? '' : 'none';
    });

    // Voice Notes
    const tabVoiceNote = document.getElementById('tabVoiceNote');
    const panelVoiceNote = document.getElementById('panelVoiceNote');
    if (tabVoiceNote) tabVoiceNote.style.display = feat.voiceNotes ? '' : 'none';
    if (!feat.voiceNotes && panelVoiceNote) {
      panelVoiceNote.classList.remove('active');
      const tabText = document.getElementById('tabTextNote');
      const panelText = document.getElementById('panelTextNote');
      if (tabText) tabText.classList.add('active');
      if (panelText) panelText.classList.add('active');
    }

    // Products & Services
    const prodSec = document.getElementById('productsSection');
    if (prodSec) prodSec.style.display = feat.products ? '' : 'none';

    // Social Links
    const socialGrid = document.querySelector('.compact-grid');
    if (socialGrid && socialGrid.closest('.section-block')) {
      socialGrid.closest('.section-block').style.display = feat.socialLinks ? '' : 'none';
    }

    // Reviews Section
    const reviewSec = document.getElementById('reviewSection');
    if (reviewSec) reviewSec.style.display = feat.reviews ? '' : 'none';

    // vCard Save Contact Button
    const btnSaveContact = document.getElementById('btnSaveContact');
    if (btnSaveContact) btnSaveContact.style.display = feat.vcard ? '' : 'none';
  }

  applyCompanySettingsToApp();
  applyStaffInterfaceVisibility();

  // =========================================================
  // 3. ROLE SWITCHER (1. Müşteri Ekranı vs 2. Personel Paneli vs 3. Firma Yöneticisi vs 4. Süper Admin)
  // =========================================================
  const btnRoleCustomer = document.getElementById('btnRoleCustomer');
  const btnRoleStaff = document.getElementById('btnRoleStaff');
  const btnRoleAdmin = document.getElementById('btnRoleAdmin');
  const btnRoleSuperAdmin = document.getElementById('btnRoleSuperAdmin');
  const btnAdminSwitchStaff = document.getElementById('btnAdminSwitchStaff');
  const btnSuperSwitchAdmin = document.getElementById('btnSuperSwitchAdmin');

  const urlParams = new URLSearchParams(window.location.search);
  let storedRole = null;
  try {
    storedRole = localStorage.getItem('monacard_role');
  } catch (e) {}
  let activeRole = urlParams.get('role') || storedRole || 'customer';

  function setRole(role, silent = false) {
    activeRole = role;
    try {
      localStorage.setItem('monacard_role', role);
    } catch (e) {}

    const reviewBtnText = document.getElementById('reviewBtnText');
    const reviewBtnShareIcon = document.getElementById('reviewBtnShareIcon');
    const btnLeaveReview = document.getElementById('btnLeaveReview');

    // Remove active from all role buttons
    if (btnRoleCustomer) btnRoleCustomer.classList.remove('active');
    if (btnRoleStaff) btnRoleStaff.classList.remove('active');
    if (btnRoleAdmin) btnRoleAdmin.classList.remove('active');
    if (btnRoleSuperAdmin) btnRoleSuperAdmin.classList.remove('active');

    // Apply company theme settings (dark mode, brand color, etc.)
    applyCompanySettingsToApp();

    if (role === 'customer') {
      const isDark = adminSettings.themeMode === 'dark';
      document.body.className = isDark ? 'role-customer theme-dark' : 'role-customer';
      if (btnRoleCustomer) btnRoleCustomer.classList.add('active');
      if (reviewBtnText) reviewBtnText.textContent = currentLang === 'en' ? 'Leave Review' : 'Yorum Bırak';
      if (reviewBtnShareIcon) reviewBtnShareIcon.style.display = 'none';
      if (btnLeaveReview) btnLeaveReview.title = currentLang === 'en' ? 'Leave Google Review' : 'Google Değerlendirmesi Bırak';
      const cancelledOverlay = document.getElementById('staffCancelledCardOverlay');
      if (cancelledOverlay) cancelledOverlay.classList.add('hidden');
      navigateToPage('pageHome');
      if (!silent) {
        showToast(currentLang === 'en' ? 'Client View Active (Simplified Card)' : 'Müşteri Ekranı Aktif (Sadeleştirilmiş Görünüm)');
      }
    } else if (role === 'staff') {
      const isDark = adminSettings.themeMode === 'dark';
      document.body.className = isDark ? 'role-staff theme-dark' : 'role-staff';
      if (btnRoleStaff) btnRoleStaff.classList.add('active');
      if (reviewBtnText) reviewBtnText.textContent = currentLang === 'en' ? 'Share' : 'Paylaş';
      if (reviewBtnShareIcon) reviewBtnShareIcon.style.display = 'inline-block';
      if (btnLeaveReview) btnLeaveReview.title = currentLang === 'en' ? 'Share Google Review Link' : 'Google Yorum Bağlantısını Paylaş';
      
      const activeStaff = adminStaffList.find(s => s.id === 'staff-1') || adminStaffList[0];
      if (activeStaff) {
        activeStaff.status = 'active';
        saveStaffToStorage();
      }
      const cancelledOverlay = document.getElementById('staffCancelledCardOverlay');
      if (cancelledOverlay) cancelledOverlay.classList.add('hidden');

      if (!silent) {
        showToast(currentLang === 'en' ? 'Staff Portal Active (All Management Features Enabled)' : 'Personel Paneli Aktif (Tüm Yönetim Özellikleri Açık)');
      }
      navigateToPage('pageHome');
    } else if (role === 'admin') {
      const isDark = adminSettings.themeMode === 'dark';
      document.body.className = isDark ? 'role-admin theme-dark' : 'role-admin';
      if (btnRoleAdmin) btnRoleAdmin.classList.add('active');
      const cancelledOverlay = document.getElementById('staffCancelledCardOverlay');
      if (cancelledOverlay) cancelledOverlay.classList.add('hidden');
      renderAdminAll();
      if (!silent) {
        showToast(currentLang === 'en' ? 'Company Administrator Portal Active 🏢💼' : 'Firma Yöneticisi Paneli Aktif 🏢💼');
      }
    } else if (role === 'superadmin') {
      const isDark = adminSettings.themeMode === 'dark';
      document.body.className = isDark ? 'role-superadmin theme-dark' : 'role-superadmin';
      if (btnRoleSuperAdmin) btnRoleSuperAdmin.classList.add('active');
      const cancelledOverlay = document.getElementById('staffCancelledCardOverlay');
      if (cancelledOverlay) cancelledOverlay.classList.add('hidden');
      renderSuperAll();
      if (!silent) {
        showToast(currentLang === 'en' ? 'Super Admin (SaaS Owner) Portal Active 👑🚀' : 'Süper Admin (SaaS Sahibi) Paneli Aktif 👑🚀');
      }
    }

    // Apply staff interface visibility restrictions set by manager
    applyStaffInterfaceVisibility();
  }

  if (btnRoleCustomer) {
    btnRoleCustomer.addEventListener('click', () => setRole('customer'));
  }
  if (btnRoleStaff) {
    btnRoleStaff.addEventListener('click', () => setRole('staff'));
  }
  if (btnRoleAdmin) {
    btnRoleAdmin.addEventListener('click', () => setRole('admin'));
  }
  if (btnRoleSuperAdmin) {
    btnRoleSuperAdmin.addEventListener('click', () => setRole('superadmin'));
  }
  if (btnAdminSwitchStaff) {
    btnAdminSwitchStaff.addEventListener('click', () => setRole('staff'));
  }
  if (btnSuperSwitchAdmin) {
    btnSuperSwitchAdmin.addEventListener('click', () => setRole('admin'));
  }

  const btnSwitchAdminFromCancelled = document.getElementById('btnSwitchAdminFromCancelled');
  if (btnSwitchAdminFromCancelled) {
    btnSwitchAdminFromCancelled.addEventListener('click', () => {
      setRole('admin');
    });
  }

  // =========================================================
  // 4. TOAST NOTIFICATIONS
  // =========================================================
  function showToast(message) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00A86B" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
      <span>${message}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      if (toast.parentNode) {
        toast.parentNode.removeChild(toast);
      }
    }, 3200);
  }

  // =========================================================
  // 5. VCARD (.VCF) GENERATOR (Tüm İletişim Kanallarını İçerir!)
  // =========================================================
  const btnSaveContact = document.getElementById('btnSaveContact');
  if (btnSaveContact) {
    btnSaveContact.addEventListener('click', downloadFullVCard);
  }

  function downloadFullVCard() {
    const cleanPhone = staffProfile.phoneClean;
    const cleanAddress = staffProfile.address.replace(/\n/g, ', ');
    const mapsUrl = `https://maps.google.com/?q=${encodeURIComponent(cleanAddress)}`;
    const whatsappUrl = `https://wa.me/${staffProfile.whatsapp.replace(/\D/g, '')}`;
    const telegramUrl = `https://t.me/${staffProfile.telegram}`;
    const linkedinUrl = `https://linkedin.com/in/${staffProfile.linkedin}`;
    const twitterUrl = `https://x.com/${staffProfile.twitter}`;
    const facebookUrl = `https://facebook.com/${staffProfile.facebook}`;
    const instagramUrl = `https://instagram.com/${staffProfile.instagram}`;

    const noteContent = [
      "MonaCard Akıllı Dijital Kartvizit",
      `Şirket: ${staffProfile.company}`,
      `Ünvan: ${staffProfile.title}`,
      `Telefon: ${staffProfile.phone}`,
      `E-Posta: ${staffProfile.email}`,
      `Web: ${staffProfile.website}`,
      `Adres: ${cleanAddress}`,
      `Harita: ${mapsUrl}`,
      `WhatsApp: ${whatsappUrl}`,
      `Telegram: ${telegramUrl}`,
      `LinkedIn: ${linkedinUrl}`,
      `X: ${twitterUrl}`,
      `Facebook: ${facebookUrl}`,
      `Instagram: ${instagramUrl}`
    ].join('\\n');

    const vcardLines = [
      'BEGIN:VCARD',
      'VERSION:3.0',
      'PRODID:-//MonaCard//Digital Business Card 2.0//TR',
      `N:Öktem;Muhiddin;;;`,
      `FN:${staffProfile.fullName}`,
      `ORG:${staffProfile.company}`,
      `TITLE:${staffProfile.title}`,
      `TEL;TYPE=CELL,VOICE,PREF:${cleanPhone}`,
      `TEL;TYPE=WORK,VOICE:${cleanPhone}`,
      `EMAIL;TYPE=WORK,INTERNET,PREF:${staffProfile.email}`,
      `URL;TYPE=WORK,PREF:${staffProfile.website}`,
      `ADR;TYPE=WORK,POSTAL,PARCEL:;;Dalgıç Sk. Yeşilce Mh. No 3;Kağıthane;İstanbul;34418;Turkey`,
      `URL;TYPE=LOCATION:${mapsUrl}`,
      `X-SOCIALPROFILE;TYPE=whatsapp:${whatsappUrl}`,
      `X-SOCIALPROFILE;TYPE=telegram:${telegramUrl}`,
      `X-SOCIALPROFILE;TYPE=linkedin:${linkedinUrl}`,
      `X-SOCIALPROFILE;TYPE=twitter:${twitterUrl}`,
      `X-SOCIALPROFILE;TYPE=facebook:${facebookUrl}`,
      `X-SOCIALPROFILE;TYPE=instagram:${instagramUrl}`,
      `NOTE;CHARSET=UTF-8:${noteContent}`,
      `REV:${new Date().toISOString()}`,
      'END:VCARD'
    ];

    const vcardContent = vcardLines.join('\r\n');
    const blob = new Blob([vcardContent], { type: 'text/vcard;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `${staffProfile.fullName.replace(/\s+/g, '_')}_MonaCard.vcf`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    showToast('Tüm iletişim kanallarıyla rehber kartı (.vcf) indirildi! 📱');
  }

  // Modal Event Listeners
  document.querySelectorAll('[data-close]').forEach(btn => {
    btn.addEventListener('click', () => {
      const modalId = btn.getAttribute('data-close');
      const target = document.getElementById(modalId);
      if (target) closeModal(target);
      else closeAllModals();
    });
  });

  allModals.forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal(modal);
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeAllModals();
    }
  });

  // Action buttons on Hero Card
  const btnQrModal = document.getElementById('btnQrModal');
  const qrModal = document.getElementById('qrModal');
  if (btnQrModal && qrModal) {
    btnQrModal.addEventListener('click', () => {
      renderQrCode();
      openModal(qrModal);
    });
  }

  const btnShareModal = document.getElementById('btnShareModal');
  const shareModal = document.getElementById('shareModal');
  if (btnShareModal && shareModal) {
    btnShareModal.addEventListener('click', async () => {
      if (navigator.share) {
        try {
          await navigator.share({
            title: `${staffProfile.fullName} - MonaCard Dijital Kartvizit`,
            text: `${staffProfile.fullName} (${staffProfile.title}) iletişim kartı:`,
            url: window.location.href
          });
          return;
        } catch (err) {}
      }
      openModal(shareModal);
    });
  }

  const btnLocationNav = document.getElementById('btnLocationNav');
  if (btnLocationNav) {
    btnLocationNav.addEventListener('click', () => {
      const q = encodeURIComponent(staffProfile.address.replace(/\n/g, ' '));
      window.open(`https://maps.google.com/?q=${q}`, '_blank');
      showToast('Harita lokasyonu açıldı 📍');
    });
  }

  const btnLeaveReview = document.getElementById('btnLeaveReview');
  const reviewModal = document.getElementById('reviewModal');
  if (btnLeaveReview) {
    btnLeaveReview.addEventListener('click', () => {
      if (activeRole === 'staff') {
        const shareModal = document.getElementById('shareModal');
        if (navigator.share) {
          navigator.share({
            title: `${staffProfile.fullName} - Google Değerlendirme`,
            text: `${staffProfile.fullName} için Google değerlendirmesi bağlantısı:`,
            url: window.location.href
          }).catch(() => {
            if (shareModal) openModal(shareModal);
          });
        } else if (shareModal) {
          openModal(shareModal);
        } else {
          showToast('Google Yorum bağlantısı paylaşıldı! 🌟');
        }
      } else {
        if (reviewModal) openModal(reviewModal);
      }
    });
  }

  // Google Değerlendirme İnteraktif Yıldız Seçimi
  let selectedReviewRating = 5;
  const starBtns = document.querySelectorAll('.star-rating-btn');
  const starContainer = document.getElementById('starRatingSelector');

  function updateStars(score, isHover = false) {
    starBtns.forEach(btn => {
      const s = parseInt(btn.getAttribute('data-score'), 10);
      if (s <= score) {
        btn.classList.add(isHover ? 'hover-active' : 'active');
      } else {
        btn.classList.remove(isHover ? 'hover-active' : 'active');
      }
    });
  }

  starBtns.forEach(btn => {
    btn.addEventListener('mouseenter', () => {
      const s = parseInt(btn.getAttribute('data-score'), 10);
      updateStars(s, true);
    });

    btn.addEventListener('click', (e) => {
      e.preventDefault();
      selectedReviewRating = parseInt(btn.getAttribute('data-score'), 10);
      starBtns.forEach(b => b.classList.remove('hover-active'));
      updateStars(selectedReviewRating, false);
      showToast(`${selectedReviewRating} Yıldız seçildi! ⭐`);
    });
  });

  if (starContainer) {
    starContainer.addEventListener('mouseleave', () => {
      starBtns.forEach(b => b.classList.remove('hover-active'));
      updateStars(selectedReviewRating, false);
    });
  }

  const btnSubmitReview = document.getElementById('btnSubmitReview');
  if (btnSubmitReview) {
    btnSubmitReview.addEventListener('click', () => {
      if (reviewModal) closeModal(reviewModal);
      const commentInput = document.getElementById('reviewComment');
      if (commentInput) commentInput.value = '';
      showToast(`Değerlendirmeniz için çok teşekkür ederiz! (${selectedReviewRating}/5 Yıldız) ⭐`);
    });
  }

  // QR Code Rendering
  function renderQrCode() {
    const container = document.getElementById('qrCodeContainer');
    if (!container) return;
    const qrTargetUrl = encodeURIComponent(window.location.href);
    container.innerHTML = `
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${qrTargetUrl}&color=00593b&bgcolor=ffffff" 
           alt="MonaCard QR Code" 
           id="generatedQrImg"
           style="width:100%; height:100%; object-fit:contain; border-radius:8px;"
           onerror="this.onerror=null; this.src='https://chart.googleapis.com/chart?chs=220x220&cht=qr&chl=${qrTargetUrl}&choe=UTF-8';">
    `;
  }

  const btnDownloadQr = document.getElementById('btnDownloadQr');
  if (btnDownloadQr) {
    btnDownloadQr.addEventListener('click', () => {
      const qrImg = document.getElementById('generatedQrImg');
      if (qrImg && qrImg.src) {
        const link = document.createElement('a');
        link.href = qrImg.src;
        link.download = `MonaCard_QR_${staffProfile.fullName}.png`;
        link.target = '_blank';
        link.click();
        showToast('Karekod indirme başlatıldı 📥');
      }
    });
  }

  const btnCopyProfileUrl = document.getElementById('btnCopyProfileUrl');
  if (btnCopyProfileUrl) btnCopyProfileUrl.addEventListener('click', copyLink);
  const btnCopyInline = document.getElementById('btnCopyInline');
  if (btnCopyInline) btnCopyInline.addEventListener('click', copyLink);

  function copyLink() {
    const url = window.location.href;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(url).then(() => showToast('Bağlantı panoya kopyalandı! 📋')).catch(() => fallbackCopy(url));
    } else {
      fallbackCopy(url);
    }
  }

  function fallbackCopy(text) {
    const input = document.createElement('textarea');
    input.value = text;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);
    showToast('Bağlantı panoya kopyalandı! 📋');
  }

  const shareWhatsApp = document.getElementById('shareWhatsApp');
  if (shareWhatsApp) {
    shareWhatsApp.addEventListener('click', () => {
      const text = encodeURIComponent(`Muhiddin Öktem'in dijital kartviziti:\n${window.location.href}`);
      window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
    });
  }

  // =========================================================
  // 7. CRM VERİ TABANI & MÜŞTERİLER (Sıcak, Ilık, Soğuk)
  // =========================================================
  const initialCustomers = [
    {
      id: "cust-1",
      name: "Kemal Yılmaz",
      company: "TechPlus Bilişim A.Ş.",
      title: "Genel Müdür",
      phone: "+90 532 111 22 33",
      email: "kemal@techplus.com",
      stage: "hot",
      initials: "KY",
      notes: [
        {
          id: "note-1",
          type: "voice",
          text: "Kemal Bey ile yüz yüze görüştük. 250 adet kurumsal MonaCard teklifi hazırlıyoruz. Haftaya Çarşamba sözleşme imzalanabilir.",
          duration: "00:18",
          time: "Dün 16:45",
          hubspotSynced: true
        },
        {
          id: "note-2",
          type: "text",
          text: "Kurumsal renk ve logo revizyonları tamamlandı. Fiyat teklifi PDF olarak iletildi.",
          time: "15 Eylül 11:20",
          hubspotSynced: true
        }
      ]
    },
    {
      id: "cust-2",
      name: "Selin Demir",
      company: "Finans Global Bank",
      title: "İnsan Kaynakları Direktörü",
      phone: "+90 533 444 55 66",
      email: "selin@finansglobal.com",
      stage: "warm",
      initials: "SD",
      notes: [
        {
          id: "note-3",
          type: "text",
          text: "Demo sunumu gerçekleştirildi. Yönetim kurulu onayına sunacaklarını bildirdiler.",
          time: "14 Eylül 14:10",
          hubspotSynced: true
        }
      ]
    },
    {
      id: "cust-3",
      name: "Zeynep Kaya",
      company: "Kaya Mimarlık & Tasarım",
      title: "Kurucu Ortak",
      phone: "+90 530 222 33 44",
      email: "zeynep@kayamimarlik.com",
      stage: "hot",
      initials: "ZK",
      notes: [
        {
          id: "note-4",
          type: "text",
          text: "Tasarım ekibi için 20 adet özel siyah mat NFC MonaCard talep edildi.",
          time: "12 Eylül 09:30",
          hubspotSynced: true
        }
      ]
    },
    {
      id: "cust-4",
      name: "Emre Can",
      company: "Delta Uluslararası Lojistik",
      title: "Operasyon Müdürü",
      phone: "+90 535 777 88 99",
      email: "emre@deltalojistik.com",
      stage: "cold",
      initials: "EC",
      notes: [
        {
          id: "note-5",
          type: "text",
          text: "Numune kartvizit kargolandı, takip numarası iletildi.",
          time: "10 Eylül 17:00",
          hubspotSynced: true
        }
      ]
    }
  ];

  let customers = [...initialCustomers];
  try {
    const savedCust = localStorage.getItem('monacard_crm_customers');
    if (savedCust) customers = JSON.parse(savedCust);
  } catch (e) {
    console.warn(e);
  }

  function saveCustomersToStorage() {
    try {
      localStorage.setItem('monacard_crm_customers', JSON.stringify(customers));
    } catch (e) {
      console.warn(e);
    }
  }

  let currentCrmFilter = 'all';
  let currentCompanyFilter = 'all';
  let activeSelectedCustomer = null;

  function populateCompanyFilter() {
    const filterSelect = document.getElementById('crmCompanyFilter');
    if (!filterSelect) return;
    const currentVal = filterSelect.value || 'all';
    const companies = Array.from(new Set(customers.map(c => c.company ? c.company.trim() : '').filter(Boolean))).sort();
    filterSelect.innerHTML = `<option value="all">Tüm Firmalar (Tümü)</option>` + 
      companies.map(comp => `<option value="${comp}">${comp}</option>`).join('');
    if (companies.includes(currentVal)) {
      filterSelect.value = currentVal;
    } else {
      filterSelect.value = 'all';
      currentCompanyFilter = 'all';
    }
  }

  function renderCrmList() {
    const container = document.getElementById('customerCardsContainer');
    const searchVal = (document.getElementById('crmSearchInput')?.value || '').toLowerCase().trim();

    if (!container) return;

    const filtered = customers.filter(c => {
      const matchStage = (currentCrmFilter === 'all') || (c.stage === currentCrmFilter);
      const matchCompany = (currentCompanyFilter === 'all') || (c.company && c.company.trim() === currentCompanyFilter);
      const matchSearch = c.name.toLowerCase().includes(searchVal) || (c.company && c.company.toLowerCase().includes(searchVal));
      return matchStage && matchCompany && matchSearch;
    });

    // Update Counts
    const countAll = document.getElementById('countAll');
    const countHot = document.getElementById('countHot');
    const countWarm = document.getElementById('countWarm');
    const countCold = document.getElementById('countCold');

    if (countAll) countAll.textContent = customers.length;
    if (countHot) countHot.textContent = customers.filter(c => c.stage === 'hot').length;
    if (countWarm) countWarm.textContent = customers.filter(c => c.stage === 'warm').length;
    if (countCold) countCold.textContent = customers.filter(c => c.stage === 'cold').length;

    if (filtered.length === 0) {
      container.innerHTML = `
        <div class="text-center py-8 text-muted" style="padding: 24px; font-size: 13px;">
          Bu filtrede henüz müşteri bulunmuyor.
        </div>
      `;
      return;
    }

    container.innerHTML = filtered.map(c => {
      const stagePill = c.stage === 'hot' 
        ? '<span class="customer-stage-pill hot" title="Sıcak Müşteri">🔥</span>' 
        : c.stage === 'warm' 
        ? '<span class="customer-stage-pill warm" title="Ilık Müşteri">⚡</span>' 
        : '<span class="customer-stage-pill cold" title="Soğuk Müşteri">❄️</span>';

      const lastNote = c.notes && c.notes.length > 0 ? c.notes[0].text : 'Henüz not düşülmedi.';

      return `
        <div class="customer-card-item" data-id="${c.id}">
          <div class="customer-card-left">
            <div class="customer-avatar-initials">${c.initials || c.name.substring(0,2).toUpperCase()}</div>
            <div class="customer-summary">
              <h5>${c.name}</h5>
              <p>${c.company}</p>
              <div class="last-note">${lastNote}</div>
            </div>
          </div>
          <div class="customer-card-right">
            ${stagePill}
            <span class="customer-card-arrow">➔</span>
          </div>
        </div>
      `;
    }).join('');

    container.querySelectorAll('.customer-card-item').forEach(item => {
      item.addEventListener('click', () => {
        const id = item.getAttribute('data-id');
        const found = customers.find(c => c.id === id);
        if (found) {
          openCustomerProfile(found);
        }
      });
    });

    const meetCustomerSelect = document.getElementById('meetCustomer');
    if (meetCustomerSelect) {
      meetCustomerSelect.innerHTML = customers.map(c => `
        <option value="${c.name} (${c.company})">${c.name} - ${c.company}</option>
      `).join('');
    }
  }

  // Filter Tabs
  document.querySelectorAll('.crm-filter-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.crm-filter-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      currentCrmFilter = tab.getAttribute('data-filter');
      renderCrmList();
    });
  });

  const crmCompanyFilter = document.getElementById('crmCompanyFilter');
  if (crmCompanyFilter) {
    crmCompanyFilter.addEventListener('change', (e) => {
      currentCompanyFilter = e.target.value;
      renderCrmList();
    });
  }

  const crmSearchInput = document.getElementById('crmSearchInput');
  if (crmSearchInput) crmSearchInput.addEventListener('input', renderCrmList);

  // New Customer Accordion
  const btnOpenNewCustomerForm = document.getElementById('btnOpenNewCustomerForm');
  const newCustomerBox = document.getElementById('newCustomerBox');
  const btnCancelNewCustomer = document.getElementById('btnCancelNewCustomer');
  const newCustomerDirectForm = document.getElementById('newCustomerDirectForm');

  if (btnOpenNewCustomerForm && newCustomerBox) {
    btnOpenNewCustomerForm.addEventListener('click', () => {
      newCustomerBox.classList.toggle('hidden');
    });
  }
  if (btnCancelNewCustomer && newCustomerBox) {
    btnCancelNewCustomer.addEventListener('click', () => {
      newCustomerBox.classList.add('hidden');
    });
  }

  if (newCustomerDirectForm) {
    newCustomerDirectForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const name = document.getElementById('ncName').value.trim();
      const company = document.getElementById('ncCompany').value.trim();
      const phone = document.getElementById('ncPhone').value.trim();
      const email = document.getElementById('ncEmail').value.trim();
      const stage = document.getElementById('ncStage').value;
      const title = document.getElementById('ncTitle').value.trim() || 'Yetkili';
      const initialNote = document.getElementById('ncInitialNote').value.trim();

      const initials = name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();

      const newCust = {
        id: "cust-" + Date.now(),
        name,
        company,
        title,
        phone,
        email,
        stage,
        initials,
        notes: initialNote ? [{
          id: "note-" + Date.now(),
          type: "text",
          text: initialNote,
          time: "Şimdi",
          hubspotSynced: true
        }] : []
      };

      customers.unshift(newCust);
      saveCustomersToStorage();
      newCustomerDirectForm.reset();
      newCustomerBox.classList.add('hidden');
      populateCompanyFilter();
      renderCrmList();
      showToast(`${name} CRM'e kaydedildi ve HubSpot'a aktarıldı! 🚀`);
    });
  }

  // =========================================================
  // 8. MÜŞTERİ KARTVİZİT DETAY SAYFASI (pageCrmDetail)
  // =========================================================
  function openCustomerProfile(cust) {
    activeSelectedCustomer = cust;

    document.getElementById('custProfName').textContent = cust.name;
    document.getElementById('custProfInitials').textContent = cust.initials || cust.name.substring(0, 2).toUpperCase();
    document.getElementById('custCardFullName').textContent = cust.name;
    document.getElementById('custCardCompany').textContent = cust.company;
    document.getElementById('custCardTitle').textContent = cust.title || 'Müşteri';
    
    document.getElementById('custPhoneLabel').textContent = cust.phone;
    document.getElementById('custCallBtn').href = `tel:${cust.phone.replace(/[^\d+]/g, '')}`;

    document.getElementById('custEmailLabel').textContent = cust.email || 'E-posta belirtilmedi';
    document.getElementById('custMailBtn').href = cust.email ? `mailto:${cust.email}` : '#';

    const stagePill = document.getElementById('custProfStage');
    if (cust.stage === 'hot') {
      stagePill.className = 'customer-stage-pill hot';
      stagePill.textContent = '🔥 Sıcak Müşteri';
    } else if (cust.stage === 'warm') {
      stagePill.className = 'customer-stage-pill warm';
      stagePill.textContent = '⚡ Ilık Müşteri';
    } else {
      stagePill.className = 'customer-stage-pill cold';
      stagePill.textContent = '❄️ Soğuk Müşteri';
    }

    const custStatusSelect = document.getElementById('custStatusSelect');
    if (custStatusSelect) custStatusSelect.value = cust.stage;

    renderNotesTimeline();
    navigateToPage('pageCrmDetail');
  }

  const custStatusSelect = document.getElementById('custStatusSelect');
  if (custStatusSelect) {
    custStatusSelect.addEventListener('change', (e) => {
      if (!activeSelectedCustomer) return;
      activeSelectedCustomer.stage = e.target.value;
      saveCustomersToStorage();
      renderCrmList();

      const stagePill = document.getElementById('custProfStage');
      if (activeSelectedCustomer.stage === 'hot') {
        stagePill.className = 'customer-stage-pill hot';
        stagePill.textContent = '🔥 Sıcak Müşteri';
      } else if (activeSelectedCustomer.stage === 'warm') {
        stagePill.className = 'customer-stage-pill warm';
        stagePill.textContent = '⚡ Ilık Müşteri';
      } else {
        stagePill.className = 'customer-stage-pill cold';
        stagePill.textContent = '❄️ Soğuk Müşteri';
      }

      showToast(`Müşteri durumu güncellendi ve HubSpot'a iletildi! 🔄`);
    });
  }

  function renderNotesTimeline() {
    const container = document.getElementById('customerNotesTimeline');
    const countLabel = document.getElementById('notesCountLabel');
    if (!container || !activeSelectedCustomer) return;

    const notes = activeSelectedCustomer.notes || [];
    if (countLabel) countLabel.textContent = `${notes.length} Not`;

    if (notes.length === 0) {
      container.innerHTML = `
        <div class="text-center py-6 text-muted" style="padding: 16px; font-size: 12px;">
          Henüz bu müşteriye not eklenmemiş. Yukarıdan sesli veya yazılı not düşebilirsiniz.
        </div>
      `;
      return;
    }

    container.innerHTML = notes.map(n => {
      const isVoice = n.type === 'voice';
      return `
        <div class="note-history-card">
          <div class="note-history-top">
            <span class="note-type-tag ${isVoice ? 'voice' : 'text'}">
              ${isVoice ? '🎙️ Sesli Not' : '✏️ Yazılı Not'}
            </span>
            <span class="note-time">${n.time || 'Bugün'}</span>
          </div>
          <p class="note-body-text">${n.text}</p>
          ${isVoice ? `
            <div class="voice-player-bar">
              <button class="voice-play-btn" onclick="alert('Ses kaydı oynatılıyor: ${n.duration || '00:15'}')">▶</button>
              <div class="voice-progress-track"><div class="voice-progress-fill"></div></div>
              <span style="font-size: 11px; font-family: monospace; color:#64748B;">${n.duration || '00:15'}</span>
            </div>
          ` : ''}
          <div class="hubspot-synced-confirmation">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            <span>HubSpot CRM'e senkronize edildi</span>
          </div>
        </div>
      `;
    }).join('');
  }

  // Note Tabs
  const tabTextNote = document.getElementById('tabTextNote');
  const tabVoiceNote = document.getElementById('tabVoiceNote');
  const panelTextNote = document.getElementById('panelTextNote');
  const panelVoiceNote = document.getElementById('panelVoiceNote');

  if (tabTextNote && tabVoiceNote) {
    tabTextNote.addEventListener('click', () => {
      tabTextNote.classList.add('active');
      tabVoiceNote.classList.remove('active');
      panelTextNote.classList.add('active');
      panelVoiceNote.classList.remove('active');
    });

    tabVoiceNote.addEventListener('click', () => {
      tabVoiceNote.classList.add('active');
      tabTextNote.classList.remove('active');
      panelVoiceNote.classList.add('active');
      panelTextNote.classList.remove('active');
    });
  }

  // Save Text Note
  const btnSaveTextNote = document.getElementById('btnSaveTextNote');
  const textNoteInput = document.getElementById('textNoteInput');

  if (btnSaveTextNote && textNoteInput) {
    btnSaveTextNote.addEventListener('click', () => {
      const text = textNoteInput.value.trim();
      if (!text) {
        showToast('Lütfen bir not yazın!');
        return;
      }
      if (!activeSelectedCustomer) return;

      const newNote = {
        id: "note-" + Date.now(),
        type: "text",
        text: text,
        time: "Bugün " + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        hubspotSynced: true
      };

      activeSelectedCustomer.notes.unshift(newNote);
      saveCustomersToStorage();
      textNoteInput.value = '';
      renderNotesTimeline();
      renderCrmList();
      showToast("Yazılı not HubSpot CRM'e senkronize edildi! 📝");
    });
  }

  // Voice Recording
  const btnToggleRecord = document.getElementById('btnToggleRecord');
  const voiceRecorderBox = document.querySelector('.voice-recorder-box');
  const voiceTimer = document.getElementById('voiceTimer');
  const voiceStatusText = document.getElementById('voiceStatusText');
  const btnSaveVoiceNote = document.getElementById('btnSaveVoiceNote');

  let isRecording = false;
  let recordSeconds = 0;
  let recordInterval = null;

  if (btnToggleRecord) {
    btnToggleRecord.addEventListener('click', () => {
      if (!isRecording) {
        isRecording = true;
        recordSeconds = 0;
        voiceRecorderBox.classList.add('recording');
        voiceStatusText.textContent = 'Ses kaydediliyor... Konuşun';
        btnSaveVoiceNote.classList.add('hidden');

        recordInterval = setInterval(() => {
          recordSeconds++;
          const mins = String(Math.floor(recordSeconds / 60)).padStart(2, '0');
          const secs = String(recordSeconds % 60).padStart(2, '0');
          voiceTimer.textContent = `${mins}:${secs}`;
        }, 1000);
      } else {
        isRecording = false;
        clearInterval(recordInterval);
        voiceRecorderBox.classList.remove('recording');
        voiceStatusText.textContent = `Ses kaydı tamamlandı (${voiceTimer.textContent})`;
        btnSaveVoiceNote.classList.remove('hidden');
      }
    });
  }

  if (btnSaveVoiceNote) {
    btnSaveVoiceNote.addEventListener('click', () => {
      if (!activeSelectedCustomer) return;

      const duration = voiceTimer.textContent;
      const newVoiceNote = {
        id: "note-" + Date.now(),
        type: "voice",
        text: `Müşteri görüşmesi ses kaydı (${duration})`,
        duration: duration,
        time: "Bugün " + new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        hubspotSynced: true
      };

      activeSelectedCustomer.notes.unshift(newVoiceNote);
      saveCustomersToStorage();
      renderNotesTimeline();
      renderCrmList();

      voiceTimer.textContent = '00:00';
      voiceStatusText.textContent = 'Ses kaydı almak için mikrofon butonuna basın';
      btnSaveVoiceNote.classList.add('hidden');

      showToast("Ses kaydı HubSpot CRM'e aktarıldı! 🎙️🚀");
    });
  }

  // =========================================================
  // 9. TOPLANTI YÖNETİMİ (pageMeetings)
  // =========================================================
  const defaultMeetings = [
    {
      id: "meet-1",
      title: "Vedubox Dijital Dönüşüm Sunumu",
      customer: "Kemal Yılmaz (TechPlus), Selin Demir (Finans Global)",
      date: "2026-09-18",
      time: "14:00",
      type: "Google Meet",
      completed: false
    },
    {
      id: "meet-2",
      title: "MonaCard Entegrasyon Değerlendirmesi",
      customer: "Selin Demir (Finans Global)",
      date: "2026-09-19",
      time: "11:30",
      type: "Zoom Meet",
      completed: false
    },
    {
      id: "meet-3",
      title: "Kurumsal Sözleşme & Kart Numuneleri",
      customer: "Zeynep Kaya (Kaya Mimarlık)",
      date: "2026-09-21",
      time: "15:00",
      type: "Yüz Yüze",
      completed: false
    },
    {
      id: "meet-past-1",
      title: "Ön Tanışma & İhtiyaç Analizi",
      customer: "Emre Can (Delta Uluslararası Lojistik)",
      date: "2026-09-10",
      time: "10:30",
      type: "Google Meet",
      completed: true,
      isPast: true
    },
    {
      id: "meet-past-2",
      title: "Prototip Demo & Geri Bildirim Toplantısı",
      customer: "Zeynep Kaya (Kaya Mimarlık & Tasarım), Kemal Yılmaz (TechPlus Bilişim A.Ş.)",
      date: "2026-09-05",
      time: "13:00",
      type: "Zoom Meet",
      completed: true,
      isPast: true
    }
  ];

  let meetings = [...defaultMeetings];
  try {
    const savedM = localStorage.getItem('monacard_meetings');
    if (savedM) {
      const saved = JSON.parse(savedM);
      // Merge: keep saved meetings + add any new defaults not in saved
      const savedIds = new Set(saved.map(m => m.id));
      const newDefaults = defaultMeetings.filter(m => !savedIds.has(m.id));
      meetings = [...saved, ...newDefaults];
    }
  } catch (e) {
    console.warn(e);
  }

  function saveMeetingsToStorage() {
    try {
      localStorage.setItem('monacard_meetings', JSON.stringify(meetings));
    } catch (e) {
      console.warn(e);
    }
  }

  // Google Meet, Zoom Meet ve Yüz Yüze (2'li User İkonu) SVG ve Stilleri
  function getMeetingPlatformData(type) {
    const t = (type || '').toLowerCase();
    if (t.includes('zoom')) {
      return {
        class: 'zoom',
        label: 'Zoom Meet',
        svg: `
          <svg width="26" height="26" viewBox="0 0 48 48" fill="none">
            <rect width="48" height="48" rx="12" fill="#2D8CFF"/>
            <path d="M12 18C12 16.3431 13.3431 15 15 15H26C27.6569 15 29 16.3431 29 18V30C29 31.6569 27.6569 33 26 33H15C13.3431 33 12 31.6569 12 30V18Z" fill="white"/>
            <path d="M30.5 21.2L36 17.5C36.6 17.1 37.5 17.5 37.5 18.3V29.7C37.5 30.5 36.6 30.9 36 30.5L30.5 26.8V21.2Z" fill="white"/>
          </svg>
        `
      };
    } else if (t.includes('google') || t.includes('meet')) {
      return {
        class: 'meet',
        label: 'Google Meet',
        svg: `
          <svg width="26" height="26" viewBox="0 0 48 48" fill="none">
            <path d="M42 14.5L34 20.5V13C34 11.3431 32.6569 10 31 10H7C5.34315 10 4 11.3431 4 13V35C4 36.6569 5.34315 38 7 38H31C32.6569 38 34 36.6569 34 35V27.5L42 33.5C43.1046 34.3284 44 33.8807 44 32.5V15.5C44 14.1193 43.1046 13.6716 42 14.5Z" fill="#00832D"/>
            <path d="M34 27.5V35C34 36.6569 32.6569 38 31 38H7C5.34315 38 4 36.6569 4 35V30L19 22L34 27.5Z" fill="#0066DA"/>
            <path d="M4 17L19 25L34 19.5V13C34 11.3431 32.6569 10 31 10H7C5.34315 10 4 11.3431 4 13V17Z" fill="#E53935"/>
            <path d="M4 17V30L19 23.5L4 17Z" fill="#FBBC04"/>
            <path d="M34 20.5L42 14.5C43.1046 13.6716 44 14.1193 44 15.5V32.5C44 33.8807 43.1046 34.3284 42 33.5L34 27.5V20.5Z" fill="#00AC47"/>
          </svg>
        `
      };
    } else {
      // Yüz yüze veya Müşteri Ofisi: 2'li user ikonu
      return {
        class: 'face',
        label: type || 'Yüz Yüze',
        svg: `
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4338CA" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        `
      };
    }
  }

  // =========================================================
  // TOPLANTI ZAMAN KONTROLÜ (Otomatik Geçmiş Toplantı Belirleme)
  // =========================================================
  function isMeetingPast(m) {
    if (!m || !m.date) return false;
    try {
      const timeStr = m.time ? (m.time.length === 5 ? m.time + ':00' : m.time) : '23:59:00';
      const meetDateTime = new Date(`${m.date}T${timeStr}`);
      if (isNaN(meetDateTime.getTime())) {
        const todayStr = new Date().toISOString().split('T')[0];
        return m.date < todayStr;
      }
      return meetDateTime.getTime() < Date.now();
    } catch (e) {
      return false;
    }
  }

  // =========================================================
  // KATILIMCI LİSTESİ: FİRMAYA GÖRE GRUPLU VE YAN YANA SEÇİM LİSTESİ
  // =========================================================
  function renderGroupedCustomerChecklist(dropdownEl, selectedCustomers, onToggleCallback) {
    if (!dropdownEl || !customers) return;

    // Şirketlere göre grupla
    const grouped = {};
    customers.forEach(c => {
      const comp = c.company || 'Diğer / Bireysel';
      if (!grouped[comp]) grouped[comp] = [];
      grouped[comp].push(c);
    });

    let html = '';
    for (const [company, members] of Object.entries(grouped)) {
      html += `
        <div class="company-group-block">
          <div class="company-group-title">🏢 ${company}</div>
          <div class="company-group-members">
            ${members.map(c => {
              const isChecked = selectedCustomers.some(sc => sc.id === c.id);
              return `
                <label class="grouped-participant-row">
                  <input type="checkbox" data-id="${c.id}" ${isChecked ? 'checked' : ''}>
                  <span class="participant-name-title">
                    <span>${c.name}</span>
                    <span style="font-weight: 500; color: #64748B; font-size: 11px;">(${c.company})</span>
                  </span>
                </label>
              `;
            }).join('')}
          </div>
        </div>
      `;
    }

    dropdownEl.innerHTML = html;

    dropdownEl.querySelectorAll('input[type="checkbox"]').forEach(cb => {
      cb.addEventListener('change', (e) => {
        const id = e.target.getAttribute('data-id');
        const cust = customers.find(c => c.id === id);
        if (cust && onToggleCallback) {
          onToggleCallback(cust, e.target.checked);
        }
      });
    });
  }

  // =========================================================
  // YENİ TOPLANTI: ÇOKLU KATILIMCI SEÇİCİ (DROPDOWN)
  // =========================================================
  let newMeetingSelectedCustomers = [];
  let newSelectListenersAttached = false;

  function populateMeetingCustomerSelect() {
    if (window.renderNewMeetingDropdown) {
      window.renderNewMeetingDropdown();
    }
  }

  function initNewMeetingParticipantsMultiSelect() {
    const toggle = document.getElementById('newMeetParticipantsToggle');
    const dropdown = document.getElementById('newMeetParticipantsDropdown');
    const chipsContainer = document.getElementById('newMeetSelectedChips');
    const placeholder = document.getElementById('newMeetParticipantsPlaceholder');
    const countBadge = document.getElementById('newMeetSelectedCount');

    if (!toggle || !dropdown) return;

    window.renderNewMeetingDropdown = function() {
      renderGroupedCustomerChecklist(dropdown, newMeetingSelectedCustomers, (cust, isChecked) => {
        if (isChecked) {
          if (!newMeetingSelectedCustomers.some(sc => sc.id === cust.id)) {
            newMeetingSelectedCustomers.push(cust);
          }
        } else {
          newMeetingSelectedCustomers = newMeetingSelectedCustomers.filter(sc => sc.id !== cust.id);
        }
        updateNewMeetingChips();
      });
    };

    window.updateNewMeetingChips = function() {
      if (chipsContainer) {
        chipsContainer.innerHTML = newMeetingSelectedCustomers.map(c => `
          <span class="participant-chip">
            <span>${c.name}</span>
            <button type="button" class="participant-chip-remove" data-id="${c.id}" title="Kaldır">&times;</button>
          </span>
        `).join('');

        chipsContainer.querySelectorAll('.participant-chip-remove').forEach(btn => {
          btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const id = btn.getAttribute('data-id');
            newMeetingSelectedCustomers = newMeetingSelectedCustomers.filter(c => c.id !== id);
            renderNewMeetingDropdown();
            updateNewMeetingChips();
          });
        });
      }

      if (countBadge && placeholder) {
        if (newMeetingSelectedCustomers.length > 0) {
          countBadge.textContent = `${newMeetingSelectedCustomers.length} Seçili`;
          countBadge.classList.remove('hidden');
          placeholder.textContent = `${newMeetingSelectedCustomers.length} Katılımcı seçildi`;
        } else {
          countBadge.classList.add('hidden');
          placeholder.textContent = "Katılımcıları seçin (Çoklu seçim)...";
        }
      }
    };

    window.renderNewMeetingDropdown();

    if (newSelectListenersAttached) return;
    newSelectListenersAttached = true;

    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      e.preventDefault();
      dropdown.classList.toggle('hidden');
    });

    dropdown.addEventListener('click', (e) => {
      e.stopPropagation();
    });

    document.addEventListener('click', (e) => {
      if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });
  }

  // =========================================================
  // TOPLANTI DETAY & DÜZENLEME MODALI (DROPDOWN SELECT İLE)
  // =========================================================
  let activeMeetingIndex = null;
  let editModalSelectedCustomers = [];
  let editSelectListenersAttached = false;

  function initEditMeetingParticipantsMultiSelect() {
    const toggle = document.getElementById('editMeetParticipantsToggle');
    const dropdown = document.getElementById('editMeetParticipantsDropdown');
    if (!toggle || !dropdown || editSelectListenersAttached) return;

    editSelectListenersAttached = true;
    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      e.preventDefault();
      dropdown.classList.toggle('hidden');
    });

    dropdown.addEventListener('click', (e) => {
      e.stopPropagation();
    });

    document.addEventListener('click', (e) => {
      if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });
  }

  function updateEditMeetingChips() {
    const chipsContainer = document.getElementById('editMeetSelectedChips');
    const placeholder = document.getElementById('editMeetParticipantsPlaceholder');
    const countBadge = document.getElementById('editMeetSelectedCount');
    const dropdown = document.getElementById('editMeetParticipantsDropdown');

    if (chipsContainer) {
      chipsContainer.innerHTML = editModalSelectedCustomers.map(c => `
        <span class="participant-chip">
          <span>${c.name}</span>
          <button type="button" class="participant-chip-remove" data-id="${c.id}" title="Kaldır">&times;</button>
        </span>
      `).join('');

      chipsContainer.querySelectorAll('.participant-chip-remove').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.stopPropagation();
          const id = btn.getAttribute('data-id');
          editModalSelectedCustomers = editModalSelectedCustomers.filter(c => c.id !== id);
          if (dropdown) {
            renderGroupedCustomerChecklist(dropdown, editModalSelectedCustomers, onEditParticipantToggle);
          }
          updateEditMeetingChips();
        });
      });
    }

    if (countBadge && placeholder) {
      if (editModalSelectedCustomers.length > 0) {
        countBadge.textContent = `${editModalSelectedCustomers.length} Seçili`;
        countBadge.classList.remove('hidden');
        placeholder.textContent = `${editModalSelectedCustomers.length} Katılımcı seçildi`;
      } else {
        countBadge.classList.add('hidden');
        placeholder.textContent = "Katılımcı seçin...";
      }
    }
  }

  function onEditParticipantToggle(cust, isChecked) {
    if (isChecked) {
      if (!editModalSelectedCustomers.some(sc => sc.id === cust.id)) {
        editModalSelectedCustomers.push(cust);
      }
    } else {
      editModalSelectedCustomers = editModalSelectedCustomers.filter(sc => sc.id !== cust.id);
    }
    updateEditMeetingChips();
  }

  window.openMeetingDetail = function(index) {
    const m = meetings[index];
    if (!m) return;
    activeMeetingIndex = index;

    const modalTitle = document.getElementById('meetDetTitle');
    if (modalTitle) modalTitle.textContent = 'Toplantı Detayı & Düzenle';

    const pData = getMeetingPlatformData(m.type);
    const logoBox = document.getElementById('meetDetPlatformLogo');
    if (logoBox) {
      logoBox.style.display = 'flex';
      logoBox.className = `meeting-platform-logo-box ${pData.class}`;
      logoBox.innerHTML = pData.svg;
    }

    const titleInput = document.getElementById('editMeetTitle');
    if (titleInput) titleInput.value = m.title;

    const isPast = isMeetingPast(m);
    const statusBadge = document.getElementById('meetDetStatusBadge');
    if (statusBadge) {
      statusBadge.textContent = isPast ? 'Zamanı Geçti' : 'Planlandı';
      statusBadge.className = isPast ? 'meeting-status-badge done' : 'meeting-status-badge';
    }
    const badgeWrapper = document.getElementById('meetDetStatusBadgeWrapper');
    if (badgeWrapper) {
      badgeWrapper.style.display = 'flex';
    }

    const dateInput = document.getElementById('editMeetDate');
    if (dateInput) dateInput.value = m.date || new Date().toISOString().split('T')[0];

    const timeInput = document.getElementById('editMeetTime');
    if (timeInput) timeInput.value = m.time || '14:00';

    const typeSelect = document.getElementById('editMeetType');
    if (typeSelect) typeSelect.value = m.type || 'Google Meet';

    // Katılımcılar Çoklu Seçim Dropdown (Select ile)
    initEditMeetingParticipantsMultiSelect();

    // Mevcut toplantıdaki katılımcıları eşleştir
    editModalSelectedCustomers = customers.filter(c => m.customer && m.customer.includes(c.name));
    if (editModalSelectedCustomers.length === 0 && m.customer && customers.length > 0) {
      editModalSelectedCustomers = [customers[0]];
    }

    const editDropdown = document.getElementById('editMeetParticipantsDropdown');
    if (editDropdown) {
      editDropdown.classList.add('hidden');
      renderGroupedCustomerChecklist(editDropdown, editModalSelectedCustomers, onEditParticipantToggle);
    }

    updateEditMeetingChips();

    const meetingDetailModal = document.getElementById('meetingDetailModal');
    if (meetingDetailModal) openModal(meetingDetailModal);
  };

  // Yeni Toplantı Oluşturma Modalı Açıcı (Takvim, CRM veya Dashboard üzerinden)
  window.openCreateMeetingModal = function(defaultTitle = "", defaultDate = "", defaultCust = null) {
    activeMeetingIndex = null;

    const modalTitle = document.getElementById('meetDetTitle');
    if (modalTitle) modalTitle.textContent = 'Yeni Toplantı Planla';

    const titleInput = document.getElementById('editMeetTitle');
    if (titleInput) {
      titleInput.value = defaultTitle;
      titleInput.placeholder = "Toplantı Konusu / Başlığı";
    }

    const dateInput = document.getElementById('editMeetDate');
    if (dateInput) dateInput.value = defaultDate || new Date().toISOString().split('T')[0];

    const timeInput = document.getElementById('editMeetTime');
    if (timeInput) timeInput.value = '14:00';

    const typeSelect = document.getElementById('editMeetType');
    if (typeSelect) typeSelect.value = 'Google Meet';

    // Yeni oluştururken meet logosu ve planlandı etiketi gizlensin, sadece başlık kalsın
    const logoBox = document.getElementById('meetDetPlatformLogo');
    if (logoBox) {
      logoBox.style.display = 'none';
      logoBox.innerHTML = '';
    }

    const badgeWrapper = document.getElementById('meetDetStatusBadgeWrapper');
    if (badgeWrapper) {
      badgeWrapper.style.display = 'none';
    }

    initEditMeetingParticipantsMultiSelect();

    // Set selected customers
    editModalSelectedCustomers = defaultCust ? [defaultCust] : [];

    const editDropdown = document.getElementById('editMeetParticipantsDropdown');
    if (editDropdown) {
      editDropdown.classList.add('hidden');
      renderGroupedCustomerChecklist(editDropdown, editModalSelectedCustomers, onEditParticipantToggle);
    }

    updateEditMeetingChips();

    const meetingDetailModal = document.getElementById('meetingDetailModal');
    if (meetingDetailModal) openModal(meetingDetailModal);
  };

  // Toplantı Değişikliklerini Kaydet Butonu (Zamanı Geçti Otomatik Hesaplanır)
  const btnSaveMeetingChanges = document.getElementById('btnSaveMeetingChanges');
  if (btnSaveMeetingChanges) {
    btnSaveMeetingChanges.addEventListener('click', () => {
      const title = document.getElementById('editMeetTitle').value.trim() || "Toplantı";
      const date = document.getElementById('editMeetDate').value || new Date().toISOString().split('T')[0];
      const time = document.getElementById('editMeetTime').value || "14:00";
      const type = document.getElementById('editMeetType').value || "Google Meet";

      const customerNames = editModalSelectedCustomers.length > 0
        ? editModalSelectedCustomers.map(c => `${c.name} (${c.company})`).join(', ')
        : "Belirtilmedi";

      if (activeMeetingIndex !== null && meetings[activeMeetingIndex]) {
        // Düzenleme
        meetings[activeMeetingIndex].title = title;
        meetings[activeMeetingIndex].date = date;
        meetings[activeMeetingIndex].time = time;
        meetings[activeMeetingIndex].type = type;
        meetings[activeMeetingIndex].customer = customerNames;
        meetings[activeMeetingIndex].isPast = isMeetingPast({ date, time });
        meetings[activeMeetingIndex].completed = meetings[activeMeetingIndex].isPast;
        showToast("Toplantı detayları güncellendi! 💾✅");
      } else {
        // Yeni Toplantı Ekleme
        const newMeeting = {
          id: "meet-" + Date.now(),
          title: title,
          date: date,
          time: time,
          type: type,
          customer: customerNames,
          isPast: isMeetingPast({ date, time }),
          completed: false,
          notes: []
        };
        meetings.unshift(newMeeting);
        if (typeof adminPersonalMeetings !== 'undefined') {
          adminPersonalMeetings.unshift({
            id: newMeeting.id,
            title: title,
            date: date,
            time: time,
            type: type,
            participants: customerNames,
            isPast: newMeeting.isPast,
            isPersonal: true
          });
        }
        showToast("Yeni toplantı başarıyla oluşturuldu ve ajandaya eklendi! 📅✨");
      }

      saveMeetingsToStorage();
      renderMeetingsList();
      if (typeof renderAdminCalendar === 'function') renderAdminCalendar();
      if (typeof renderAdminDashboard === 'function') renderAdminDashboard();
      closeModal(document.getElementById('meetingDetailModal'));
    });
  }

  // Katılımcılara Hatırlat Butonu (Birden Fazla Katılımcıya Mail)
  const btnSendMeetingReminderMail = document.getElementById('btnSendMeetingReminderMail');
  if (btnSendMeetingReminderMail) {
    btnSendMeetingReminderMail.addEventListener('click', () => {
      if (activeMeetingIndex === null || !meetings[activeMeetingIndex]) return;
      const m = meetings[activeMeetingIndex];

      // Katılımcı e-postalarını topla
      const participantEmails = editModalSelectedCustomers.length > 0
        ? editModalSelectedCustomers.map(c => c.email).filter(Boolean)
        : customers.filter(c => m.customer && m.customer.includes(c.name)).map(c => c.email).filter(Boolean);

      const emailList = participantEmails.length > 0 ? participantEmails.join(',') : 'iletisim@sirket.com';
      const participantNames = editModalSelectedCustomers.length > 0
        ? editModalSelectedCustomers.map(c => c.name).join(', ')
        : m.customer;

      const subject = `Toplantı Hatırlatması: ${m.title}`;
      const body = `Merhaba Sayın ${participantNames},\n\n` +
        `${m.date} saat ${m.time} için planlanan "${m.title}" konulu toplantımızı hatırlatmak isteriz.\n\n` +
        `Toplantı Şekli / Platform: ${m.type}\n\n` +
        `Katılım durumunuzu teyit etmenizi rica ederiz.\n\n` +
        `İyi çalışmalar dileriz,\n` +
        `${staffProfile.fullName} | ${staffProfile.company}`;

      window.open(`mailto:${emailList}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`, '_blank');
      closeModal(document.getElementById('meetingDetailModal'));
      showToast(`Tüm katılımcılara (${participantEmails.length || 1} kişi) hatırlatıcı e-posta hazırlandı! ✉️`);
    });
  }

  // =========================================================
  // TOPLANTI LİSTESİ RENDER (Geçmiş Toplantılar Otomatik Soluk Gri Zeminli)
  // =========================================================
  function renderMeetingsList() {
    const container = document.getElementById('meetingsListContainer');
    const badge = document.getElementById('meetingCountBadge');
    if (!container) return;

    if (badge) badge.textContent = `${meetings.length} Toplantı`;

    if (meetings.length === 0) {
      container.innerHTML = `<p class="text-muted text-center py-4" style="font-size:12px;">Henüz planlanmış toplantı yok.</p>`;
      return;
    }

    container.innerHTML = meetings.map((m, index) => {
      const pData = getMeetingPlatformData(m.type);
      const isPast = isMeetingPast(m);
      const cardClass = isPast ? 'meeting-card-item past-meeting' : 'meeting-card-item';
      const pastTag = isPast ? '<span class="past-status-tag">Zamanı Geçti</span>' : '';

      return `
        <div class="${cardClass}" onclick="window.openMeetingDetail(${index})" title="Detayları görüntülemek, düzenlemek ve hatırlatıcı göndermek için tıklayın">
          <!-- Üst Alan: Platform Logosu, Toplantı Konusu ve Müşteri -->
          <div class="meeting-card-top">
            <div class="meeting-platform-logo-box ${pData.class}" title="${pData.label}">
              ${pData.svg}
            </div>
            <div class="meeting-card-info">
              <h5 class="meeting-title" title="${m.title}">${m.title}</h5>
              <div class="meeting-customer">
                ${(m.customer || '').split(',').map(person => person.trim()).filter(Boolean).map(person => `
                  <span class="meeting-customer-row">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>${person}</span>
                  </span>
                `).join('')}
              </div>
            </div>
          </div>

          <!-- En Alt Alan: Tarih ve Toplantı Şekli (Sıkışmayı Önler) -->
          <div class="meeting-card-footer">
            <div class="meeting-date-time">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span>${m.date} • ${m.time}</span>
            </div>
            <div class="flex items-center gap-1">
              ${pastTag}
              <span class="meeting-type-badge ${pData.class}">
                ${pData.label}
              </span>
            </div>
          </div>
        </div>
      `;
    }).join('');
  }

  // Yeni Toplantı Formu Submit İşleyicisi
  const newMeetingForm = document.getElementById('newMeetingForm');
  if (newMeetingForm) {
    const meetDateInput = document.getElementById('meetDate');
    if (meetDateInput) {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      meetDateInput.value = tomorrow.toISOString().split('T')[0];
    }

    initNewMeetingParticipantsMultiSelect();

    newMeetingForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const title = document.getElementById('meetTitle').value.trim();
      const type = document.getElementById('meetType').value;
      const date = document.getElementById('meetDate').value;
      const time = document.getElementById('meetTime').value;

      if (newMeetingSelectedCustomers.length === 0) {
        showToast("Lütfen en az bir katılımcı / müşteri seçin! 👥");
        return;
      }

      const customerNames = newMeetingSelectedCustomers.map(c => `${c.name} (${c.company})`).join(', ');

      meetings.unshift({
        id: "meet-" + Date.now(),
        title,
        customer: customerNames,
        type,
        date,
        time,
        isPast: isMeetingPast({ date, time }),
        completed: isMeetingPast({ date, time })
      });

      saveMeetingsToStorage();
      renderMeetingsList();

      newMeetingForm.reset();
      newMeetingSelectedCustomers = [];
      updateNewMeetingChips();
      renderNewMeetingDropdown();

      showToast(`"${title}" toplantısı takvime eklendi! 📅`);
    });
  }

  // =========================================================
  // 10. TAKVİM & HATIRLATICILAR (pageCalendar)
  // =========================================================
  const defaultReminders = [
    {
      id: "rem-1",
      title: "Kemal Bey'e revize kurumsal teklif dosyasını gönder",
      date: "Yarın",
      priority: "high",
      completed: false
    },
    {
      id: "rem-2",
      title: "Selin Hanım ile toplantı öncesi sunum slaytlarını kontrol et",
      date: "Cuma",
      priority: "medium",
      completed: false
    },
    {
      id: "rem-3",
      title: "HubSpot senkronizasyon raporunu incele",
      date: "Haftaya Pazartesi",
      priority: "low",
      completed: true
    }
  ];

  let reminders = [...defaultReminders];
  try {
    const savedR = localStorage.getItem('monacard_reminders');
    if (savedR) reminders = JSON.parse(savedR);
  } catch (e) {
    console.warn(e);
  }

  let activeReminderForDetail = null;

  function renderRemindersList() {
    const container = document.getElementById('remindersListContainer');
    const badge = document.getElementById('reminderCountBadge');
    if (!container) return;

    if (badge) badge.textContent = `${reminders.length} Hatırlatıcı`;

    if (reminders.length === 0) {
      container.innerHTML = `<p class="text-muted text-center py-4" style="font-size:12px;">Henüz hatırlatıcı bulunmuyor.</p>`;
      return;
    }

    container.innerHTML = reminders.map((r, index) => {
      const isDone = !!r.completed;
      const pLevel = r.priority || 'medium';
      const pLabel = pLevel === 'high' ? 'Acil' : pLevel === 'medium' ? 'Normal' : 'Düşük';

      return `
        <div class="reminder-item-card ${isDone ? 'is-completed' : ''}">
          <!-- Check Solda (Basınca tikli hale gelir) -->
          <button type="button" class="reminder-check-btn ${isDone ? 'checked' : ''}" onclick="window.toggleReminderComplete(${index}, event)" title="${isDone ? 'Tamamlandı (Geri al)' : 'Tamamla'}" aria-label="Görevi Tamamla">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </button>

          <!-- Başlığa tıklayınca detay görünsün -->
          <div class="reminder-content-wrap" onclick="window.openReminderDetail(${index})">
            <div class="reminder-card-title ${isDone ? 'line-through' : ''}">${r.title}</div>
            <div class="reminder-card-date">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span>${r.date}</span>
              <span class="click-detail-hint">• Detay</span>
            </div>
          </div>

          <!-- Öncelik seviyesi kartların sağ üstünde -->
          <span class="reminder-priority-badge ${pLevel}">${pLabel}</span>
        </div>
      `;
    }).join('');
  }

  window.toggleReminderComplete = function(index, event) {
    if (event) event.stopPropagation();
    if (!reminders[index]) return;
    reminders[index].completed = !reminders[index].completed;
    try {
      localStorage.setItem('monacard_reminders', JSON.stringify(reminders));
    } catch (e) {}
    renderRemindersList();
    showToast(reminders[index].completed ? 'Hatırlatıcı tamamlandı! ✅' : 'Hatırlatıcı tekrar aktif edildi! 🔄');
  };

  window.openReminderDetail = function(index) {
    const r = reminders[index];
    if (!r) return;
    activeReminderForDetail = { ...r, index };

    const hl = document.getElementById('remDetHeadline');
    if (hl) hl.textContent = r.title;

    const dt = document.getElementById('remDetDate');
    if (dt) dt.textContent = r.date;

    const pb = document.getElementById('remDetPriorityBadge');
    if (pb) {
      pb.className = `reminder-modal-priority-badge ${r.priority || 'medium'}`;
      pb.textContent = r.priority === 'high' ? 'Acil Öncelik' : r.priority === 'medium' ? 'Normal Öncelik' : 'Düşük Öncelik';
    }

    const sb = document.getElementById('remDetStatusBadge');
    if (sb) {
      sb.textContent = r.completed ? '✅ Tamamlandı' : '⏳ Bekliyor';
      sb.className = r.completed ? 'rem-status-pill done' : 'rem-status-pill';
    }

    const toggleBtn = document.getElementById('btnToggleReminderModal');
    if (toggleBtn) {
      if (r.completed) {
        toggleBtn.innerHTML = `
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="1 4 1 10 7 10"></polyline>
            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
          </svg>
          <span>Görevi Aktif Et</span>
        `;
      } else {
        toggleBtn.innerHTML = `
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
          <span>Tamamlandı</span>
        `;
      }
    }

    const reminderDetailModal = document.getElementById('reminderDetailModal');
    if (reminderDetailModal) openModal(reminderDetailModal);
  };

  const btnToggleReminderModal = document.getElementById('btnToggleReminderModal');
  if (btnToggleReminderModal) {
    btnToggleReminderModal.addEventListener('click', () => {
      if (!activeReminderForDetail) return;
      window.toggleReminderComplete(activeReminderForDetail.index);
      closeModal(document.getElementById('reminderDetailModal'));
    });
  }

  const newReminderForm = document.getElementById('newReminderForm');
  if (newReminderForm) {
    const remDate = document.getElementById('remDate');
    if (remDate) {
      remDate.value = new Date().toISOString().split('T')[0];
    }

    newReminderForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const title = document.getElementById('remText').value.trim();
      const date = document.getElementById('remDate').value;
      const priority = document.getElementById('remPriority').value;

      reminders.unshift({
        id: "rem-" + Date.now(),
        title,
        date,
        priority
      });

      try {
        localStorage.setItem('monacard_reminders', JSON.stringify(reminders));
      } catch (err) {}

      renderRemindersList();
      newReminderForm.reset();
      showToast('Yeni hatırlatıcı ajandaya kaydedildi! ⏰');
    });
  }

  // =========================================================
  // 11. FLOATING ACTION BUTTON (FAB) - KARTVİZİT BİLGİSİ AL MODALI
  // =========================================================
  const cardCaptureModal = document.getElementById('cardCaptureModal');
  const mainFabBtn = document.getElementById('mainFabBtn');

  if (mainFabBtn && cardCaptureModal) {
    mainFabBtn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      openModal(cardCaptureModal);
    });
  }

  // a: NFC ile Oku
  const btnCaptureNfc = document.getElementById('btnCaptureNfc');
  if (btnCaptureNfc) {
    btnCaptureNfc.addEventListener('click', () => {
      closeModal(cardCaptureModal);
      const newCust = {
        id: "cust-" + Date.now(),
        name: "Burak Özdemir",
        company: "NFC Tech İnovasyon Ltd.",
        title: "Teknoloji Direktörü (CTO)",
        phone: "+90 535 777 88 99",
        email: "burak@nfctech.com.tr",
        stage: "hot",
        initials: "BÖ",
        notes: [
          {
            id: "note-" + Date.now(),
            type: "text",
            text: "NFC MonaCard ile temassız okutuldu. Kurumsal 150 adet kartvizit talebi var.",
            time: "Şimdi",
            hubspotSynced: true
          }
        ]
      };
      customers.unshift(newCust);
      saveCustomersToStorage();
      populateCompanyFilter();
      renderCrmList();
      populateMeetingCustomerSelect();
      openCustomerProfile(newCust);
      showToast("NFC ile Kartvizit Okundu! Müşteri CRM'e aktarıldı. 📇⚡");
    });
  }

  // QR Kod Oku
  const btnCaptureQr = document.getElementById('btnCaptureQr');
  if (btnCaptureQr) {
    btnCaptureQr.addEventListener('click', () => {
      closeModal(cardCaptureModal);
      const newCust = {
        id: "cust-" + Date.now(),
        name: "Deniz Arda",
        company: "Arda Mimarlık & Tasarım",
        title: "Baş Mimar & Tasarımcı",
        phone: "+90 542 888 99 00",
        email: "deniz@ardatasarim.com",
        stage: "warm",
        initials: "DA",
        notes: [
          {
            id: "note-" + Date.now(),
            type: "text",
            text: "Kamera ile müşteri kartvizit QR kodu taranarak CRM'e aktarıldı.",
            time: "Şimdi",
            hubspotSynced: true
          }
        ]
      };
      customers.unshift(newCust);
      saveCustomersToStorage();
      populateCompanyFilter();
      renderCrmList();
      populateMeetingCustomerSelect();
      openCustomerProfile(newCust);
      showToast("QR Kod Okundu! Müşteri kartviziti CRM'e eklendi. 📱✨");
    });
  }

  // OCR ile Oku
  const btnCaptureOcr = document.getElementById('btnCaptureOcr');
  if (btnCaptureOcr) {
    btnCaptureOcr.addEventListener('click', () => {
      closeModal(cardCaptureModal);
      const newCust = {
        id: "cust-" + Date.now(),
        name: "Kaan Yurtseven",
        company: "Yurtseven Danışmanlık",
        title: "Yönetici Ortak",
        phone: "+90 533 999 11 22",
        email: "kaan@yurtsevendanismanlik.com",
        stage: "hot",
        initials: "KY",
        notes: [
          {
            id: "note-" + Date.now(),
            type: "text",
            text: "Fiziksel kağıt kartvizit kamera OCR ile tarandı, isim, şirket ve iletişim bilgileri otomatik ayrıştırıldı.",
            time: "Şimdi",
            hubspotSynced: true
          }
        ]
      };
      customers.unshift(newCust);
      saveCustomersToStorage();
      populateCompanyFilter();
      renderCrmList();
      populateMeetingCustomerSelect();
      openCustomerProfile(newCust);
      showToast("OCR ile Kartvizit Tarandı! Bilgiler CRM'e kaydedildi. 🔍✨");
    });
  }

  // Manuel Ekle
  const btnCaptureManual = document.getElementById('btnCaptureManual');
  if (btnCaptureManual) {
    btnCaptureManual.addEventListener('click', () => {
      closeModal(cardCaptureModal);
      navigateToPage('pageCrm');
      const newCustomerBox = document.getElementById('newCustomerBox');
      if (newCustomerBox) {
        newCustomerBox.classList.remove('hidden');
        const inputName = document.getElementById('newCustName');
        if (inputName) {
          setTimeout(() => inputName.focus(), 150);
        }
      }
      showToast("Manuel kartvizit / müşteri ekleme formu açıldı 📝");
    });
  }

  // =========================================================
  // ADMIN PANEL CONTROLLER & VIEW LOGIC
  // =========================================================
  const adminViews = {
    viewAdminDashboard: document.getElementById('viewAdminDashboard'),
    viewAdminStaff: document.getElementById('viewAdminStaff'),
    viewAdminStaffDetail: document.getElementById('viewAdminStaffDetail'),
    viewAdminCrm: document.getElementById('viewAdminCrm'),
    viewAdminCustomerDetail: document.getElementById('viewAdminCustomerDetail'),
    viewAdminCalendar: document.getElementById('viewAdminCalendar'),
    viewAdminSettings: document.getElementById('viewAdminSettings'),
    viewAdminProfile: document.getElementById('viewAdminProfile')
  };

  const adminNavItems = document.querySelectorAll('.admin-nav-item');
  let currentStaffStatusFilter = 'all';
  let currentCalendarTab = 'all';
  let currentCalendarViewMode = 'month'; // 'month' or 'cards'
  let currentStaffDetailId = 'staff-1';
  let currentStaffDetailPeriod = 'month'; // 'week', 'month', '30days', '3months', 'all'
  let calCurrentYear = 2026;
  let calCurrentMonth = 8; // 0-indexed: 8 is September (Eylül)

  const turkishMonths = [
    'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
    'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
  ];

  // Admin View Navigation
  function navigateToAdminView(viewId) {
    if (!adminViews[viewId]) return;

    Object.values(adminViews).forEach(v => {
      if (v) v.classList.remove('active');
    });
    adminViews[viewId].classList.add('active');

    adminNavItems.forEach(item => {
      const itemTarget = item.getAttribute('data-admin-view');
      if (itemTarget === viewId || 
         (viewId === 'viewAdminStaffDetail' && itemTarget === 'viewAdminStaff') ||
         (viewId === 'viewAdminCustomerDetail' && itemTarget === 'viewAdminCrm')) {
        item.classList.add('active');
      } else {
        item.classList.remove('active');
      }
    });

    // Specific view renders
    if (viewId === 'viewAdminDashboard') renderAdminDashboard();
    if (viewId === 'viewAdminStaff') renderAdminStaffTable();
    if (viewId === 'viewAdminStaffDetail') renderStaffDetailPage();
    if (viewId === 'viewAdminCrm') renderAdminCrmTable();
    if (viewId === 'viewAdminCalendar') renderAdminCalendar();
    if (viewId === 'viewAdminSettings') renderAdminSettings();
    if (viewId === 'viewAdminProfile') renderAdminProfile();
    // Close mobile drawer on navigation
    toggleAdminMobileSidebar(false);
  }

  // Admin Mobile Drawer Toggles
  const adminSidebar = document.getElementById('adminSidebar');
  const btnToggleMobileAdminSidebar = document.getElementById('btnToggleMobileAdminSidebar');
  const adminMobileBackdrop = document.getElementById('adminMobileBackdrop');

  function toggleAdminMobileSidebar(open) {
    if (!adminSidebar) return;
    const shouldOpen = open !== undefined ? open : !adminSidebar.classList.contains('mobile-open');
    if (shouldOpen) {
      adminSidebar.classList.add('mobile-open');
      if (adminMobileBackdrop) adminMobileBackdrop.classList.add('active');
    } else {
      adminSidebar.classList.remove('mobile-open');
      if (adminMobileBackdrop) adminMobileBackdrop.classList.remove('active');
    }
  }

  if (btnToggleMobileAdminSidebar) {
    btnToggleMobileAdminSidebar.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleAdminMobileSidebar();
    });
  }
  if (adminMobileBackdrop) {
    adminMobileBackdrop.addEventListener('click', () => toggleAdminMobileSidebar(false));
  }

  adminNavItems.forEach(item => {
    item.addEventListener('click', () => {
      const viewId = item.getAttribute('data-admin-view');
      navigateToAdminView(viewId);
    });
  });

  // Topbar Dark Mode Toggle Button
  const btnAdminThemeToggle = document.getElementById('btnAdminThemeToggle');
  if (btnAdminThemeToggle) {
    btnAdminThemeToggle.addEventListener('click', () => {
      adminSettings.themeMode = adminSettings.themeMode === 'dark' ? 'light' : 'dark';
      saveAdminSettingsToStorage();
      applyCompanySettingsToApp();
      renderAdminSettings();
      showToast(adminSettings.themeMode === 'dark' ? '🌙 Karanlık Mod Aktif' : '☀️ Aydınlık Mod Aktif');
    });
  }

  // Topbar Profile Pill Trigger -> Navigates to Admin Profile
  const adminUserBadgeTrigger = document.getElementById('adminUserBadgeTrigger');
  if (adminUserBadgeTrigger) {
    adminUserBadgeTrigger.addEventListener('click', () => {
      navigateToAdminView('viewAdminProfile');
    });
  }

  // Topbar Global Search
  const adminGlobalSearch = document.getElementById('adminGlobalSearch');
  if (adminGlobalSearch) {
    adminGlobalSearch.addEventListener('input', (e) => {
      const query = e.target.value.trim().toLowerCase();
      if (!query) return;

      // Filter staff and CRM
      if (adminViews.viewAdminStaff && adminViews.viewAdminStaff.classList.contains('active')) {
        const staffSearch = document.getElementById('staffSearchInput');
        if (staffSearch) {
          staffSearch.value = query;
          renderAdminStaffTable();
        }
      } else if (adminViews.viewAdminCrm && adminViews.viewAdminCrm.classList.contains('active')) {
        const crmSearch = document.getElementById('adminCrmSearchInput');
        if (crmSearch) {
          crmSearch.value = query;
          renderAdminCrmTable();
        }
      }
    });
  }

  // Topbar Date Filter
  const adminDateFilter = document.getElementById('adminDateFilter');
  if (adminDateFilter) {
    adminDateFilter.addEventListener('change', () => {
      renderAdminDashboard();
      showToast(`Veriler "${adminDateFilter.options[adminDateFilter.selectedIndex].text}" aralığına göre güncellendi 📊`);
    });
  }

  // 1. DASHBOARD RENDERER
  function renderAdminDashboard() {
    const totalRev = adminStaffList.reduce((acc, s) => acc + (s.revenue || 0), 0);
    const totalNewLeads = adminStaffList.reduce((acc, s) => acc + (s.newLeads || 0), 0);
    const totalContacts = adminStaffList.reduce((acc, s) => acc + (s.totalContacts || 0), 0);
    const totalHot = adminStaffList.reduce((acc, s) => acc + (s.hotCount || 0), 0);
    const totalWarm = adminStaffList.reduce((acc, s) => acc + (s.warmCount || 0), 0);
    const totalCold = adminStaffList.reduce((acc, s) => acc + (s.coldCount || 0), 0);
    const monthMeetingsCount = meetings.length + adminPersonalMeetings.length;

    const statHotCustomers = document.getElementById('statHotCustomers');
    const statWarmCustomers = document.getElementById('statWarmCustomers');
    const statNewLeads = document.getElementById('statNewLeads');
    const statMonthMeetings = document.getElementById('statMonthMeetings');

    if (statHotCustomers) statHotCustomers.textContent = totalHot || 125;
    if (statWarmCustomers) statWarmCustomers.textContent = totalWarm || 51;
    if (statNewLeads) statNewLeads.textContent = totalNewLeads || 104;
    if (statMonthMeetings) statMonthMeetings.textContent = monthMeetingsCount || 18;

    // Admin greeting name
    const greetingName = document.getElementById('adminGreetingName');
    const headerName = document.getElementById('adminHeaderName');
    if (greetingName && adminSettings.companyProfile) {
      greetingName.textContent = adminSettings.companyProfile.managerName.split(' ')[0] || "Yönetici";
    }
    if (headerName && adminSettings.companyProfile) {
      headerName.textContent = adminSettings.companyProfile.managerName || "Muhiddin Öktem";
    }

    // Mini Widgets
    const miniUpcomingMeetings = document.getElementById('miniUpcomingMeetings');
    const miniSatisfaction = document.getElementById('miniSatisfaction');
    if (miniUpcomingMeetings) miniUpcomingMeetings.textContent = monthMeetingsCount;
    if (miniSatisfaction) miniSatisfaction.textContent = "4.8 / 5";

    // Update Donut Chart (Sıcak, Ilık, Soğuk Dağılımı %100)
    const sumAll = totalHot + totalWarm + totalCold || 1;

    const pctHot = Math.round((totalHot / sumAll) * 100);
    const pctWarm = Math.round((totalWarm / sumAll) * 100);
    const pctCold = Math.max(0, 100 - (pctHot + pctWarm));

    const isEn = currentLang === 'en';
    const donutTotalCountLabel = document.getElementById('donutTotalCountLabel');
    if (donutTotalCountLabel) donutTotalCountLabel.textContent = isEn ? `Total: ${sumAll} Records` : `Toplam: ${sumAll} Kayıt`;

    const legendHotVal = document.getElementById('legendHotVal');
    const legendWarmVal = document.getElementById('legendWarmVal');
    const legendColdVal = document.getElementById('legendColdVal');

    if (legendHotVal) legendHotVal.textContent = `${pctHot}% (${totalHot})`;
    if (legendWarmVal) legendWarmVal.textContent = `${pctWarm}% (${totalWarm})`;
    if (legendColdVal) legendColdVal.textContent = `${pctCold}% (${totalCold})`;

    // Dynamic Donut SVG Slices Calculation
    const C = 364.4;
    const segHot = document.getElementById('donutSegHot');
    const segWarm = document.getElementById('donutSegWarm');
    const segCold = document.getElementById('donutSegCold');

    const lHot = (pctHot / 100) * C;
    const lWarm = (pctWarm / 100) * C;
    const lCold = (pctCold / 100) * C;

    if (segHot) {
      segHot.setAttribute('stroke-dasharray', `${lHot} ${C}`);
      segHot.setAttribute('stroke-dashoffset', '0');
    }
    if (segWarm) {
      segWarm.setAttribute('stroke-dasharray', `${lWarm} ${C}`);
      segWarm.setAttribute('stroke-dashoffset', `${-lHot}`);
    }
    if (segCold) {
      segCold.setAttribute('stroke-dasharray', `${lCold} ${C}`);
      segCold.setAttribute('stroke-dashoffset', `${-(lHot + lWarm)}`);
    }

    const donutDataMap = {
      hot: { pct: pctHot, count: totalHot, label: isEn ? "🔥 Hot Lead" : "🔥 Sıcak Müşteri", shortLabel: isEn ? "🔥 Hot" : "🔥 Sıcak", color: "#EF4444" },
      warm: { pct: pctWarm, count: totalWarm, label: isEn ? "⚡ Warm Lead" : "⚡ Ilık Müşteri", shortLabel: isEn ? "⚡ Warm" : "⚡ Ilık", color: "#F59E0B" },
      cold: { pct: pctCold, count: totalCold, label: isEn ? "❄️ Cold Lead" : "❄️ Soğuk Müşteri", shortLabel: isEn ? "❄️ Cold" : "❄️ Soğuk", color: "#3B82F6" }
    };

    window.updateDonutSelection = function(type) {
      window.currentSelectedDonutType = type;
      const d = donutDataMap[type] || donutDataMap.hot;
      const donutCenterVal = document.getElementById('donutHotPercent');
      const donutCenterSub = document.getElementById('donutCenterSub');

      if (donutCenterVal) {
        donutCenterVal.textContent = `${d.pct}%`;
        donutCenterVal.style.color = d.color;
      }
      if (donutCenterSub) {
        donutCenterSub.textContent = d.shortLabel;
      }

      ['hot', 'warm', 'cold'].forEach(t => {
        const seg = document.getElementById(`donutSeg${t.charAt(0).toUpperCase() + t.slice(1)}`);
        const leg = document.getElementById(`donutLegend${t.charAt(0).toUpperCase() + t.slice(1)}`);
        if (seg) {
          if (t === type) seg.classList.add('active');
          else seg.classList.remove('active');
        }
        if (leg) {
          if (t === type) leg.classList.add('active');
          else leg.classList.remove('active');
        }
      });
    };

    // Attach click event listeners
    ['hot', 'warm', 'cold'].forEach(t => {
      const seg = document.getElementById(`donutSeg${t.charAt(0).toUpperCase() + t.slice(1)}`);
      const leg = document.getElementById(`donutLegend${t.charAt(0).toUpperCase() + t.slice(1)}`);
      if (seg) seg.onclick = () => window.updateDonutSelection(t);
      if (leg) leg.onclick = () => window.updateDonutSelection(t);
    });

    window.updateDonutSelection(window.currentSelectedDonutType || 'hot');

    // Top 3 Performing Personnel Widget (Sıcak Görüşme & Müşteri)
    const topPerformersList = document.getElementById('topPerformersList');
    if (topPerformersList) {
      const top3 = [...adminStaffList]
        .sort((a, b) => ((b.score || 0) * 1000 + (b.totalContacts || 0)) - ((a.score || 0) * 1000 + (a.totalContacts || 0)))
        .slice(0, 3);

      topPerformersList.innerHTML = top3.map((s, idx) => {
        const rankClass = idx === 0 ? 'rank-1' : idx === 1 ? 'rank-2' : 'rank-3';
        const rankIcon = idx === 0 ? '🥇' : idx === 1 ? '🥈' : '🥉';

        return `
          <div class="top-performer-card">
            <div class="top-performer-left">
              <div class="top-rank-badge ${rankClass}" title="${idx + 1}. ${isEn ? 'Rank' : 'Sıra'}">${rankIcon}</div>
              <div class="top-performer-info">
                <div class="top-performer-name">
                  <span>${s.name}</span>
                  ${s.isLeader ? `<span title="${isEn ? 'Team Leader' : 'Takım Lideri'}" style="font-size:12px; margin-left:3px;">👑</span>` : ''}
                </div>
                <div class="top-performer-title">${s.title}</div>
              </div>
            </div>

            <div class="top-performer-stats">
              <div class="top-stat-item">
                <span class="top-stat-label">${isEn ? 'RECORDS' : 'KAYIT'}</span>
                <span class="top-stat-value">${s.totalContacts || 0} ${isEn ? 'People' : 'Kişi'}</span>
              </div>
              <div class="top-stat-item">
                <span class="top-stat-label">${isEn ? 'HOT MEETINGS' : 'SICAK GÖRÜŞME'}</span>
                <span class="top-stat-value" style="color: #EF4444; font-weight: 800;">🔥 ${s.hotCount || 0}</span>
              </div>
            </div>

            <div class="top-performer-score">
              <span class="top-score-badge">%${s.score || 85}</span>
              <div class="top-score-bar-bg">
                <div class="top-score-bar-fill" style="width: ${s.score || 85}%;"></div>
              </div>
            </div>

            <button class="admin-table-action-btn primary btn-top-performer-detail" data-id="${s.id}" title="${s.name} ${isEn ? 'Open Detailed Performance Page' : 'Detaylı Performans Sayfasına Git'}">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>
        `;
      }).join('');

      topPerformersList.querySelectorAll('.btn-top-performer-detail').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          openStaffDetailPage(id);
        });
      });
    }

    // "Tümünü Gör" Button
    const btnDashViewAllStaff = document.getElementById('btnDashViewAllStaff');
    if (btnDashViewAllStaff) {
      btnDashViewAllStaff.onclick = () => {
        navigateToAdminView('viewAdminStaff');
      };
    }

    // Initialize 7-Day Weekly Timeline Schedule Widget
    initWeeklyTimelineWidget();
  }

  // =========================================================
  // 1.1 WEEKLY TIMELINE WIDGET (Image 2 Calendar UI)
  // =========================================================
  let weeklyCurrentMonthIdx = 8; // 8 = Eylül (0-indexed: Ocak=0, ..., Eylül=8)
  const monthNamesTr = [
    "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran",
    "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"
  ];
  const monthNamesEn = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
  let weeklyTimelineInitDone = false;

  function initWeeklyTimelineWidget() {
    const prevBtn = document.getElementById('btnWeeklyPrevMonth');
    const nextBtn = document.getElementById('btnWeeklyNextMonth');
    const titleEl = document.getElementById('weeklyTimelineTitle');
    const dayItems = document.querySelectorAll('.weekly-day-item');
    const gridCols = document.querySelectorAll('.weekly-grid-col');

    if (!titleEl) return;

    function updateWeeklyHeader() {
      const isEn = currentLang === 'en';
      const mNames = isEn ? monthNamesEn : monthNamesTr;
      const prevIdx = (weeklyCurrentMonthIdx + 11) % 12;
      const nextIdx = (weeklyCurrentMonthIdx + 1) % 12;
      
      if (prevBtn) prevBtn.textContent = `< ${mNames[prevIdx]}`;
      titleEl.textContent = `${mNames[weeklyCurrentMonthIdx]} 2026`;
      if (nextBtn) nextBtn.textContent = `${mNames[nextIdx]} >`;
    }

    if (!weeklyTimelineInitDone) {
      weeklyTimelineInitDone = true;

      if (prevBtn) {
        prevBtn.addEventListener('click', () => {
          weeklyCurrentMonthIdx = (weeklyCurrentMonthIdx + 11) % 12;
          updateWeeklyHeader();
          const isEn = currentLang === 'en';
          const mNames = isEn ? monthNamesEn : monthNamesTr;
          showToast(isEn ? `Calendar shifted to ${mNames[weeklyCurrentMonthIdx]} 2026 📅` : `Takvim ${mNames[weeklyCurrentMonthIdx]} 2026 dönemine kaydırıldı 📅`);
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', () => {
          weeklyCurrentMonthIdx = (weeklyCurrentMonthIdx + 1) % 12;
          updateWeeklyHeader();
          const isEn = currentLang === 'en';
          const mNames = isEn ? monthNamesEn : monthNamesTr;
          showToast(isEn ? `Calendar shifted to ${mNames[weeklyCurrentMonthIdx]} 2026 📅` : `Takvim ${mNames[weeklyCurrentMonthIdx]} 2026 dönemine kaydırıldı 📅`);
        });
      }

      dayItems.forEach((item, index) => {
        item.addEventListener('click', () => {
          dayItems.forEach(d => d.classList.remove('active'));
          item.classList.add('active');

          if (gridCols.length > index) {
            gridCols.forEach(c => c.classList.remove('active'));
            gridCols[index].classList.add('active');
          }

          const dayNum = item.getAttribute('data-day') || item.querySelector('.weekly-day-num')?.textContent;
          const dayName = item.querySelector('.weekly-day-name')?.textContent || '';
          const isEn = currentLang === 'en';
          const mNames = isEn ? monthNamesEn : monthNamesTr;
          showToast(isEn ? `${mNames[weeklyCurrentMonthIdx]} ${dayNum} (${dayName}) selected 🗓️` : `${dayNum} ${mNames[weeklyCurrentMonthIdx]} (${dayName}) seçildi 🗓️`);
        });
      });
    }

    updateWeeklyHeader();
  }

  // 2. STAFF & LEADER MANAGEMENT
  const staffTableBody = document.getElementById('staffTableBody');
  const staffSearchInput = document.getElementById('staffSearchInput');
  const staffLeaderFilter = document.getElementById('staffLeaderFilter');
  const staffSortSelect = document.getElementById('staffSortSelect');

  // Status Filter Pills for Staff
  document.querySelectorAll('.status-pill-box').forEach(pill => {
    pill.addEventListener('click', () => {
      document.querySelectorAll('.status-pill-box').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      currentStaffStatusFilter = pill.getAttribute('data-staff-filter');
      renderAdminStaffTable();
    });
  });

  if (staffSearchInput) staffSearchInput.addEventListener('input', renderAdminStaffTable);
  if (staffLeaderFilter) staffLeaderFilter.addEventListener('change', renderAdminStaffTable);
  if (staffSortSelect) staffSortSelect.addEventListener('change', renderAdminStaffTable);

  function renderAdminStaffTable() {
    if (!staffTableBody) return;

    // Update Pill Counters
    const pillCountAllStaff = document.getElementById('pillCountAllStaff');
    const pillCountLeaders = document.getElementById('pillCountLeaders');
    const pillCountTopPerformers = document.getElementById('pillCountTopPerformers');
    const pillCountNewStaff = document.getElementById('pillCountNewStaff');

    const leadersCount = adminStaffList.filter(s => s.isLeader).length;
    const topCount = adminStaffList.filter(s => (s.score || 0) >= 85).length;
    const newCount = adminStaffList.filter(s => s.newLeads >= 10).length;

    if (pillCountAllStaff) pillCountAllStaff.textContent = adminStaffList.length;
    if (pillCountLeaders) pillCountLeaders.textContent = leadersCount;
    if (pillCountTopPerformers) pillCountTopPerformers.textContent = topCount;
    if (pillCountNewStaff) pillCountNewStaff.textContent = newCount;

    const isEn = currentLang === 'en';

    // Populate Leader Filter Select Dropdown
    if (staffLeaderFilter) {
      const currentVal = staffLeaderFilter.value;
      const leaders = adminStaffList.filter(s => s.isLeader);
      staffLeaderFilter.innerHTML = `
        <option value="all">${isEn ? 'All Teams / Leaders' : 'Tüm Ekipler / Liderler'}</option>
        <option value="only-leaders">👑 ${isEn ? 'Only Team Leaders' : 'Sadece Takım Liderleri'}</option>
        ${leaders.map(l => `<option value="${l.id}">${isEn ? 'Team:' : 'Takım:'} ${l.name}</option>`).join('')}
      `;
      staffLeaderFilter.value = currentVal || "all";
    }

    // Filtering
    let filtered = [...adminStaffList];

    // Status pill filter
    if (currentStaffStatusFilter === 'leaders') {
      filtered = filtered.filter(s => s.isLeader);
    } else if (currentStaffStatusFilter === 'top') {
      filtered = filtered.filter(s => (s.score || 0) >= 85);
    } else if (currentStaffStatusFilter === 'new') {
      filtered = filtered.filter(s => s.newLeads >= 10);
    }

    // Leader dropdown filter
    const selectedLeader = staffLeaderFilter ? staffLeaderFilter.value : 'all';
    if (selectedLeader === 'only-leaders') {
      filtered = filtered.filter(s => s.isLeader);
    } else if (selectedLeader !== 'all') {
      filtered = filtered.filter(s => s.leaderId === selectedLeader || s.id === selectedLeader);
    }

    // Search filter
    const query = staffSearchInput ? staffSearchInput.value.trim().toLowerCase() : '';
    if (query) {
      filtered = filtered.filter(s =>
        s.name.toLowerCase().includes(query) ||
        s.title.toLowerCase().includes(query) ||
        s.email.toLowerCase().includes(query)
      );
    }

    // Sorting
    const sortVal = staffSortSelect ? staffSortSelect.value : 'performance';
    if (sortVal === 'performance') {
      filtered.sort((a, b) => (b.score || 0) - (a.score || 0));
    } else if (sortVal === 'leads') {
      filtered.sort((a, b) => (b.totalContacts || 0) - (a.totalContacts || 0));
    } else if (sortVal === 'name') {
      filtered.sort((a, b) => a.name.localeCompare(b.name, isEn ? 'en' : 'tr'));
    }

    const paginationInfo = document.getElementById('staffPaginationInfo');
    if (paginationInfo) {
      paginationInfo.textContent = isEn 
        ? `1-${filtered.length} / ${adminStaffList.length} Staff`
        : `1-${filtered.length} / ${adminStaffList.length} Personel`;
    }

    if (filtered.length === 0) {
      staffTableBody.innerHTML = `
        <tr>
          <td colspan="7" class="text-center py-8 text-muted" style="padding: 30px; text-align: center;">
            ${isEn ? 'No staff found matching search criteria.' : 'Arama kriterlerine uygun personel bulunamadı.'}
          </td>
        </tr>
      `;
      return;
    }

    staffTableBody.innerHTML = filtered.map(s => {
      const leaderObj = s.leaderId ? adminStaffList.find(l => l.id === s.leaderId) : null;
      const leaderLabel = s.isLeader 
        ? `<span class="leader-badge">👑 ${isEn ? 'Team Leader' : 'Takım Lideri'}</span>`
        : (leaderObj ? `<span class="team-lead-tag">👤 ${leaderObj.name}</span>` : `<span class="text-xs text-muted">${isEn ? 'Direct Manager' : 'Doğrudan Yönetici'}</span>`);

      const avatarMarkup = s.avatar
        ? `<img src="${s.avatar}" alt="${s.name}">`
        : `<span>${s.name.substring(0, 2).toUpperCase()}</span>`;

      const monthlyTarget = s.monthlyTarget || 15;
      const totalConvs = s.totalContacts || 0;
      const targetPct = Math.min(100, Math.round((totalConvs / monthlyTarget) * 100));

      const isCancelled = s.status === 'cancelled';
      const cancelledBadge = isCancelled 
        ? `<span style="display:inline-block; font-size:10px; font-weight:700; background:#FEE2E2; color:#DC2626; border-radius:4px; padding:1px 5px; margin-left:4px;" title="${isEn ? 'This staff card is deactivated' : 'Bu personelin kartı iptal edilmiştir'}">⛔ ${isEn ? 'Deactivated' : 'İptal'}</span>`
        : '';

      return `
        <tr data-staff-id="${s.id}" class="${isCancelled ? 'staff-row-cancelled' : ''}">
          <td data-label="${isEn ? 'Staff' : 'Personel'}" class="staff-cell-user">
            <div class="staff-table-user">
              <div class="staff-table-avatar">${avatarMarkup}</div>
              <div>
                <div class="staff-table-name">${s.name} ${cancelledBadge}</div>
                <div class="staff-table-title">${s.title}</div>
              </div>
            </div>
          </td>
          <td data-label="${isEn ? 'Reporting Leader' : 'Bağlı Olduğu Lider'}">${leaderLabel}</td>
          <td data-label="${isEn ? 'Registered Clients' : 'Kayıtlı Müşteri'}">
            <div class="font-bold text-slate-900">${s.totalContacts} ${isEn ? 'People' : 'Kişi'}</div>
            <div class="text-xs text-muted font-medium">${s.newLeads} ${isEn ? 'New' : 'Yeni'} / ${s.existingLeads} ${isEn ? 'Old' : 'Eski'}</div>
          </td>
          <td data-label="${isEn ? 'Client Distribution' : 'Müşteri Dağılımı'}">
            <div class="stage-pills-row">
              <span class="stage-badge-sm hot" title="${isEn ? 'Hot Lead' : 'Sıcak Müşteri'}">🔥 ${s.hotCount}</span>
              <span class="stage-badge-sm warm" title="${isEn ? 'Warm Lead' : 'Ilık Müşteri'}">⚡ ${s.warmCount}</span>
              <span class="stage-badge-sm cold" title="${isEn ? 'Cold Lead' : 'Soğuk Müşteri'}">❄️ ${s.coldCount}</span>
            </div>
          </td>
          <td data-label="${isEn ? 'Monthly Goal' : 'Aylık Hedef'}">
            <div class="staff-target-col">
              <span class="font-bold text-slate-800 text-xs mb-1 block">${totalConvs} / ${monthlyTarget} ${isEn ? 'Meetings' : 'Görüşme'}</span>
              <div class="perf-progress-bg" style="height: 6px;">
                <div class="perf-progress-fill" style="width: ${targetPct}%; background: ${targetPct >= 80 ? '#10B981' : targetPct >= 50 ? '#4F46E5' : '#F59E0B'};"></div>
              </div>
            </div>
          </td>
          <td data-label="${isEn ? 'Performance' : 'Performans'}" class="text-center" style="text-align: center;">
            <span class="stage-badge-sm font-bold ${targetPct >= 80 ? 'hot' : targetPct >= 50 ? 'warm' : 'cold'}">%${targetPct}</span>
          </td>
          <td data-label="${isEn ? 'Actions' : 'İşlemler'}" class="text-center staff-cell-actions" style="text-align: center;">
            <div class="admin-table-actions center" style="justify-content: center;">
              <button class="admin-table-action-btn primary btn-staff-inspect" data-id="${s.id}" title="${isEn ? 'Open Detail & Performance Page' : 'Performans & Yönetim Detay Sayfasını Aç'}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </button>
              <button class="admin-table-action-btn btn-staff-target" data-id="${s.id}" title="${isEn ? 'Set Monthly Meeting Goal' : 'Aylık Görüşme Hedefi Belirle'}" style="color: #4F46E5;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
              </button>
              <button class="admin-table-action-btn btn-staff-edit" data-id="${s.id}" title="${isEn ? 'Edit Info / Assign Leader' : 'Bilgileri Düzenle / Lider Ata'}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <button class="admin-table-action-btn danger btn-staff-delete" data-id="${s.id}" title="${isEn ? 'Delete Staff' : 'Personeli Sil'}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');

    // Bind Action Buttons
    staffTableBody.querySelectorAll('.btn-staff-inspect').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        openStaffDetailPage(id);
      });
    });

    staffTableBody.querySelectorAll('.btn-staff-target').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        openStaffTargetModal(id);
      });
    });

    staffTableBody.querySelectorAll('.btn-staff-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        openStaffModalForEdit(id);
      });
    });

    staffTableBody.querySelectorAll('.btn-staff-delete').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const staff = adminStaffList.find(s => s.id === id);
        if (staff && confirm(isEn ? `Are you sure you want to delete ${staff.name} from the system?` : `${staff.name} adlı personeli sistemden silmek istediğinize emin misiniz?`)) {
          adminStaffList = adminStaffList.filter(s => s.id !== id);
          saveStaffToStorage();
          renderAdminStaffTable();
          renderAdminDashboard();
          showToast(isEn ? `${staff.name} deleted.` : `${staff.name} silindi.`);
        }
      });
    });
  }

  // =========================================================
  // 2.1 DEDICATED STAFF DETAIL PAGE (Peoplexio Dashboard Layout)
  // =========================================================
  function openStaffDetailPage(staffId) {
    currentStaffDetailId = staffId;
    navigateToAdminView('viewAdminStaffDetail');
    renderStaffDetailPage();
  }

  const btnBackToStaffList = document.getElementById('btnBackToStaffList');
  if (btnBackToStaffList) {
    btnBackToStaffList.addEventListener('click', () => {
      navigateToAdminView('viewAdminStaff');
    });
  }

  const staffDetTimeFilter = document.getElementById('staffDetailTimeFilter') || document.getElementById('staffDetTimeFilter');
  if (staffDetTimeFilter) {
    staffDetTimeFilter.addEventListener('change', (e) => {
      currentStaffDetailPeriod = e.target.value;
      renderStaffDetailPage();
      const isEn = currentLang === 'en';
      showToast(isEn ? `Time filter updated to "${e.target.options[e.target.selectedIndex].text}" ⏱️` : `Zaman filtresi "${e.target.options[e.target.selectedIndex].text}" olarak güncellendi ⏱️`);
    });
  }

  const btnEditStaffTargetModal = document.getElementById('btnEditStaffTargetModal');
  if (btnEditStaffTargetModal) {
    btnEditStaffTargetModal.addEventListener('click', () => {
      openStaffTargetModal(currentStaffDetailId);
    });
  }

  const btnQuickEditTarget = document.getElementById('btnQuickEditTarget');
  if (btnQuickEditTarget) {
    btnQuickEditTarget.addEventListener('click', () => {
      openStaffTargetModal(currentStaffDetailId);
    });
  }

  function renderStaffDetailPage() {
    const staff = adminStaffList.find(s => s.id === currentStaffDetailId) || adminStaffList[0];
    if (!staff) return;

    const isEn = currentLang === 'en';

    // Period multiplier for realistic dynamic numbers
    let mult = 1.0;
    if (currentStaffDetailPeriod === 'week') mult = 0.35;
    else if (currentStaffDetailPeriod === 'month') mult = 1.0;
    else if (currentStaffDetailPeriod === '30days') mult = 1.05;
    else if (currentStaffDetailPeriod === '3months') mult = 2.8;
    else if (currentStaffDetailPeriod === 'all') mult = 4.2;

    const totalConvs = Math.max(1, Math.round((staff.totalContacts || 20) * mult));
    const hotCount = Math.max(0, Math.round((staff.hotCount || 8) * mult));
    const warmCount = Math.max(0, Math.round((staff.warmCount || 6) * mult));
    const coldCount = Math.max(0, Math.round((staff.coldCount || 4) * mult));
    const targetGoal = staff.monthlyTarget || 15;
    const targetPct = Math.min(100, Math.round((totalConvs / targetGoal) * 100));

    // 1. Header Information
    const detHeaderName = document.getElementById('staffDetHeaderName');
    const detHeaderBadge = document.getElementById('staffDetHeaderBadge');
    const detHeaderTitle = document.getElementById('staffDetHeaderTitle');

    if (detHeaderName) detHeaderName.textContent = staff.name;
    if (detHeaderTitle) detHeaderTitle.textContent = `${staff.title} | ${staff.team || (isEn ? 'B2B Solutions' : 'B2B Çözümler')}`;
    if (detHeaderBadge) {
      if (staff.isLeader) {
        detHeaderBadge.style.display = 'inline-flex';
        detHeaderBadge.textContent = `👑 ${isEn ? 'Team Leader' : 'Takım Lideri'}`;
      } else {
        detHeaderBadge.style.display = 'none';
      }
    }

    // 2. Quick Metric Stat Chips
    const metricMeetings = document.getElementById('detMetricMeetings');
    const metricHot = document.getElementById('detMetricHot');
    const metricWarm = document.getElementById('detMetricWarm');
    const metricCold = document.getElementById('detMetricCold');
    const metricTargetPct = document.getElementById('detMetricTargetPct');
    const metricTargetSub = document.getElementById('detMetricTargetSub');

    if (metricMeetings) metricMeetings.textContent = `${totalConvs} ${isEn ? 'Meetings' : 'Görüşme'}`;
    if (metricHot) metricHot.textContent = `${hotCount} ${isEn ? 'Leads' : 'Müşteri'}`;
    if (metricWarm) metricWarm.textContent = `${warmCount} ${isEn ? 'Leads' : 'Müşteri'}`;
    if (metricCold) metricCold.textContent = `${coldCount} ${isEn ? 'Leads' : 'Müşteri'}`;
    if (metricTargetPct) metricTargetPct.textContent = `%${targetPct}`;
    if (metricTargetSub) metricTargetSub.textContent = `${totalConvs} / ${targetGoal} ${isEn ? 'Meeting Goal' : 'Görüşme Hedefi'}`;

    // 3. Col 1: Hero Card
    const detAvatar = document.getElementById('staffDetAvatar');
    if (detAvatar) {
      if (staff.avatar) {
        detAvatar.innerHTML = `<img src="${staff.avatar}" alt="${staff.name}">`;
      } else {
        const initials = staff.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        detAvatar.textContent = initials;
      }
    }

    const cardName = document.getElementById('staffDetCardName');
    const cardRole = document.getElementById('staffDetCardRole');
    const heroSalaryBadge = document.getElementById('staffDetHeroSalaryBadge');

    if (cardName) cardName.textContent = staff.name;
    if (cardRole) cardRole.textContent = staff.title;
    if (heroSalaryBadge) {
      heroSalaryBadge.innerHTML = `
        <span class="text-xs opacity-80">${isEn ? 'Total Meetings' : 'Toplam Görüşme'}</span>
        <strong>${totalConvs} ${isEn ? 'Meetings' : 'Görüşme'}</strong>
      `;
    }

    // 4. Col 1: Details & Card Management Status
    const phoneLink = document.getElementById('staffDetPhoneLink');
    const phoneText = document.getElementById('staffDetPhoneText');
    const mailLink = document.getElementById('staffDetMailLink');
    const emailText = document.getElementById('staffDetEmailText');
    const teamText = document.getElementById('staffDetTeamText');
    const subCountBadge = document.getElementById('staffDetSubCountBadge');

    if (phoneText) phoneText.textContent = staff.phone || '+90 532 987 65 43';
    if (phoneLink) phoneLink.href = `tel:${(staff.phone || '+905329876543').replace(/\s+/g, '')}`;
    if (emailText) emailText.textContent = staff.email || `${staff.name.toLowerCase().replace(/\s+/g, '')}@vedubox.com`;
    if (mailLink) mailLink.href = `mailto:${staff.email || 'info@vedubox.com'}`;

    const subStaffList = adminStaffList.filter(s => s.leaderId === staff.id);
    if (subCountBadge) subCountBadge.textContent = subStaffList.length;

    const btnSubordinatesModal = document.getElementById('btnOpenSubordinatesModal');
    if (btnSubordinatesModal) {
      if (staff.isLeader && subStaffList.length > 0) {
        btnSubordinatesModal.style.display = 'flex';
      } else {
        btnSubordinatesModal.style.display = 'none';
      }
    }

    if (teamText) {
      const leaderObj = staff.leaderId ? adminStaffList.find(l => l.id === staff.leaderId) : null;
      if (staff.isLeader) {
        teamText.textContent = isEn 
          ? `Team Leader (${subStaffList.length} Subordinates Assigned)` 
          : `Takım Lideri (${subStaffList.length} Personel Bağlı)`;
      } else if (leaderObj) {
        teamText.textContent = isEn 
          ? `Reports to: ${leaderObj.name}'s Team` 
          : `Bağlı: ${leaderObj.name} Ekibi`;
      } else {
        teamText.textContent = isEn 
          ? `Directly Reports to General Manager` 
          : `Doğrudan Genel Müdüre Bağlı`;
      }
    }

    // Card Cancellation Status & Buttons Update (Madde 5)
    const statusNotice = document.getElementById('staffDetCardStatusNotice');
    const btnCancelCard = document.getElementById('btnCancelStaffCard');
    const btnCancelCardText = document.getElementById('btnCancelStaffCardText');

    if (staff.status === 'cancelled') {
      if (statusNotice) statusNotice.classList.remove('hidden');
      if (btnCancelCardText) btnCancelCardText.textContent = isEn ? 'Reactivate Card' : 'Kartı Yeniden Aktif Et';
      if (btnCancelCard) {
        btnCancelCard.classList.add('reactivate');
        btnCancelCard.title = isEn ? 'Reactivate deactivated business card' : 'İptal edilmiş kartviziti yeniden kullanıma aç';
      }
    } else {
      if (statusNotice) statusNotice.classList.add('hidden');
      if (btnCancelCardText) btnCancelCardText.textContent = isEn ? 'Deactivate Card' : 'Bu Kartı İptal Et';
      if (btnCancelCard) {
        btnCancelCard.classList.remove('reactivate');
        btnCancelCard.title = isEn ? 'Deactivate this staff digital card' : 'Bu personelin dijital kartvizitini iptal et';
      }
    }

    // 5. Col 2: Big Activity Counter & Bar Trend
    const bigCount = document.getElementById('staffDetBigCount');
    if (bigCount) bigCount.textContent = totalConvs;

    const activityBars = document.getElementById('staffDetActivityBars');
    if (activityBars) {
      const days = isEn ? [
        { label: 'M', name: 'Mon', count: Math.round(totalConvs * 0.16), height: 48 },
        { label: 'T', name: 'Tue', count: Math.round(totalConvs * 0.24), height: 75 },
        { label: 'W', name: 'Wed', count: Math.round(totalConvs * 0.18), height: 60 },
        { label: 'T', name: 'Thu', count: Math.round(totalConvs * 0.32), height: 95, active: true },
        { label: 'F', name: 'Fri', count: Math.round(totalConvs * 0.22), height: 70 },
        { label: 'S', name: 'Sat', count: Math.round(totalConvs * 0.10), height: 35 },
        { label: 'S', name: 'Sun', count: Math.round(totalConvs * 0.05), height: 20 }
      ] : [
        { label: 'P', name: 'Pzt', count: Math.round(totalConvs * 0.16), height: 48 },
        { label: 'S', name: 'Sal', count: Math.round(totalConvs * 0.24), height: 75 },
        { label: 'Ç', name: 'Çar', count: Math.round(totalConvs * 0.18), height: 60 },
        { label: 'P', name: 'Per', count: Math.round(totalConvs * 0.32), height: 95, active: true },
        { label: 'C', name: 'Cum', count: Math.round(totalConvs * 0.22), height: 70 },
        { label: 'C', name: 'Cmt', count: Math.round(totalConvs * 0.10), height: 35 },
        { label: 'P', name: 'Paz', count: Math.round(totalConvs * 0.05), height: 20 }
      ];

      activityBars.innerHTML = days.map(d => `
        <div class="act-bar-col">
          <div class="act-bar-fill ${d.active ? 'active' : ''}" style="height: ${d.height}%;">
            ${d.active ? `<div class="peoplexio-peak-pill">${totalConvs} ${isEn ? 'Meetings' : 'Görüşme'}</div>` : ''}
            <span class="act-tooltip">${d.count} (${d.name})</span>
          </div>
          <span class="act-label">${d.label}</span>
        </div>
      `).join('');
    }

    // 6. Col 2: Organized Meetings List
    const meetingsListEl = document.getElementById('staffDetMeetingsList');
    const meetingBadge = document.getElementById('staffDetMeetingCountBadge');
    const staffMeetings = meetings.filter((m, idx) => {
      const assigned = adminStaffList[idx % adminStaffList.length].name;
      return assigned === staff.name || idx % 2 === 0;
    }).slice(0, 4);

    if (meetingBadge) meetingBadge.textContent = `${staffMeetings.length} ${isEn ? 'Meetings' : 'Toplantı'}`;
    if (meetingsListEl) {
      if (staffMeetings.length === 0) {
        meetingsListEl.innerHTML = `<p class="text-xs text-muted text-center py-4">${isEn ? 'No scheduled meetings for this staff.' : 'Bu personelin ayarlanmış toplantısı bulunmuyor.'}</p>`;
      } else {
        meetingsListEl.innerHTML = staffMeetings.map((m, idx) => {
          const custRaw = m.customer || (isEn ? 'Client' : 'Müşteri');
          const custArr = custRaw.split(/[,&]+/).map(c => c.trim()).filter(Boolean);

          return `
          <div class="staff-meeting-row">
            <div class="staff-meet-info">
              <span class="staff-meet-title">${m.title}</span>
              <div class="staff-meet-meta">
                <span>📅 ${m.date} • ${m.time}</span>
                <span>🌐 ${m.type}</span>
              </div>
              <div class="staff-meet-participants">
                ${custArr.map(c => `<span class="participant-line">👥 ${c}</span>`).join('')}
              </div>
            </div>
            <button class="admin-table-action-btn" onclick="window.openMeetingDetail(${idx})" title="${isEn ? 'Meeting Detail' : 'Toplantı Detayı'}" style="color: #64748B; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>
        `;
        }).join('');
      }
    }

    // 7. Col 3: Weekly Activity Logs
    const logsListEl = document.getElementById('staffDetLogsList');
    if (logsListEl) {
      const staffLogs = isEn ? [
        {
          icon: '🤝',
          title: `Meeting Completed: ${customers[0] ? customers[0].name : 'Kemal Yılmaz'}`,
          meta: 'Shared enterprise MonaCard presentation and pricing proposal.',
          time: 'Today, 14:30'
        },
        {
          icon: '📄',
          title: `Proposal Sent: ${customers[1] ? customers[1].name : 'Selin Demir'}`,
          meta: '150-User Digital Business Card annual license package.',
          time: 'Yesterday, 11:15'
        },
        {
          icon: '📅',
          title: 'New Meeting Scheduled (Google Meet)',
          meta: 'Digital Transformation & CRM Integration Pitch.',
          time: '18 Sep, 16:40'
        },
        {
          icon: '✅',
          title: `Partnership Deal Approved: ${customers[2] ? customers[2].name : 'Zeynep Kaya'}`,
          meta: 'Contract signed via e-signature and activation completed.',
          time: '17 Sep, 15:20'
        },
        {
          icon: '🎙️',
          title: 'Voice Note Logged',
          meta: 'Discussed budget revision with procurement director.',
          time: '16 Sep, 10:05'
        }
      ] : [
        {
          icon: '🤝',
          title: `Görüşme Tamamlandı: ${customers[0] ? customers[0].name : 'Kemal Yılmaz'}`,
          meta: 'Kurumsal MonaCard tanıtımı ve fiyat teklifi paylaşıldı.',
          time: 'Bugün, 14:30'
        },
        {
          icon: '📄',
          title: `Teklif Gönderildi: ${customers[1] ? customers[1].name : 'Selin Demir'}`,
          meta: '150 Kullanıcılı Dijital Kartvizit yıllık lisans paketi.',
          time: 'Dün, 11:15'
        },
        {
          icon: '📅',
          title: 'Yeni Toplantı Planlandı (Google Meet)',
          meta: 'Dijital Dönüşüm & CRM Entegrasyon Sunumu.',
          time: '18 Eylül, 16:40'
        },
        {
          icon: '✅',
          title: `İş Birliği Anlaşması Onaylandı: ${customers[2] ? customers[2].name : 'Zeynep Kaya'}`,
          meta: 'Sözleşme e-imza ile tamamlandı ve aktivasyon yapıldı.',
          time: '17 Eylül, 15:20'
        },
        {
          icon: '🎙️',
          title: 'Sesli Görüşme Notu Kaydedildi',
          meta: 'Satın alma yetkilisi ile bütçe revizyonu görüşüldü.',
          time: '16 Eylül, 10:05'
        }
      ];

      logsListEl.innerHTML = staffLogs.map(log => `
        <div class="dark-log-item">
          <div class="dark-log-icon-box">${log.icon}</div>
          <div class="dark-log-info">
            <span class="dark-log-item-title">${log.title}</span>
            <div class="dark-log-meta">
              <span>${log.meta}</span>
              <span class="dark-log-time">${log.time}</span>
            </div>
          </div>
        </div>
      `).join('');
    }
  }

  // =========================================================
  // CARD MANAGEMENT (BU KARTI İPTAL ET & MÜŞTERİLERİ AKTAR - Madde 5)
  // =========================================================
  const btnCancelStaffCard = document.getElementById('btnCancelStaffCard');
  if (btnCancelStaffCard) {
    btnCancelStaffCard.addEventListener('click', () => {
      const staff = adminStaffList.find(s => s.id === currentStaffDetailId);
      if (!staff) return;

      if (staff.status === 'cancelled') {
        staff.status = 'active';
        saveStaffToStorage();
        renderStaffDetailPage();
        renderAdminStaffTable();
        renderAdminDashboard();
        showToast(`${staff.name} personeline ait dijital kartvizit yeniden aktif edildi! ✅✨`);
      } else {
        if (confirm(`${staff.name} adlı personelin dijital kartvizitini iptal etmek istediğinize emin misiniz?\n\nKart iptal edildiğinde personel sisteme ve kartvizitine giriş yapamaz.`)) {
          staff.status = 'cancelled';
          saveStaffToStorage();
          renderStaffDetailPage();
          renderAdminStaffTable();
          renderAdminDashboard();
          showToast(`${staff.name} kartviziti iptal edildi. Giriş yetkisi durduruldu. ⛔`);
        }
      }
    });
  }

  const btnTransferStaffClients = document.getElementById('btnTransferStaffClients');
  if (btnTransferStaffClients) {
    btnTransferStaffClients.addEventListener('click', () => {
      openTransferCustomersModal(currentStaffDetailId);
    });
  }

  // Transfer Customers Modal Logic
  const transferCustomersModal = document.getElementById('transferCustomersModal');
  const transferStaffSearchInput = document.getElementById('transferStaffSearchInput');
  const transferStaffListContainer = document.getElementById('transferStaffListContainer');
  const btnConfirmTransfer = document.getElementById('btnConfirmTransfer');
  let selectedTransferTargetStaffId = null;

  function openTransferCustomersModal(sourceStaffId) {
    const sourceStaff = adminStaffList.find(s => s.id === sourceStaffId);
    if (!sourceStaff || !transferCustomersModal) return;

    selectedTransferTargetStaffId = null;
    if (btnConfirmTransfer) btnConfirmTransfer.disabled = true;

    const sourceAvatar = document.getElementById('transferSourceAvatar');
    const sourceName = document.getElementById('transferSourceName');
    const sourceStats = document.getElementById('transferSourceStats');

    if (sourceAvatar) {
      if (sourceStaff.avatar) {
        sourceAvatar.innerHTML = `<img src="${sourceStaff.avatar}" alt="${sourceStaff.name}" style="width:100%;height:100%;border-radius:10px;object-fit:cover;">`;
      } else {
        sourceAvatar.textContent = sourceStaff.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
      }
    }
    if (sourceName) sourceName.textContent = sourceStaff.name;
    if (sourceStats) {
      sourceStats.textContent = `${sourceStaff.totalContacts || 0} Müşteri • ${sourceStaff.hotCount || 0} Sıcak • ${sourceStaff.warmCount || 0} Ilık • ${sourceStaff.coldCount || 0} Soğuk`;
    }

    if (transferStaffSearchInput) {
      transferStaffSearchInput.value = '';
    }

    renderTransferStaffList(sourceStaffId, '');
    openModal(transferCustomersModal);
  }

  function renderTransferStaffList(sourceStaffId, searchQuery) {
    if (!transferStaffListContainer) return;

    const q = (searchQuery || '').trim().toLowerCase();
    const targets = adminStaffList.filter(s => {
      if (s.id === sourceStaffId || s.status === 'cancelled') return false;
      if (!q) return true;
      return s.name.toLowerCase().includes(q) || s.title.toLowerCase().includes(q) || s.email.toLowerCase().includes(q);
    });

    if (targets.length === 0) {
      transferStaffListContainer.innerHTML = `
        <div style="text-align:center; padding:24px 12px; color:var(--admin-text-sub,#64748B); font-size:13px;">
          Uygun veya aranan kriterlere uyan aktif personel bulunamadı.
        </div>
      `;
      return;
    }

    transferStaffListContainer.innerHTML = targets.map(t => {
      const isSelected = selectedTransferTargetStaffId === t.id;
      const initials = t.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
      const avatarMarkup = t.avatar
        ? `<img src="${t.avatar}" alt="${t.name}" style="width:100%;height:100%;border-radius:10px;object-fit:cover;">`
        : `<span>${initials}</span>`;

      return `
        <div class="transfer-staff-card ${isSelected ? 'selected' : ''}" data-target-id="${t.id}">
          <div class="transfer-staff-avatar">${avatarMarkup}</div>
          <div class="transfer-staff-info">
            <div class="transfer-staff-name">${t.name} ${t.isLeader ? '<span style="font-size:11px;">👑 Lider</span>' : ''}</div>
            <div class="transfer-staff-title">${t.title}</div>
            <div class="transfer-staff-stats">${t.totalContacts || 0} Mevcut Müşteri • %${t.score || 85} Performans</div>
          </div>
          <div class="transfer-radio-circle ${isSelected ? 'active' : ''}">
            ${isSelected ? '<div class="radio-inner-dot"></div>' : ''}
          </div>
        </div>
      `;
    }).join('');

    transferStaffListContainer.querySelectorAll('.transfer-staff-card').forEach(card => {
      card.addEventListener('click', () => {
        selectedTransferTargetStaffId = card.getAttribute('data-target-id');
        renderTransferStaffList(sourceStaffId, transferStaffSearchInput ? transferStaffSearchInput.value : '');
        if (btnConfirmTransfer) btnConfirmTransfer.disabled = !selectedTransferTargetStaffId;
      });
    });
  }

  if (transferStaffSearchInput) {
    transferStaffSearchInput.addEventListener('input', (e) => {
      renderTransferStaffList(currentStaffDetailId, e.target.value);
    });
  }

  if (btnConfirmTransfer) {
    btnConfirmTransfer.addEventListener('click', () => {
      if (!selectedTransferTargetStaffId) return;
      const sourceStaff = adminStaffList.find(s => s.id === currentStaffDetailId);
      const targetStaff = adminStaffList.find(s => s.id === selectedTransferTargetStaffId);

      if (!sourceStaff || !targetStaff) return;

      if (confirm(`"${sourceStaff.name}" personeline ait tüm müşteriler, görüşmeler ve veriler "${targetStaff.name}" personeline aktarılacaktır.\n\nBu işlemi onaylıyor musunuz?`)) {
        // Transfer CRM customers
        customers.forEach(c => {
          if (c.assignedStaff === sourceStaff.name || (!c.assignedStaff && sourceStaff.id === 'staff-1')) {
            c.assignedStaff = targetStaff.name;
          }
        });

        // Transfer counts
        targetStaff.totalContacts = (targetStaff.totalContacts || 0) + (sourceStaff.totalContacts || 0);
        targetStaff.hotCount = (targetStaff.hotCount || 0) + (sourceStaff.hotCount || 0);
        targetStaff.warmCount = (targetStaff.warmCount || 0) + (sourceStaff.warmCount || 0);
        targetStaff.coldCount = (targetStaff.coldCount || 0) + (sourceStaff.coldCount || 0);
        targetStaff.newLeads = (targetStaff.newLeads || 0) + (sourceStaff.newLeads || 0);

        sourceStaff.totalContacts = 0;
        sourceStaff.hotCount = 0;
        sourceStaff.warmCount = 0;
        sourceStaff.coldCount = 0;
        sourceStaff.newLeads = 0;

        saveStaffToStorage();
        saveCustomersToStorage();

        closeModal(transferCustomersModal);
        renderStaffDetailPage();
        renderAdminStaffTable();
        renderAdminDashboard();
        renderAdminCrmTable();

        showToast(`"${sourceStaff.name}" personeline ait tüm müşteriler ve veriler başarıyla "${targetStaff.name}" personeline aktarıldı! 🚀💼`);
      }
    });
  }

  // Subordinates Modal Logic
  const staffSubordinatesModal = document.getElementById('staffSubordinatesModal');
  const btnOpenSubordinatesModal = document.getElementById('btnOpenSubordinatesModal');
  const subordinatesModalSubtitle = document.getElementById('subordinatesModalSubtitle');
  const subordinatesModalBody = document.getElementById('subordinatesModalBody');

  function openSubordinatesModal(leaderId) {
    const leader = adminStaffList.find(s => s.id === leaderId);
    if (!leader || !staffSubordinatesModal) return;

    const subordinates = adminStaffList.filter(s => s.leaderId === leader.id);

    if (subordinatesModalSubtitle) {
      subordinatesModalSubtitle.textContent = `${leader.name} (${leader.title}) ekibindeki çalışanlar (${subordinates.length} Kişi)`;
    }

    if (subordinatesModalBody) {
      if (subordinates.length === 0) {
        subordinatesModalBody.innerHTML = `
          <div style="text-align: center; padding: 28px 16px; color: #64748B;">
            <div style="font-size: 32px; margin-bottom: 8px;">👤</div>
            <h4 style="font-size: 14px; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Bağlı Personel Bulunmuyor</h4>
            <p style="font-size: 12px;">Bu personele doğrudan bağlı çalışan alt personel kaydı bulunmamaktadır.</p>
          </div>
        `;
      } else {
        subordinatesModalBody.innerHTML = subordinates.map(sub => {
          const subTarget = sub.monthlyTarget || 15;
          const subContacts = sub.totalContacts || 18;
          const subPerf = Math.min(100, Math.round((subContacts / subTarget) * 100));
          const initials = sub.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

          return `
            <div class="subordinate-card-item">
              <div class="flex items-center gap-3" style="min-width: 0; flex: 1;">
                <div class="sub-avatar">
                  ${sub.avatar ? `<img src="${sub.avatar}" alt="${sub.name}" style="width:100%;height:100%;border-radius:10px;object-fit:cover;">` : initials}
                </div>
                <div class="sub-info">
                  <h5 class="sub-name">${sub.name}</h5>
                  <span class="sub-role">${sub.title} • ${sub.team || 'B2B Portföy'}</span>
                </div>
              </div>

              <div class="sub-stats">
                <div class="sub-stat-col">
                  <span class="sub-stat-val text-primary">${subContacts}</span>
                  <span class="sub-stat-lbl">Müşteri</span>
                </div>
                <div class="sub-stat-col">
                  <span class="sub-stat-val text-emerald-600">%${subPerf}</span>
                  <span class="sub-stat-lbl">${subContacts} / ${subTarget} Görüşme</span>
                </div>
                <button class="btn-outline btn-sm" onclick="window.openSubStaffDetail('${sub.id}')" style="margin-left: 6px; padding: 4px 10px; font-size: 11.5px; border-radius: 8px;">İncele ➔</button>
              </div>
            </div>
          `;
        }).join('');
      }
    }

    openModal(staffSubordinatesModal);
  }

  window.openSubStaffDetail = function(staffId) {
    if (staffSubordinatesModal) closeModal(staffSubordinatesModal);
    openStaffDetailPage(staffId);
  };

  if (btnOpenSubordinatesModal) {
    btnOpenSubordinatesModal.addEventListener('click', () => {
      openSubordinatesModal(currentStaffDetailId);
    });
  }

  // Target Management Modal
  const adminStaffTargetModal = document.getElementById('adminStaffTargetModal');
  const adminTargetForm = document.getElementById('adminTargetForm');
  const targetStaffId = document.getElementById('targetStaffId');
  const targetGoalInput = document.getElementById('targetGoalInput');
  const targetModalSubtitle = document.getElementById('targetModalSubtitle');

  function openStaffTargetModal(staffId) {
    const staff = adminStaffList.find(s => s.id === staffId);
    if (!staff || !adminStaffTargetModal) return;

    if (targetStaffId) targetStaffId.value = staff.id;
    if (targetGoalInput) targetGoalInput.value = staff.monthlyTarget || 15;
    if (targetModalSubtitle) targetModalSubtitle.textContent = `${staff.name} (${staff.title}) için görüşme hedefi belirleyin`;

    openModal(adminStaffTargetModal);
  }

  if (adminTargetForm) {
    adminTargetForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const sId = targetStaffId ? targetStaffId.value : '';
      const goalVal = targetGoalInput ? parseInt(targetGoalInput.value, 10) : 15;

      const staffIdx = adminStaffList.findIndex(s => s.id === sId);
      if (staffIdx !== -1) {
        adminStaffList[staffIdx].monthlyTarget = goalVal;
        saveStaffToStorage();
        renderAdminStaffTable();
        if (adminViews.viewAdminStaffDetail && adminViews.viewAdminStaffDetail.classList.contains('active')) {
          renderStaffDetailPage();
        }
        showToast(`${adminStaffList[staffIdx].name} için aylık hedef ${goalVal} görüşme olarak güncellendi! 🎯✨`);
      }
      closeModal(adminStaffTargetModal);
    });
  }

  // =========================================================
  // 3. CENTRAL CRM & MÜŞTERİ HAVUZU + CUSTOMER DETAIL PAGE
  // =========================================================
  const adminCrmTableBody = document.getElementById('adminCrmTableBody');
  const adminCrmSearchInput = document.getElementById('adminCrmSearchInput');
  const adminCrmStaffFilter = document.getElementById('adminCrmStaffFilter');
  const adminCrmStageFilter = document.getElementById('adminCrmStageFilter');
  const adminCrmTypeFilter = document.getElementById('adminCrmTypeFilter');

  if (adminCrmSearchInput) adminCrmSearchInput.addEventListener('input', renderAdminCrmTable);
  if (adminCrmStaffFilter) adminCrmStaffFilter.addEventListener('change', renderAdminCrmTable);
  if (adminCrmStageFilter) adminCrmStageFilter.addEventListener('change', renderAdminCrmTable);
  if (adminCrmTypeFilter) adminCrmTypeFilter.addEventListener('change', renderAdminCrmTable);

  function renderAdminCrmTable() {
    if (!adminCrmTableBody) return;

    const isEn = currentLang === 'en';

    // Populate Staff Filter Select
    if (adminCrmStaffFilter) {
      const currentVal = adminCrmStaffFilter.value;
      adminCrmStaffFilter.innerHTML = `
        <option value="all">${isEn ? 'All Staff' : 'Tüm Personeller'}</option>
        ${adminStaffList.map(s => `<option value="${s.name}">${s.name} (${s.isLeader ? (isEn ? '👑 Leader' : '👑 Lider') : (isEn ? 'Rep' : 'Temsilci')})</option>`).join('')}
      `;
      adminCrmStaffFilter.value = currentVal || "all";
    }

    let list = [...customers];

    // Staff filter
    const staffVal = adminCrmStaffFilter ? adminCrmStaffFilter.value : 'all';
    if (staffVal !== 'all') {
      list = list.filter(c => c.assignedStaff === staffVal || true);
    }

    // Stage filter
    const stageVal = adminCrmStageFilter ? adminCrmStageFilter.value : 'all';
    if (stageVal !== 'all') {
      if (stageVal === 'converted') {
        list = list.filter(c => c.stage === 'hot');
      } else {
        list = list.filter(c => c.stage === stageVal);
      }
    }

    // Search filter
    const q = adminCrmSearchInput ? adminCrmSearchInput.value.trim().toLowerCase() : '';
    if (q) {
      list = list.filter(c =>
        c.name.toLowerCase().includes(q) ||
        c.company.toLowerCase().includes(q) ||
        (c.phone && c.phone.includes(q))
      );
    }

    if (list.length === 0) {
      adminCrmTableBody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center py-8 text-muted" style="padding: 24px; text-align: center;">
            ${isEn ? 'No records found in client CRM.' : 'Müşteri havuzunda kayıt bulunamadı.'}
          </td>
        </tr>
      `;
      return;
    }

    adminCrmTableBody.innerHTML = list.map((c, i) => {
      const assignedStaff = adminStaffList[i % adminStaffList.length].name;
      const stagePill = c.stage === 'hot'
        ? `<span class="stage-badge-sm hot font-bold">🔥 ${isEn ? 'Hot Lead' : 'Sıcak Müşteri'}</span>`
        : c.stage === 'warm'
        ? `<span class="stage-badge-sm warm font-bold">⚡ ${isEn ? 'Warm Lead' : 'Ilık Müşteri'}</span>`
        : `<span class="stage-badge-sm cold font-bold">❄️ ${isEn ? 'Cold Lead' : 'Soğuk Müşteri'}</span>`;

      const lastNote = c.notes && c.notes.length > 0 ? c.notes[0].text : (isEn ? 'No notes yet' : 'Henüz not yok');

      return `
        <tr>
          <td data-label="${isEn ? 'Client' : 'Müşteri'}" class="crm-cell-customer">
            <div class="font-bold text-slate-900">${c.name}</div>
            <div class="text-xs text-muted font-mono">${c.phone}</div>
          </td>
          <td data-label="${isEn ? 'Company & Title' : 'Şirket & Ünvan'}">
            <div class="font-bold text-slate-800">${c.company}</div>
            <div class="text-xs text-muted">${c.title || (isEn ? 'Executive' : 'Yetkili')}</div>
          </td>
          <td data-label="${isEn ? 'Assigned Staff' : 'İlgilenen Personel'}">
            <div class="flex items-center gap-2">
              <span class="text-xs font-bold text-slate-700">👤 ${assignedStaff}</span>
            </div>
          </td>
          <td data-label="${isEn ? 'Client Status' : 'Müşteri Durumu'}">${stagePill}</td>
          <td data-label="${isEn ? 'Record Type' : 'Kayıt Türü'}">
            <span class="text-xs font-semibold text-slate-600">${i % 2 === 0 ? (isEn ? '✨ New Client' : '✨ Yeni Müşteri') : (isEn ? '📂 Existing Portfolio' : '📂 Eski Portföy')}</span>
          </td>
          <td data-label="${isEn ? 'Meeting Notes' : 'Görüşme Notları'}">
            <div class="text-xs text-slate-600 crm-last-note">
              ${lastNote}
            </div>
          </td>
          <td data-label="${isEn ? 'Date' : 'Tarih'}">
            <span class="text-xs text-muted">${isEn ? '18 Sep' : '18 Eylül'}</span>
          </td>
          <td data-label="${isEn ? 'Action' : 'Aksiyon'}" class="text-right crm-cell-actions">
            <div class="admin-table-actions">
              <a href="tel:${c.phone}" class="admin-table-action-btn" title="${isEn ? 'Call Now:' : 'Hemen Ara:'} ${c.phone}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              </a>
              <a href="mailto:${c.email || ''}" class="admin-table-action-btn" title="${isEn ? 'Send Email:' : 'E-Posta Gönder:'} ${c.email || ''}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
              </a>
              <button class="admin-table-action-btn primary btn-crm-inspect" data-cust-id="${c.id || c.phone}" title="${isEn ? 'Open Client Detail Card' : 'Müşteri Detay Kartını Aç'}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');

    adminCrmTableBody.querySelectorAll('.btn-crm-inspect').forEach(btn => {
      btn.addEventListener('click', () => {
        const cId = btn.getAttribute('data-cust-id');
        openCustomerDetailPage(cId);
      });
    });
  }

  // 3.1 DEDICATED CUSTOMER DETAIL PAGE
  window.openAdminCustomerDetail = function(customerId) {
    openCustomerDetailPage(customerId);
  };

  function openCustomerDetailPage(customerId) {
    const cust = customers.find(c => (c.id && c.id === customerId) || c.phone === customerId || c.name === customerId) || customers[0];
    if (!cust) return;

    navigateToAdminView('viewAdminCustomerDetail');

    const avatarEl = document.getElementById('crmDetAvatar');
    const cardName = document.getElementById('crmDetCardName');
    const cardRole = document.getElementById('crmDetCardRole');
    const phoneVal = document.getElementById('crmDetPhoneVal');
    const emailVal = document.getElementById('crmDetEmailVal');
    const assignedVal = document.getElementById('crmDetAssignedStaffVal');
    const stageBadge = document.getElementById('crmDetStageBadge');
    const callBtn = document.getElementById('crmDetCallBtn');
    const mailBtn = document.getElementById('crmDetMailBtn');

    if (cardName) cardName.textContent = cust.name;
    if (cardRole) cardRole.textContent = cust.title || 'Şirket Yetkilisi';

    if (stageBadge) {
      stageBadge.className = `stage-badge-sm font-bold ${cust.stage}`;
      stageBadge.textContent = cust.stage === 'hot' ? 'Sıcak Müşteri' : cust.stage === 'warm' ? 'Ilık Müşteri' : 'Soğuk Müşteri';
    }

    if (phoneVal) {
      phoneVal.textContent = cust.phone;
      phoneVal.href = `tel:${cust.phone.replace(/\s+/g, '')}`;
    }
    if (callBtn) callBtn.href = `tel:${cust.phone.replace(/\s+/g, '')}`;

    if (emailVal) {
      emailVal.textContent = cust.email || 'tanimli-degil@sirket.com';
      emailVal.href = `mailto:${cust.email || ''}`;
    }
    if (mailBtn) mailBtn.href = `mailto:${cust.email || ''}`;

    if (avatarEl) {
      avatarEl.textContent = cust.initials || cust.name.substring(0, 2).toUpperCase();
    }

    if (assignedVal) {
      assignedVal.innerHTML = `<span class="font-bold text-slate-800">${cust.assignedStaff || 'Muhiddin Öktem'}</span>`;
    }

    // Render meetings planned with this customer
    const meetsList = document.getElementById('crmDetMeetingsList');
    if (meetsList) {
      const custMeets = meetings.filter(m => (m.customer && m.customer.includes(cust.name)) || true).slice(0, 3);
      meetsList.innerHTML = custMeets.map((m, idx) => `
        <div class="cust-meeting-card-row">
          <div class="cust-meet-info">
            <span class="cust-meet-title">${m.title}</span>
            <div class="cust-meet-meta">
              <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                ${m.date} • ${m.time}
              </span>
              <span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                ${m.type}
              </span>
            </div>
          </div>
          <button class="btn-cust-meet-detail" onclick="window.openMeetingDetail(${idx})" title="Toplantı Detayını İncele">Detay</button>
        </div>
      `).join('');
    }

    // Render notes & voice notes timeline
    const notesList = document.getElementById('crmDetNotesList');
    const notesCount = document.getElementById('crmDetNotesCount');
    const custNotes = cust.notes || [
      { id: "note-1", type: "voice", text: "Kemal Bey ile yüz yüze görüştük, 250 adet kurumsal MonaCard teklifi hazırlıyoruz. Haftaya Çarşamba sözleşme imzalanabilir.", duration: "0:42", time: "Dün 16:45", hubspotSynced: true },
      { id: "note-2", type: "text", text: "Kurumsal kartvizit ve portal çözümlerimiz incelendi, teknik şartname gönderildi.", time: "15 Eylül 11:20", hubspotSynced: true }
    ];

    if (notesCount) notesCount.textContent = `${custNotes.length} Not Kayıtlı`;
    if (notesList) {
      notesList.innerHTML = custNotes.map((n, i) => {
        if (n.type === 'voice') {
          return `
            <div class="cust-note-card voice-note">
              <div class="cust-note-header">
                <div class="flex items-center gap-2">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                  <span class="cust-note-type-label">Sesli Görüşme Notu</span>
                </div>
                <span class="cust-note-time">${n.time || 'Dün 16:45'}</span>
              </div>
              
              <!-- Voice Note Audio Player -->
              <div class="voice-player-bar">
                <button type="button" class="voice-play-btn" onclick="window.toggleVoiceNotePlay(this, '${n.id || 'voice-' + i}')" title="Sesli Notu Dinle">
                  <svg class="icon-play" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                  <svg class="icon-pause hidden" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>
                </button>
                <div class="voice-waveform-wrap">
                  <div class="voice-wave-bars">
                    <span class="w-bar" style="height:8px;"></span>
                    <span class="w-bar" style="height:16px;"></span>
                    <span class="w-bar" style="height:22px;"></span>
                    <span class="w-bar" style="height:14px;"></span>
                    <span class="w-bar" style="height:26px;"></span>
                    <span class="w-bar" style="height:18px;"></span>
                    <span class="w-bar" style="height:12px;"></span>
                    <span class="w-bar" style="height:24px;"></span>
                    <span class="w-bar" style="height:15px;"></span>
                    <span class="w-bar" style="height:20px;"></span>
                    <span class="w-bar" style="height:10px;"></span>
                    <span class="w-bar" style="height:19px;"></span>
                    <span class="w-bar" style="height:14px;"></span>
                    <span class="w-bar" style="height:7px;"></span>
                  </div>
                  <div class="voice-progress-track">
                    <div class="voice-progress-fill"></div>
                  </div>
                </div>
                <span class="voice-duration-label">${n.duration || '0:42'}</span>
              </div>

              <p class="cust-note-text">${n.text}</p>
              
              <div class="flex items-center gap-1 mt-2">
                <span class="hubspot-sync-badge">
                  <span class="hubspot-dot"></span> HubSpot Eşleşti
                </span>
              </div>
            </div>
          `;
        } else {
          return `
            <div class="cust-note-card text-note">
              <div class="cust-note-header">
                <div class="flex items-center gap-2">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                  <span class="cust-note-type-label">Yazılı Not</span>
                </div>
                <span class="cust-note-time">${n.time || '15 Eylül 11:20'}</span>
              </div>
              <p class="cust-note-text">${n.text}</p>
              <div class="flex items-center gap-1 mt-2">
                <span class="hubspot-sync-badge">
                  <span class="hubspot-dot"></span> HubSpot Eşleşti
                </span>
              </div>
            </div>
          `;
        }
      }).join('');
    }
  }

  // Voice Note Playback Manager (Web Audio API Interactive Sound Simulation)
  let activeVoicePlayerInterval = null;
  let activeVoiceContext = null;

  window.toggleVoiceNotePlay = function(btnEl, noteId) {
    const parent = btnEl.closest('.voice-note');
    if (!parent) return;

    const playIcon = btnEl.querySelector('.icon-play');
    const pauseIcon = btnEl.querySelector('.icon-pause');
    const waveBars = parent.querySelectorAll('.w-bar');
    const progressFill = parent.querySelector('.voice-progress-fill');
    const durationLabel = parent.querySelector('.voice-duration-label');
    const isPlaying = parent.classList.contains('playing');

    // Stop any other playing notes
    document.querySelectorAll('.cust-note-card.playing').forEach(card => {
      card.classList.remove('playing');
      const pBtn = card.querySelector('.voice-play-btn');
      if (pBtn) {
        pBtn.querySelector('.icon-play')?.classList.remove('hidden');
        pBtn.querySelector('.icon-pause')?.classList.add('hidden');
      }
    });

    if (activeVoicePlayerInterval) {
      clearInterval(activeVoicePlayerInterval);
      activeVoicePlayerInterval = null;
    }

    if (isPlaying) {
      parent.classList.remove('playing');
      playIcon?.classList.remove('hidden');
      pauseIcon?.classList.add('hidden');
      if (durationLabel) durationLabel.textContent = "0:42";
      if (progressFill) progressFill.style.width = "0%";
      return;
    }

    // Start playback
    parent.classList.add('playing');
    playIcon?.classList.add('hidden');
    pauseIcon?.classList.remove('hidden');

    // Play subtle audio tone using Web Audio API
    try {
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      if (AudioContext) {
        activeVoiceContext = new AudioContext();
        const osc = activeVoiceContext.createOscillator();
        const gain = activeVoiceContext.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(520, activeVoiceContext.currentTime);
        osc.frequency.exponentialRampToValueAtTime(780, activeVoiceContext.currentTime + 0.15);
        gain.gain.setValueAtTime(0.08, activeVoiceContext.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, activeVoiceContext.currentTime + 0.4);
        osc.connect(gain);
        gain.connect(activeVoiceContext.destination);
        osc.start();
        osc.stop(activeVoiceContext.currentTime + 0.4);
      }
    } catch (e) {
      console.warn('Audio note tone', e);
    }

    showToast("Sesli görüşme kaydı dinleniyor... 🎧");

    let currentSec = 0;
    const totalSec = 42;

    activeVoicePlayerInterval = setInterval(() => {
      currentSec += 1;
      const pct = Math.min(100, (currentSec / totalSec) * 100);
      if (progressFill) progressFill.style.width = `${pct}%`;

      const remainingSec = totalSec - currentSec;
      const min = Math.floor(remainingSec / 60);
      const sec = remainingSec % 60;
      if (durationLabel) durationLabel.textContent = `${min}:${String(sec).padStart(2, '0')}`;

      // Randomize wave bars for realistic audio wave effect
      waveBars.forEach(bar => {
        const randomH = Math.floor(Math.random() * 20) + 6;
        bar.style.height = `${randomH}px`;
      });

      if (currentSec >= totalSec) {
        clearInterval(activeVoicePlayerInterval);
        activeVoicePlayerInterval = null;
        parent.classList.remove('playing');
        playIcon?.classList.remove('hidden');
        pauseIcon?.classList.add('hidden');
        if (durationLabel) durationLabel.textContent = "0:42";
        if (progressFill) progressFill.style.width = "0%";
        showToast("Ses kaydı oynatımı tamamlandı. ✨");
      }
    }, 250);
  };

  const btnBackToCrmList = document.getElementById('btnBackToCrmList');
  if (btnBackToCrmList) {
    btnBackToCrmList.addEventListener('click', () => {
      navigateToAdminView('viewAdminCrm');
    });
  }

  const btnCrmAddMeetForCust = document.getElementById('btnCrmAddMeetForCust');
  if (btnCrmAddMeetForCust) {
    btnCrmAddMeetForCust.addEventListener('click', () => {
      window.openCreateMeetingModal("");
    });
  }

  // =========================================================
  // 4. CENTRAL CALENDAR & GOOGLE CALENDAR MONTHLY GRID
  // =========================================================
  const tabAdminAllMeetings = document.getElementById('tabAdminAllMeetings');
  const tabAdminPersonalMeetings = document.getElementById('tabAdminPersonalMeetings');
  const adminMeetingsGrid = document.getElementById('adminMeetingsGrid');
  const adminMonthlyCalendarView = document.getElementById('adminMonthlyCalendarView');
  const adminCalStaffSelect = document.getElementById('adminCalStaffSelect');
  const calStaffFilterWrap = document.getElementById('calStaffFilterWrap');
  const btnAdminCreateMeeting = document.getElementById('btnAdminCreateMeeting');
  const btnCalViewMonth = document.getElementById('btnCalViewMonth');
  const btnCalViewCards = document.getElementById('btnCalViewCards');

  const gcalMonthTitle = document.getElementById('gcalMonthTitle');
  const btnGcalPrevMonth = document.getElementById('btnGcalPrevMonth');
  const btnGcalNextMonth = document.getElementById('btnGcalNextMonth');
  const btnGcalToday = document.getElementById('btnGcalToday');
  const gcalDaysGrid = document.getElementById('gcalDaysGrid');

  // Calendar View Switchers (Monthly Grid vs Cards)
  if (btnCalViewMonth) {
    btnCalViewMonth.addEventListener('click', () => {
      btnCalViewMonth.classList.add('active');
      if (btnCalViewCards) btnCalViewCards.classList.remove('active');
      currentCalendarViewMode = 'month';
      if (adminMonthlyCalendarView) adminMonthlyCalendarView.classList.remove('hidden');
      if (adminMeetingsGrid) adminMeetingsGrid.classList.add('hidden');
      renderAdminMonthlyCalendar();
    });
  }

  if (btnCalViewCards) {
    btnCalViewCards.addEventListener('click', () => {
      btnCalViewCards.classList.add('active');
      if (btnCalViewMonth) btnCalViewMonth.classList.remove('active');
      currentCalendarViewMode = 'cards';
      if (adminMonthlyCalendarView) adminMonthlyCalendarView.classList.add('hidden');
      if (adminMeetingsGrid) adminMeetingsGrid.classList.remove('hidden');
      renderAdminCalendarCards();
    });
  }

  // Tab switcher
  if (tabAdminAllMeetings) {
    tabAdminAllMeetings.addEventListener('click', () => {
      tabAdminAllMeetings.classList.add('active');
      if (tabAdminPersonalMeetings) tabAdminPersonalMeetings.classList.remove('active');
      currentCalendarTab = 'all';
      if (calStaffFilterWrap) calStaffFilterWrap.style.display = 'flex';
      renderAdminCalendar();
    });
  }

  if (tabAdminPersonalMeetings) {
    tabAdminPersonalMeetings.addEventListener('click', () => {
      tabAdminPersonalMeetings.classList.add('active');
      if (tabAdminAllMeetings) tabAdminAllMeetings.classList.remove('active');
      currentCalendarTab = 'personal';
      if (calStaffFilterWrap) calStaffFilterWrap.style.display = 'none';
      renderAdminCalendar();
    });
  }

  if (adminCalStaffSelect) {
    adminCalStaffSelect.addEventListener('change', renderAdminCalendar);
  }

  // Google Calendar Navigation
  if (btnGcalPrevMonth) {
    btnGcalPrevMonth.addEventListener('click', () => {
      calCurrentMonth--;
      if (calCurrentMonth < 0) {
        calCurrentMonth = 11;
        calCurrentYear--;
      }
      renderAdminMonthlyCalendar();
    });
  }

  if (btnGcalNextMonth) {
    btnGcalNextMonth.addEventListener('click', () => {
      calCurrentMonth++;
      if (calCurrentMonth > 11) {
        calCurrentMonth = 0;
        calCurrentYear++;
      }
      renderAdminMonthlyCalendar();
    });
  }

  if (btnGcalToday) {
    btnGcalToday.addEventListener('click', () => {
      calCurrentYear = 2026;
      calCurrentMonth = 8; // September
      renderAdminMonthlyCalendar();
      showToast("Eylül 2026 bugünkü takvime dönüldü 📅");
    });
  }

  if (btnAdminCreateMeeting) {
    btnAdminCreateMeeting.addEventListener('click', () => {
      window.openCreateMeetingModal("");
    });
  }

  function renderAdminCalendar() {
    // Populate Staff Select in Calendar
    if (adminCalStaffSelect) {
      const currentVal = adminCalStaffSelect.value;
      adminCalStaffSelect.innerHTML = `
        <option value="all">Tüm Personellerin Randevuları</option>
        ${adminStaffList.map(s => `<option value="${s.name}">${s.name}</option>`).join('')}
      `;
      adminCalStaffSelect.value = currentVal || "all";
    }

    if (currentCalendarViewMode === 'month') {
      if (adminMonthlyCalendarView) adminMonthlyCalendarView.classList.remove('hidden');
      if (adminMeetingsGrid) adminMeetingsGrid.classList.add('hidden');
      renderAdminMonthlyCalendar();
    } else {
      if (adminMonthlyCalendarView) adminMonthlyCalendarView.classList.add('hidden');
      if (adminMeetingsGrid) adminMeetingsGrid.classList.remove('hidden');
      renderAdminCalendarCards();
    }
  }

  // Google Calendar Monthly Grid Renderer
  function renderAdminMonthlyCalendar() {
    if (!gcalDaysGrid) return;

    if (gcalMonthTitle) {
      gcalMonthTitle.textContent = `${turkishMonths[calCurrentMonth]} ${calCurrentYear}`;
    }

    // Days in current month & first day of month (Monday start)
    const firstDayIndex = new Date(calCurrentYear, calCurrentMonth, 1).getDay(); // 0 is Sun
    const firstDayMondayBased = (firstDayIndex + 6) % 7; // 0 is Mon, 6 is Sun
    const daysInMonth = new Date(calCurrentYear, calCurrentMonth + 1, 0).getDate();
    const daysInPrevMonth = new Date(calCurrentYear, calCurrentMonth, 0).getDate();

    const totalCells = (firstDayMondayBased + daysInMonth) > 35 ? 42 : 35;

    // Filter relevant meetings
    let calendarEvents = [];
    if (currentCalendarTab === 'personal') {
      calendarEvents = adminPersonalMeetings.map(m => ({ ...m, isPersonal: true }));
    } else {
      const selectedStaff = adminCalStaffSelect ? adminCalStaffSelect.value : 'all';
      calendarEvents = meetings.map((m, idx) => ({
        ...m,
        assignedStaff: adminStaffList[idx % adminStaffList.length].name,
        meetingIndex: idx
      }));

      if (selectedStaff !== 'all') {
        calendarEvents = calendarEvents.filter(m => m.assignedStaff === selectedStaff);
      }
    }

    let gridHtml = '';

    for (let i = 0; i < totalCells; i++) {
      let dayNum = 0;
      let isOtherMonth = false;
      let isToday = false;
      let cellDateStr = '';

      if (i < firstDayMondayBased) {
        // Prev Month
        dayNum = daysInPrevMonth - (firstDayMondayBased - i - 1);
        isOtherMonth = true;
        const prevM = calCurrentMonth === 0 ? 11 : calCurrentMonth - 1;
        const prevY = calCurrentMonth === 0 ? calCurrentYear - 1 : calCurrentYear;
        cellDateStr = `${prevY}-${String(prevM + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
      } else if (i < firstDayMondayBased + daysInMonth) {
        // Current Month
        dayNum = i - firstDayMondayBased + 1;
        cellDateStr = `${calCurrentYear}-${String(calCurrentMonth + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
        if (calCurrentYear === 2026 && calCurrentMonth === 8 && dayNum === 20) {
          isToday = true;
        }
      } else {
        // Next Month
        dayNum = i - (firstDayMondayBased + daysInMonth) + 1;
        isOtherMonth = true;
        const nextM = calCurrentMonth === 11 ? 0 : calCurrentMonth + 1;
        const nextY = calCurrentMonth === 11 ? calCurrentYear + 1 : calCurrentYear;
        cellDateStr = `${nextY}-${String(nextM + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
      }

      // Check events on this date
      const dayEvents = calendarEvents.filter(e => {
        if (!e.date) return false;
        // Normalize date to YYYY-MM-DD
        const eDateStr = e.date.trim();
        return eDateStr === cellDateStr;
      });

      const eventsMarkup = dayEvents.map(e => {
        let pillTypeClass = 'meet';
        if (e.type && e.type.toLowerCase().includes('zoom')) pillTypeClass = 'zoom';
        else if (e.type && (e.type.toLowerCase().includes('yüz') || e.type.toLowerCase().includes('ofis'))) pillTypeClass = 'inperson';

        return `
          <div class="gcal-event-pill ${pillTypeClass}" title="${e.title} (${e.time} - ${e.assignedStaff || 'VIP'})" onclick="event.stopPropagation(); window.openMeetingDetail(${e.meetingIndex !== undefined ? e.meetingIndex : 0})">
            <span class="font-mono">${e.time || '10:00'}</span>
            <strong>${e.title}</strong>
          </div>
        `;
      }).join('');

      gridHtml += `
        <div class="gcal-day-cell ${isOtherMonth ? 'other-month' : ''} ${isToday ? 'today' : ''}" data-date="${cellDateStr}" onclick="window.quickPlanForDate('${cellDateStr}')">
          <div class="gcal-day-header">
            <span class="gcal-day-number">${dayNum}</span>
            ${isToday ? '<span class="gcal-today-badge">Bugün</span>' : ''}
          </div>
          <div class="gcal-day-events">
            ${eventsMarkup}
          </div>
        </div>
      `;
    }

    gcalDaysGrid.innerHTML = gridHtml;
  }

  // Quick plan on day click
  window.quickPlanForDate = function(dateStr) {
    window.openCreateMeetingModal("", dateStr);
  };

  // Card view renderer (Alternative view)
  function renderAdminCalendarCards() {
    if (!adminMeetingsGrid) return;

    if (currentCalendarTab === 'personal') {
      adminMeetingsGrid.innerHTML = adminPersonalMeetings.map(m => `
        <div class="admin-meeting-card card-blue-glow">
          <div>
            <div class="admin-meet-top">
              <span class="stage-badge-sm hot">⭐ Yönetici Özel VIP</span>
              <span class="text-xs font-mono font-bold text-slate-500">${m.time}</span>
            </div>
            <h4 class="admin-meet-title">${m.title}</h4>
            <div class="admin-meet-meta">
              <span>📅 ${m.date}</span>
              <span>👥 Katılımcılar: <strong>${m.participants}</strong></span>
              <span>🌐 Platform: <strong>${m.type}</strong></span>
            </div>
          </div>
          <div class="flex items-center justify-between mt-3 pt-3 border-t">
            <span class="meeting-status-badge">Planlandı</span>
            <button class="btn-primary btn-sm" onclick="alert('Toplantı bağlantısı açılıyor: ${m.type}')">Toplantıya Katıl ➔</button>
          </div>
        </div>
      `).join('');
      return;
    }

    let allMeetingsList = [...meetings];
    const selectedStaff = adminCalStaffSelect ? adminCalStaffSelect.value : 'all';

    adminMeetingsGrid.innerHTML = allMeetingsList.map((m, i) => {
      const assignedStaff = adminStaffList[i % adminStaffList.length].name;
      if (selectedStaff !== 'all' && assignedStaff !== selectedStaff) {
        return '';
      }
      const isPast = isMeetingPast(m);

      return `
        <div class="admin-meeting-card ${isPast ? 'bg-slate-50' : ''}">
          <div>
            <div class="admin-meet-top">
              <span class="admin-meet-staff-tag">👤 ${assignedStaff}</span>
              <span class="text-xs font-mono font-bold text-slate-500">${m.time}</span>
            </div>
            <h4 class="admin-meet-title">${m.title}</h4>
            <div class="admin-meet-meta">
              <span>📅 ${m.date}</span>
              <span>👥 Müşteri: <strong>${m.customer || 'Belirtilmedi'}</strong></span>
              <span>🌐 Platform: <strong>${m.type}</strong></span>
            </div>
          </div>
          <div class="flex items-center justify-between mt-3 pt-3 border-t">
            <span class="meeting-status-badge ${isPast ? 'done' : ''}">${isPast ? 'Tamamlandı' : 'Planlandı'}</span>
            <button class="btn-outline btn-sm" onclick="window.openMeetingDetail(${i})">Detay &amp; Düzenle</button>
          </div>
        </div>
      `;
    }).join('');
  }

  // Staff Modal for Add / Edit
  const btnAdminAddStaff = document.getElementById('btnAdminAddStaff');
  const adminStaffModal = document.getElementById('adminStaffModal');
  const adminStaffForm = document.getElementById('adminStaffForm');
  const staffInputIsLeader = document.getElementById('staffInputIsLeader');
  const staffInputLeaderId = document.getElementById('staffInputLeaderId');
  const staffLeaderSelectGroup = document.getElementById('staffLeaderSelectGroup');
  const staffInputMonthlyTarget = document.getElementById('staffInputMonthlyTarget');

  if (btnAdminAddStaff) {
    btnAdminAddStaff.addEventListener('click', () => {
      openStaffModalForAdd();
    });
  }

  function openStaffModalForAdd() {
    if (!adminStaffModal || !adminStaffForm) return;
    document.getElementById('staffModalTitle').textContent = "Yeni Personel Ekle";
    document.getElementById('editStaffId').value = "";
    adminStaffForm.reset();
    if (staffInputMonthlyTarget) staffInputMonthlyTarget.value = "15";

    populateLeadersSelectDropdown("");

    if (staffLeaderSelectGroup) staffLeaderSelectGroup.style.display = 'block';
    openModal(adminStaffModal);
  }

  function openStaffModalForEdit(staffId) {
    const staff = adminStaffList.find(s => s.id === staffId);
    if (!staff || !adminStaffModal || !adminStaffForm) return;

    document.getElementById('staffModalTitle').textContent = "Personel Bilgilerini & Liderliği Düzenle";
    document.getElementById('editStaffId').value = staff.id;
    document.getElementById('staffInputName').value = staff.name;
    document.getElementById('staffInputTitle').value = staff.title;
    document.getElementById('staffInputEmail').value = staff.email;
    document.getElementById('staffInputPhone').value = staff.phone;
    if (staffInputMonthlyTarget) staffInputMonthlyTarget.value = staff.monthlyTarget || 15;

    if (staffInputIsLeader) {
      staffInputIsLeader.checked = !!staff.isLeader;
    }

    populateLeadersSelectDropdown(staff.leaderId || "", staff.id);

    if (staffLeaderSelectGroup) {
      staffLeaderSelectGroup.style.display = staff.isLeader ? 'none' : 'block';
    }

    openModal(adminStaffModal);
  }

  function populateLeadersSelectDropdown(selectedId = "", excludeId = "") {
    if (!staffInputLeaderId) return;
    const leaders = adminStaffList.filter(s => s.isLeader && s.id !== excludeId);
    staffInputLeaderId.innerHTML = `
      <option value="">-- Bağımsız / Doğrudan Genel Müdüre Bağlı --</option>
      ${leaders.map(l => `<option value="${l.id}" ${l.id === selectedId ? 'selected' : ''}>👑 ${l.name} (${l.title})</option>`).join('')}
    `;
  }

  if (staffInputIsLeader && staffLeaderSelectGroup) {
    staffInputIsLeader.addEventListener('change', (e) => {
      if (e.target.checked) {
        staffLeaderSelectGroup.style.display = 'none';
        if (staffInputLeaderId) staffInputLeaderId.value = "";
      } else {
        staffLeaderSelectGroup.style.display = 'block';
      }
    });
  }

  if (adminStaffForm) {
    adminStaffForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const editId = document.getElementById('editStaffId').value;
      const name = document.getElementById('staffInputName').value.trim();
      const title = document.getElementById('staffInputTitle').value.trim();
      const email = document.getElementById('staffInputEmail').value.trim();
      const phone = document.getElementById('staffInputPhone').value.trim();
      const monthlyTarget = staffInputMonthlyTarget ? parseInt(staffInputMonthlyTarget.value, 10) || 15 : 15;
      const isLeader = staffInputIsLeader ? staffInputIsLeader.checked : false;
      const leaderId = isLeader ? "" : (staffInputLeaderId ? staffInputLeaderId.value : "");

      if (editId) {
        // Edit existing
        const idx = adminStaffList.findIndex(s => s.id === editId);
        if (idx !== -1) {
          adminStaffList[idx].name = name;
          adminStaffList[idx].title = title;
          adminStaffList[idx].email = email;
          adminStaffList[idx].phone = phone;
          adminStaffList[idx].monthlyTarget = monthlyTarget;
          adminStaffList[idx].isLeader = isLeader;
          adminStaffList[idx].leaderId = leaderId;
          showToast(`${name} güncellendi! ✅`);
        }
      } else {
        // Add new
        const newStaff = {
          id: "staff-" + Date.now(),
          name,
          title,
          email,
          phone,
          avatar: "",
          isLeader,
          leaderId,
          monthlyTarget,
          newLeads: 0,
          existingLeads: 0,
          totalContacts: 0,
          hotCount: 0,
          warmCount: 0,
          coldCount: 0,
          convertedCount: 0,
          revenue: 0,
          satisfactionRate: 5.0,
          score: 80
        };
        adminStaffList.unshift(newStaff);
        showToast(`${name} personele eklendi! 🚀`);
      }

      saveStaffToStorage();
      renderAdminStaffTable();
      renderAdminDashboard();
      closeModal(adminStaffModal);
    });
  }

  // 5. SETTINGS: BRAND COLOR, THEME & STAFF UI PERMISSIONS (Requirement 2)
  const companyColorPicker = document.getElementById('companyColorPicker');
  const colorHexInput = document.getElementById('colorHexInput');
  const colorHexText = document.getElementById('colorHexText');
  const presetColorDots = document.querySelectorAll('.preset-color-dot');
  const radioThemeLight = document.getElementById('radioThemeLight');
  const radioThemeDark = document.getElementById('radioThemeDark');
  const btnSaveCompanySettings = document.getElementById('btnSaveCompanySettings');

  // Staff UI toggles
  const settingStaffHubspot = document.getElementById('settingStaffHubspot');
  const settingStaffVoiceNote = document.getElementById('settingStaffVoiceNote');
  const settingStaffProducts = document.getElementById('settingStaffProducts');
  const settingStaffSocial = document.getElementById('settingStaffSocial');
  const settingStaffReviews = document.getElementById('settingStaffReviews');
  const settingStaffVCard = document.getElementById('settingStaffVCard');

  // Real-time Native Color Picker Input
  if (companyColorPicker) {
    companyColorPicker.addEventListener('input', (e) => {
      const color = e.target.value;
      if (colorHexInput) colorHexInput.value = color.toUpperCase();
      if (colorHexText) colorHexText.textContent = color.toUpperCase();
      adminSettings.brandColor = color;
      applyCompanySettingsToApp();
      presetColorDots.forEach(dot => {
        if (dot.getAttribute('data-color').toLowerCase() === color.toLowerCase()) {
          dot.classList.add('active');
        } else {
          dot.classList.remove('active');
        }
      });
    });
  }

  // Active Hex Input Field Handlers
  if (colorHexInput) {
    const applyTypedHex = (val) => {
      let hex = val.trim();
      if (!hex.startsWith('#')) hex = '#' + hex;
      if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(hex)) {
        if (hex.length === 4) {
          hex = '#' + hex[1] + hex[1] + hex[2] + hex[2] + hex[3] + hex[3];
        }
        adminSettings.brandColor = hex;
        if (companyColorPicker) companyColorPicker.value = hex;
        if (colorHexText) colorHexText.textContent = hex.toUpperCase();
        applyCompanySettingsToApp();
        presetColorDots.forEach(dot => {
          if (dot.getAttribute('data-color').toLowerCase() === hex.toLowerCase()) {
            dot.classList.add('active');
          } else {
            dot.classList.remove('active');
          }
        });
      }
    };

    colorHexInput.addEventListener('input', (e) => {
      applyTypedHex(e.target.value);
    });

    colorHexInput.addEventListener('change', (e) => {
      let val = e.target.value.trim();
      if (!val.startsWith('#')) val = '#' + val;
      if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(val)) {
        if (val.length === 4) {
          val = '#' + val[1] + val[1] + val[2] + val[2] + val[3] + val[3];
        }
        e.target.value = val.toUpperCase();
        applyTypedHex(val);
      } else {
        e.target.value = (adminSettings.brandColor || '#00A86B').toUpperCase();
      }
    });
  }

  // Preset Luxury Color Dots
  presetColorDots.forEach(dot => {
    dot.addEventListener('click', () => {
      presetColorDots.forEach(d => d.classList.remove('active'));
      dot.classList.add('active');
      const color = dot.getAttribute('data-color');
      if (companyColorPicker) companyColorPicker.value = color;
      if (colorHexInput) colorHexInput.value = color.toUpperCase();
      if (colorHexText) colorHexText.textContent = color.toUpperCase();
      adminSettings.brandColor = color;
      applyCompanySettingsToApp();
      showToast(`Kurumsal tema rengi "${color}" olarak ayarlandı 🎨`);
    });
  });

  // Dark / Light Mode Radio Cards
  if (radioThemeLight) {
    radioThemeLight.addEventListener('change', () => {
      if (radioThemeLight.checked) {
        adminSettings.themeMode = 'light';
        applyCompanySettingsToApp();
      }
    });
  }

  if (radioThemeDark) {
    radioThemeDark.addEventListener('change', () => {
      if (radioThemeDark.checked) {
        adminSettings.themeMode = 'dark';
        applyCompanySettingsToApp();
      }
    });
  }

  // Save Company Settings Button
  if (btnSaveCompanySettings) {
    btnSaveCompanySettings.addEventListener('click', () => {
      adminSettings.staffFeatures = {
        hubspot: settingStaffHubspot ? settingStaffHubspot.checked : true,
        voiceNotes: settingStaffVoiceNote ? settingStaffVoiceNote.checked : true,
        products: settingStaffProducts ? settingStaffProducts.checked : true,
        socialLinks: settingStaffSocial ? settingStaffSocial.checked : true,
        reviews: settingStaffReviews ? settingStaffReviews.checked : true,
        vcard: settingStaffVCard ? settingStaffVCard.checked : true
      };

      // Save integrations
      adminSettings.integrations = {
        google: {
          calendarSync: document.getElementById('integGoogleCalendarSync') ? document.getElementById('integGoogleCalendarSync').checked : true,
          meetAuto: document.getElementById('integGoogleMeetAuto') ? document.getElementById('integGoogleMeetAuto').checked : true,
          reviewUrl: document.getElementById('integGoogleReviewUrl') ? document.getElementById('integGoogleReviewUrl').value.trim() : 'https://g.page/r/vedubox/review',
          clientId: document.getElementById('integGoogleClientId') ? document.getElementById('integGoogleClientId').value.trim() : '782910482910-vedubox.apps.googleusercontent.com'
        },
        zoom: {
          autoLink: document.getElementById('integZoomAutoLink') ? document.getElementById('integZoomAutoLink').checked : true,
          waitingRoom: document.getElementById('integZoomWaitingRoom') ? document.getElementById('integZoomWaitingRoom').checked : true,
          accountId: document.getElementById('integZoomAccountId') ? document.getElementById('integZoomAccountId').value.trim() : 'zoom_acc_98412894',
          clientId: document.getElementById('integZoomClientId') ? document.getElementById('integZoomClientId').value.trim() : 'zm_client_849201948',
          clientSecret: document.getElementById('integZoomClientSecret') ? document.getElementById('integZoomClientSecret').value.trim() : 'zm_secret_k84920f92j1923'
        },
        hubspot: {
          autoSyncContacts: document.getElementById('integHubspotAutoSyncContacts') ? document.getElementById('integHubspotAutoSyncContacts').checked : true,
          autoSyncVoice: document.getElementById('integHubspotAutoSyncVoice') ? document.getElementById('integHubspotAutoSyncVoice').checked : true,
          portalId: document.getElementById('integHubspotPortalId') ? document.getElementById('integHubspotPortalId').value.trim() : '4829104',
          stage: document.getElementById('integHubspotStage') ? document.getElementById('integHubspotStage').value : 'lead',
          apiKey: document.getElementById('integHubspotApiKey') ? document.getElementById('integHubspotApiKey').value.trim() : 'demo_hubspot_token_placeholder'
        },
        salesforce: {
          autoSyncContacts: document.getElementById('integSalesforceAutoSyncContacts') ? document.getElementById('integSalesforceAutoSyncContacts').checked : true,
          autoSyncVoice: document.getElementById('integSalesforceAutoSyncVoice') ? document.getElementById('integSalesforceAutoSyncVoice').checked : true,
          autoOpp: document.getElementById('integSalesforceAutoOpp') ? document.getElementById('integSalesforceAutoOpp').checked : false,
          instanceUrl: document.getElementById('integSalesforceInstanceUrl') ? document.getElementById('integSalesforceInstanceUrl').value.trim() : 'https://vedubox.my.salesforce.com',
          leadStatus: document.getElementById('integSalesforceLeadStatus') ? document.getElementById('integSalesforceLeadStatus').value : 'Open - Not Contacted',
          consumerKey: document.getElementById('integSalesforceConsumerKey') ? document.getElementById('integSalesforceConsumerKey').value.trim() : '3MVG9lKc_I.3.4u8912384910283401923',
          consumerSecret: document.getElementById('integSalesforceConsumerSecret') ? document.getElementById('integSalesforceConsumerSecret').value.trim() : '48920194820194820194'
        }
      };

      saveAdminSettingsToStorage();
      applyCompanySettingsToApp();
      applyStaffInterfaceVisibility();
      showToast("Tüm kurumsal ayarlar, personel yetkileri & entegrasyonlar kaydedildi! 💾✨");
    });
  }

  // Enterprise Integrations Tab Switcher
  const integTabBtns = document.querySelectorAll('.integration-tab-btn');
  const integPanels = document.querySelectorAll('.integration-panel');

  integTabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-integ-tab');
      integTabBtns.forEach(b => b.classList.remove('active'));
      integPanels.forEach(p => p.classList.remove('active'));

      btn.classList.add('active');
      const targetPanel = document.getElementById(targetId);
      if (targetPanel) targetPanel.classList.add('active');
    });
  });

  // Integration Test Action Simulator
  window.testIntegration = function(service) {
    if (service === 'google') {
      showToast("Google Workspace & Google Meet bağlantısı başarıyla doğrulandı ve 18 randevu senkronize edildi! 🚀");
    } else if (service === 'zoom') {
      showToast("Zoom API bağlantısı başarılı (200 OK) & Server-to-Server OAuth yetkisi aktif! 📹");
    } else if (service === 'hubspot') {
      showToast("HubSpot CRM bağlantısı doğrulandı & 148 müşteri kişisi başarıyla senkronize edildi! 🟠✨");
    } else if (service === 'salesforce') {
      showToast("Salesforce CRM Cloud bağlantısı başarıyla doğrulandı & 230 Lead/Contact senkronize edildi! ☁️⚡");
    }
  };

  function renderAdminSettings() {
    if (companyColorPicker) companyColorPicker.value = adminSettings.brandColor || '#00A86B';
    if (colorHexInput) colorHexInput.value = (adminSettings.brandColor || '#00A86B').toUpperCase();
    if (colorHexText) colorHexText.textContent = (adminSettings.brandColor || '#00A86B').toUpperCase();

    presetColorDots.forEach(d => {
      if (d.getAttribute('data-color') === adminSettings.brandColor) {
        d.classList.add('active');
      } else {
        d.classList.remove('active');
      }
    });

    if (adminSettings.themeMode === 'dark') {
      if (radioThemeDark) radioThemeDark.checked = true;
    } else {
      if (radioThemeLight) radioThemeLight.checked = true;
    }

    if (adminSettings.staffFeatures) {
      if (settingStaffHubspot) settingStaffHubspot.checked = !!adminSettings.staffFeatures.hubspot;
      if (settingStaffVoiceNote) settingStaffVoiceNote.checked = !!adminSettings.staffFeatures.voiceNotes;
      if (settingStaffProducts) settingStaffProducts.checked = !!adminSettings.staffFeatures.products;
      if (settingStaffSocial) settingStaffSocial.checked = !!adminSettings.staffFeatures.socialLinks;
      if (settingStaffReviews) settingStaffReviews.checked = !!adminSettings.staffFeatures.reviews;
      if (settingStaffVCard) settingStaffVCard.checked = !!adminSettings.staffFeatures.vcard;
    }

    // Populate Integrations
    const integ = adminSettings.integrations || defaultAdminSettings.integrations;
    if (integ) {
      if (integ.google) {
        if (document.getElementById('integGoogleCalendarSync')) document.getElementById('integGoogleCalendarSync').checked = !!integ.google.calendarSync;
        if (document.getElementById('integGoogleMeetAuto')) document.getElementById('integGoogleMeetAuto').checked = !!integ.google.meetAuto;
        if (document.getElementById('integGoogleReviewUrl')) document.getElementById('integGoogleReviewUrl').value = integ.google.reviewUrl || '';
        if (document.getElementById('integGoogleClientId')) document.getElementById('integGoogleClientId').value = integ.google.clientId || '';
      }
      if (integ.zoom) {
        if (document.getElementById('integZoomAutoLink')) document.getElementById('integZoomAutoLink').checked = !!integ.zoom.autoLink;
        if (document.getElementById('integZoomWaitingRoom')) document.getElementById('integZoomWaitingRoom').checked = !!integ.zoom.waitingRoom;
        if (document.getElementById('integZoomAccountId')) document.getElementById('integZoomAccountId').value = integ.zoom.accountId || '';
        if (document.getElementById('integZoomClientId')) document.getElementById('integZoomClientId').value = integ.zoom.clientId || '';
        if (document.getElementById('integZoomClientSecret')) document.getElementById('integZoomClientSecret').value = integ.zoom.clientSecret || '';
      }
      if (integ.hubspot) {
        if (document.getElementById('integHubspotAutoSyncContacts')) document.getElementById('integHubspotAutoSyncContacts').checked = !!integ.hubspot.autoSyncContacts;
        if (document.getElementById('integHubspotAutoSyncVoice')) document.getElementById('integHubspotAutoSyncVoice').checked = !!integ.hubspot.autoSyncVoice;
        if (document.getElementById('integHubspotPortalId')) document.getElementById('integHubspotPortalId').value = integ.hubspot.portalId || '';
        if (document.getElementById('integHubspotStage')) document.getElementById('integHubspotStage').value = integ.hubspot.stage || 'lead';
        if (document.getElementById('integHubspotApiKey')) document.getElementById('integHubspotApiKey').value = integ.hubspot.apiKey || '';
      }
      if (integ.salesforce) {
        if (document.getElementById('integSalesforceAutoSyncContacts')) document.getElementById('integSalesforceAutoSyncContacts').checked = !!integ.salesforce.autoSyncContacts;
        if (document.getElementById('integSalesforceAutoSyncVoice')) document.getElementById('integSalesforceAutoSyncVoice').checked = !!integ.salesforce.autoSyncVoice;
        if (document.getElementById('integSalesforceAutoOpp')) document.getElementById('integSalesforceAutoOpp').checked = !!integ.salesforce.autoOpp;
        if (document.getElementById('integSalesforceInstanceUrl')) document.getElementById('integSalesforceInstanceUrl').value = integ.salesforce.instanceUrl || '';
        if (document.getElementById('integSalesforceLeadStatus')) document.getElementById('integSalesforceLeadStatus').value = integ.salesforce.leadStatus || 'Open - Not Contacted';
        if (document.getElementById('integSalesforceConsumerKey')) document.getElementById('integSalesforceConsumerKey').value = integ.salesforce.consumerKey || '';
        if (document.getElementById('integSalesforceConsumerSecret')) document.getElementById('integSalesforceConsumerSecret').value = integ.salesforce.consumerSecret || '';
      }
    }
  }

  // 6. COMPANY & MANAGER PROFILE + CORPORATE SOCIAL & PRODUCTS VITRIN
  const adminCompanyProfileForm = document.getElementById('adminCompanyProfileForm');
  const adminSocialForm = document.getElementById('adminSocialForm');
  const btnAdminSaveAllProfile = document.getElementById('btnAdminSaveAllProfile');
  const btnAdminAddProduct = document.getElementById('btnAdminAddProduct');
  const adminProductModal = document.getElementById('adminProductModal');
  const adminProductForm = document.getElementById('adminProductForm');

  function saveAllCompanyProfileData() {
    if (!adminSettings.companyProfile) {
      adminSettings.companyProfile = { ...defaultAdminSettings.companyProfile };
    }

    const compName = document.getElementById('adminCompName') ? document.getElementById('adminCompName').value.trim() : adminSettings.companyProfile.name;
    const compSector = document.getElementById('adminCompSector') ? document.getElementById('adminCompSector').value.trim() : adminSettings.companyProfile.sector;
    const compWebsite = document.getElementById('adminCompWebsite') ? document.getElementById('adminCompWebsite').value.trim() : (adminSettings.companyProfile.website || 'https://vedubox.com');
    const managerName = document.getElementById('adminManagerName') ? document.getElementById('adminManagerName').value.trim() : adminSettings.companyProfile.managerName;
    const managerTitle = document.getElementById('adminManagerTitle') ? document.getElementById('adminManagerTitle').value.trim() : adminSettings.companyProfile.managerTitle;
    const compEmail = document.getElementById('adminCompEmail') ? document.getElementById('adminCompEmail').value.trim() : adminSettings.companyProfile.email;
    const compPhone = document.getElementById('adminCompPhone') ? document.getElementById('adminCompPhone').value.trim() : adminSettings.companyProfile.phone;
    const compPhone2 = document.getElementById('adminCompPhone2') ? document.getElementById('adminCompPhone2').value.trim() : (adminSettings.companyProfile.phone2 || '');
    const compAddress = document.getElementById('adminCompAddress') ? document.getElementById('adminCompAddress').value.trim() : adminSettings.companyProfile.address;

    const socialLinks = {
      whatsapp: document.getElementById('adminSocialWhatsapp') ? document.getElementById('adminSocialWhatsapp').value.trim() : '905362556424',
      linkedin: document.getElementById('adminSocialLinkedin') ? document.getElementById('adminSocialLinkedin').value.trim() : 'muhiddinoktem',
      instagram: document.getElementById('adminSocialInstagram') ? document.getElementById('adminSocialInstagram').value.trim() : 'muhiddinoktem',
      twitter: document.getElementById('adminSocialTwitter') ? document.getElementById('adminSocialTwitter').value.trim() : 'muhiddinoktem',
      telegram: document.getElementById('adminSocialTelegram') ? document.getElementById('adminSocialTelegram').value.trim() : 'muhiddinoktem',
      facebook: document.getElementById('adminSocialFacebook') ? document.getElementById('adminSocialFacebook').value.trim() : 'muhiddinoktem',
      youtube: document.getElementById('adminSocialYoutube') ? document.getElementById('adminSocialYoutube').value.trim() : 'vedubox'
    };

    adminSettings.companyProfile = {
      ...adminSettings.companyProfile,
      name: compName,
      sector: compSector,
      website: compWebsite,
      managerName: managerName,
      managerTitle: managerTitle,
      email: compEmail,
      phone: compPhone,
      phone2: compPhone2,
      address: compAddress,
      socialLinks: socialLinks,
      products: adminSettings.companyProfile.products || defaultAdminSettings.companyProfile.products
    };

    // Synchronize to staffProfile (Digital Business Card)
    staffProfile.company = compName;
    staffProfile.website = compWebsite;
    staffProfile.address = compAddress;
    if (staffProfile.fullName === managerName || !staffProfile.fullName) {
      staffProfile.fullName = managerName;
      staffProfile.title = managerTitle;
      staffProfile.email = compEmail;
      staffProfile.phone = compPhone;
      staffProfile.phoneClean = compPhone.replace(/[^\d+]/g, '');
    }

    try {
      localStorage.setItem('monacard_staff_profile', JSON.stringify(staffProfile));
    } catch (e) {
      console.warn(e);
    }

    saveAdminSettingsToStorage();
    applyProfileToUI();
    renderCardProducts();
    renderAdminDashboard();
    showToast("Firma kimlik bilgileri, sosyal medya hesapları ve ürün vitrini kaydedildi ve tüm personellere aktarıldı! 🏢✨");
  }

  if (adminCompanyProfileForm) {
    adminCompanyProfileForm.addEventListener('submit', (e) => {
      e.preventDefault();
      saveAllCompanyProfileData();
    });
  }

  if (btnAdminSaveAllProfile) {
    btnAdminSaveAllProfile.addEventListener('click', () => {
      saveAllCompanyProfileData();
    });
  }

  // Render Admin Profile Form & Products
  function renderAdminProfile() {
    const prof = adminSettings.companyProfile || defaultAdminSettings.companyProfile;
    if (document.getElementById('adminCompName')) document.getElementById('adminCompName').value = prof.name || '';
    if (document.getElementById('adminCompSector')) document.getElementById('adminCompSector').value = prof.sector || '';
    if (document.getElementById('adminCompWebsite')) document.getElementById('adminCompWebsite').value = prof.website || 'https://vedubox.com';
    if (document.getElementById('adminManagerName')) document.getElementById('adminManagerName').value = prof.managerName || '';
    if (document.getElementById('adminManagerTitle')) document.getElementById('adminManagerTitle').value = prof.managerTitle || '';
    if (document.getElementById('adminCompEmail')) document.getElementById('adminCompEmail').value = prof.email || '';
    if (document.getElementById('adminCompPhone')) document.getElementById('adminCompPhone').value = prof.phone || '';
    if (document.getElementById('adminCompPhone2')) document.getElementById('adminCompPhone2').value = prof.phone2 || '';
    if (document.getElementById('adminCompAddress')) document.getElementById('adminCompAddress').value = prof.address || '';

    // Social accounts
    const soc = prof.socialLinks || (defaultAdminSettings.companyProfile && defaultAdminSettings.companyProfile.socialLinks) || {};
    if (document.getElementById('adminSocialWhatsapp')) document.getElementById('adminSocialWhatsapp').value = soc.whatsapp || '';
    if (document.getElementById('adminSocialLinkedin')) document.getElementById('adminSocialLinkedin').value = soc.linkedin || '';
    if (document.getElementById('adminSocialInstagram')) document.getElementById('adminSocialInstagram').value = soc.instagram || '';
    if (document.getElementById('adminSocialTwitter')) document.getElementById('adminSocialTwitter').value = soc.twitter || '';
    if (document.getElementById('adminSocialTelegram')) document.getElementById('adminSocialTelegram').value = soc.telegram || '';
    if (document.getElementById('adminSocialFacebook')) document.getElementById('adminSocialFacebook').value = soc.facebook || '';
    if (document.getElementById('adminSocialYoutube')) document.getElementById('adminSocialYoutube').value = soc.youtube || '';

    renderAdminProductsList();
  }

  // Render Products list in Admin Settings
  function renderAdminProductsList() {
    const container = document.getElementById('adminProductsListContainer');
    if (!container) return;

    const products = (adminSettings.companyProfile && adminSettings.companyProfile.products) 
      ? adminSettings.companyProfile.products 
      : (defaultAdminSettings.companyProfile.products || []);

    if (products.length === 0) {
      container.innerHTML = `
        <div style="grid-column: 1 / -1; text-align: center; padding: 32px 16px; background: var(--admin-hover-bg, #F8FAFC); border: 1.5px dashed var(--admin-card-border, #E2E8F0); border-radius: 16px;">
          <div style="font-size: 28px; margin-bottom: 6px;">📦</div>
          <h4 style="font-size: 14px; font-weight: 700; color: var(--admin-text-main, #0F172A);">Henüz Ürün veya Hizmet Eklenmedi</h4>
          <p style="font-size: 12.5px; color: var(--admin-text-sub, #64748B); margin-top: 4px;">Şirketinizin ürün ve markalarını ekleyerek tüm personellerin kartvizitinde yayınlayın.</p>
        </div>
      `;
      return;
    }

    container.innerHTML = products.map(p => {
      const isImg = p.logo && (p.logo.includes('.') || p.logo.startsWith('http'));
      return `
        <div class="admin-product-item-card">
          <div class="admin-prod-logo">
            ${isImg 
              ? `<img src="${p.logo}" alt="${p.name} Logo" onerror="this.parentElement.innerHTML='<span style=\\'font-weight:800;font-size:16px;color:var(--admin-primary,#2563EB);\\'>${(p.name || 'M')[0]}</span>'">` 
              : `<span style="font-weight:800;font-size:16px;color:var(--admin-primary,#2563EB);">${(p.name || 'M').substring(0, 2).toUpperCase()}</span>`}
          </div>
          <div class="admin-prod-info">
            <h4 class="admin-prod-name">${p.name}</h4>
            <p class="admin-prod-subtitle">${p.subtitle || ''}</p>
            <a href="${p.url || '#'}" target="_blank" rel="noopener noreferrer" class="admin-prod-link">
              <span>${(p.url || '').replace(/^https?:\/\//, '') || 'Bağlantı Aç'}</span>
              <span>↗</span>
            </a>
          </div>
          <div class="admin-prod-actions">
            <button type="button" class="admin-prod-btn" onclick="window.openEditProductModal('${p.id}')" title="Ürünü Düzenle">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </button>
            <button type="button" class="admin-prod-btn delete" onclick="window.deleteAdminProduct('${p.id}')" title="Ürünü Sil">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </button>
          </div>
        </div>
      `;
    }).join('');
  }

  // Open Product Modal (Add Mode)
  if (btnAdminAddProduct) {
    btnAdminAddProduct.addEventListener('click', () => {
      if (!adminProductModal || !adminProductForm) return;
      document.getElementById('adminProductModalTitle').textContent = "Yeni Ürün / Hizmet Ekle";
      document.getElementById('editProductId').value = "";
      adminProductForm.reset();
      openModal(adminProductModal);
    });
  }

  // Open Product Modal (Edit Mode)
  window.openEditProductModal = function(productId) {
    if (!adminProductModal || !adminProductForm) return;
    const products = (adminSettings.companyProfile && adminSettings.companyProfile.products) ? adminSettings.companyProfile.products : [];
    const prod = products.find(p => p.id === productId);
    if (!prod) return;

    document.getElementById('adminProductModalTitle').textContent = "Ürün / Hizmeti Düzenle";
    document.getElementById('editProductId').value = prod.id;
    document.getElementById('prodInputName').value = prod.name || '';
    document.getElementById('prodInputSubtitle').value = prod.subtitle || '';
    document.getElementById('prodInputUrl').value = prod.url || '';
    document.getElementById('prodInputLogo').value = prod.logo || '';

    openModal(adminProductModal);
  };

  // Delete Product
  window.deleteAdminProduct = function(productId) {
    if (!adminSettings.companyProfile) return;
    const products = adminSettings.companyProfile.products || [];
    const prod = products.find(p => p.id === productId);
    const prodName = prod ? prod.name : 'Bu ürün';

    if (confirm(`"${prodName}" ürününü şirket vitrininden silmek istediğinize emin misiniz?`)) {
      adminSettings.companyProfile.products = products.filter(p => p.id !== productId);
      saveAdminSettingsToStorage();
      renderAdminProductsList();
      renderCardProducts();
      showToast(`"${prodName}" vitrinden kaldırıldı. 🗑️`);
    }
  };

  // Product Form Submit Handler
  if (adminProductForm) {
    adminProductForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const editId = document.getElementById('editProductId').value.trim();
      const name = document.getElementById('prodInputName').value.trim();
      const subtitle = document.getElementById('prodInputSubtitle').value.trim();
      const url = document.getElementById('prodInputUrl').value.trim();
      const logo = document.getElementById('prodInputLogo').value.trim() || 'vedubox.png';

      if (!adminSettings.companyProfile) {
        adminSettings.companyProfile = { ...defaultAdminSettings.companyProfile };
      }
      if (!adminSettings.companyProfile.products) {
        adminSettings.companyProfile.products = [...defaultAdminSettings.companyProfile.products];
      }

      if (editId) {
        const idx = adminSettings.companyProfile.products.findIndex(p => p.id === editId);
        if (idx !== -1) {
          adminSettings.companyProfile.products[idx] = { id: editId, name, subtitle, url, logo };
        }
      } else {
        const newId = 'prod-' + Date.now();
        adminSettings.companyProfile.products.push({ id: newId, name, subtitle, url, logo });
      }

      saveAdminSettingsToStorage();
      renderAdminProductsList();
      renderCardProducts();
      closeModal(adminProductModal);
      showToast(`"${name}" ürünü başarıyla kaydedildi ve tüm dijital kartvizitlere aktarıldı! 🚀✨`);
    });
  }

  // =========================================================
  // SÜPER ADMİN (SAAS SAHİBİ) VERİ MODELLERİ & KONTROLCÜSÜ
  // =========================================================

  // 1. Süper Admin Kurumsal Firma Listesi
  const defaultSuperAdminCompanies = [
    {
      id: "comp-vedubox",
      name: "Vedubox Bilişim & Eğitim Teknolojileri",
      sector: "Eğitim Teknolojileri & SaaS",
      managerName: "Muhiddin Öktem",
      email: "muhiddinoktem@vedubox.com",
      phone: "+90 536 255 64 24",
      planId: "tier-41-50",
      planName: "41-50 Kullanıcı",
      employeeQuota: 50,
      activeEmployees: 12,
      billingCycle: "yearly",
      pricePerSeat: 139,
      mrr: 1668,
      status: "active",
      startDate: "2026-01-15",
      expiryDate: "2027-01-15",
      logo: "vedubox.png"
    },
    {
      id: "comp-etgigrup",
      name: "Etgi Grup Bilişim Ltd. Şti.",
      sector: "Bilişim & Yazılım",
      managerName: "Ahmet Erdem",
      email: "ahmet@etgigrup.com",
      phone: "+90 532 900 11 22",
      planId: "tier-1-10",
      planName: "1-10 Kullanıcı",
      employeeQuota: 10,
      activeEmployees: 6,
      billingCycle: "yearly",
      pricePerSeat: 199,
      mrr: 1194,
      status: "active",
      startDate: "2026-03-01",
      expiryDate: "2027-03-01",
      logo: "etgigrup.png"
    },
    {
      id: "comp-acme",
      name: "Acme Holding A.Ş.",
      sector: "Sanayi & Üretim",
      managerName: "Banu Yılmaz",
      email: "banu.yilmaz@acme.com.tr",
      phone: "+90 532 111 22 33",
      planId: "tier-100plus",
      planName: "100+ Kullanıcı",
      employeeQuota: 150,
      activeEmployees: 85,
      billingCycle: "yearly",
      pricePerSeat: 99,
      mrr: 8415,
      status: "active",
      startDate: "2026-02-10",
      expiryDate: "2027-02-10",
      logo: ""
    },
    {
      id: "comp-tekno",
      name: "TeknoDijital Çözümler A.Ş.",
      sector: "Yazılım & Donanım",
      managerName: "Barış Kaya",
      email: "baris@teknodijital.com",
      phone: "+90 544 333 44 55",
      planId: "tier-1-10",
      planName: "1-10 Kullanıcı (Demo)",
      employeeQuota: 10,
      activeEmployees: 8,
      billingCycle: "monthly",
      pricePerSeat: 199,
      mrr: 0,
      status: "demo",
      startDate: "2026-09-12",
      expiryDate: "2026-09-26",
      logo: ""
    },
    {
      id: "comp-global",
      name: "Global Lojistik A.Ş.",
      sector: "Taşımacılık & Depolama",
      managerName: "Murat Demir",
      email: "murat@globallojistik.com",
      phone: "+90 533 888 77 66",
      planId: "tier-100plus",
      planName: "100+ Kullanıcı",
      employeeQuota: 200,
      activeEmployees: 140,
      billingCycle: "yearly",
      pricePerSeat: 99,
      mrr: 13860,
      status: "active",
      startDate: "2025-11-20",
      expiryDate: "2026-11-20",
      logo: ""
    },
    {
      id: "comp-medya",
      name: "MedyaPlus Kreatif Reklam Ajansı",
      sector: "Pazarlama & Reklam",
      managerName: "Ceren Aktaş",
      email: "ceren@medyaplus.com",
      phone: "+90 535 666 55 44",
      planId: "tier-21-30",
      planName: "21-30 Kullanıcı",
      employeeQuota: 25,
      activeEmployees: 15,
      billingCycle: "monthly",
      pricePerSeat: 159,
      mrr: 2385,
      status: "expired",
      startDate: "2025-08-10",
      expiryDate: "2026-08-10",
      logo: ""
    }
  ];

  let superAdminCompanies = [...defaultSuperAdminCompanies];
  try {
    const savedComps = localStorage.getItem('monacard_super_companies');
    if (savedComps) {
      superAdminCompanies = JSON.parse(savedComps);
    }
  } catch (e) {
    console.warn('Super admin companies storage error', e);
  }

  function saveSuperCompaniesToStorage() {
    try {
      localStorage.setItem('monacard_super_companies', JSON.stringify(superAdminCompanies));
    } catch (e) {}
  }

  // 2. Demo Talepleri Havuzu
  const defaultDemoRequests = [
    {
      id: "demo-req-1",
      companyName: "Atlas Finans & Faktoring A.Ş.",
      sector: "Finans & Bankacılık",
      contactName: "Banu Yılmaz",
      email: "banu.yilmaz@atlasfinans.com",
      phone: "+90 532 111 22 33",
      employeeCount: 45,
      note: "Satış ve şube personelimiz için NFC dijital kartvizit ve merkezi müşteri CRM havuzu özelliklerini test etmek istiyoruz.",
      status: "pending",
      requestDate: "2026-09-20",
      trialDays: 14
    },
    {
      id: "demo-req-2",
      companyName: "Nova Lojistik Global",
      sector: "Lojistik & Tedarik",
      contactName: "Kemal Ersoy",
      email: "kemal@novalojistik.com",
      phone: "+90 533 444 55 66",
      employeeCount: 30,
      note: "Saha operasyon ekibimizin müşteri kartvizitlerini okutup sesli notlarla HubSpot'a aktarma yeteneğini deneyeceğiz.",
      status: "pending",
      requestDate: "2026-09-20",
      trialDays: 14
    },
    {
      id: "demo-req-3",
      companyName: "Zenith Sağlık & Estetik Grubu",
      sector: "Sağlık & Medikal",
      contactName: "Dr. Arzu Çelik",
      email: "arzu.celik@zenith.com",
      phone: "+90 535 777 88 99",
      employeeCount: 80,
      note: "Uzman doktor ve klinik temsilcilerimizin randevu takvimi ile Google Yorum yönlendirmesini test etmek istiyoruz.",
      status: "pending",
      requestDate: "2026-09-19",
      trialDays: 14
    },
    {
      id: "demo-req-4",
      companyName: "Prime Mimarlık & Tasarım",
      sector: "Mimarlık & İnşaat",
      contactName: "Mert Yıldız",
      email: "mert@primemimarlik.com",
      phone: "+90 530 222 33 44",
      employeeCount: 15,
      note: "Yeni projelerimizin lansmanında kurumsal kartvizitlerimizi akıllı telefonlara aktarmak istiyoruz.",
      status: "pending",
      requestDate: "2026-09-18",
      trialDays: 14
    },
    {
      id: "demo-req-5",
      companyName: "Bosphorus Gayrimenkul Yatırım",
      sector: "Gayrimenkul & Emlak",
      contactName: "Gamze Aksoy",
      email: "gamze@bosphorusgyd.com",
      phone: "+90 536 999 00 11",
      employeeCount: 25,
      note: "Portföy danışmanlarımız için tek dokunuşla rehbere kaydetme ve toplantı linki oluşturma özellikleri önceliğimiz.",
      status: "pending",
      requestDate: "2026-09-17",
      trialDays: 14
    },
    {
      id: "demo-req-6",
      companyName: "TeknoPlus Bilişim Hizmetleri",
      sector: "Bilişim",
      contactName: "Caner Aydın",
      email: "caner@teknoplus.com",
      phone: "+90 538 555 66 77",
      employeeCount: 12,
      note: "Test tamamlandı, yıllık aboneliğe geçiş aşamasındayız.",
      status: "approved",
      requestDate: "2026-09-10",
      trialDays: 14
    },
    {
      id: "demo-req-7",
      companyName: "Delta Danışmanlık Ltd.",
      sector: "Yönetim Danışmanlığı",
      contactName: "Orhan Şen",
      email: "orhan@delta.com",
      phone: "+90 539 000 11 22",
      employeeCount: 4,
      note: "Kriterlere uygun bulunmadı.",
      status: "rejected",
      requestDate: "2026-09-08",
      trialDays: 14
    }
  ];

  let superAdminDemoRequests = [...defaultDemoRequests];
  try {
    const savedDemos = localStorage.getItem('monacard_super_demos');
    if (savedDemos) {
      superAdminDemoRequests = JSON.parse(savedDemos);
    }
  } catch (e) {
    console.warn('Super admin demos storage error', e);
  }

  function saveSuperDemosToStorage() {
    try {
      localStorage.setItem('monacard_super_demos', JSON.stringify(superAdminDemoRequests));
    } catch (e) {}
  }

  // 3. Kullanıcı Sayısına Göre Dilim Fiyatlandırması (1-10, 11-20, 21-30, 31-40, 41-50, 50-100, 100+) - Sadece Yıllık Satış
  const defaultUserTiers = [
    { id: "tier-1-10", label: "1-10 Kullanıcı", min: 1, max: 10, yearlyPrice: 1890, note: "Küçük ekipler & yeni başlayanlar için temel lisans" },
    { id: "tier-11-20", label: "11-20 Kullanıcı", min: 11, max: 20, yearlyPrice: 1690, note: "Büyüyen saha ve satış departmanları" },
    { id: "tier-21-30", label: "21-30 Kullanıcı", min: 21, max: 30, yearlyPrice: 1490, note: "Orta ölçekli şirketler için popüler dilim" },
    { id: "tier-31-40", label: "31-40 Kullanıcı", min: 31, max: 40, yearlyPrice: 1390, note: "Genişleyen kurumsal operasyonlar" },
    { id: "tier-41-50", label: "41-50 Kullanıcı", min: 41, max: 50, yearlyPrice: 1290, note: "Çoklu şube ve merkezi CRM kullanan ekipler" },
    { id: "tier-50-100", label: "50-100 Kullanıcı", min: 51, max: 100, yearlyPrice: 990, note: "Yüksek hacimli kurumsal şirketler" },
    { id: "tier-100plus", label: "100+ Kullanıcı", min: 101, max: 9999, yearlyPrice: 0, isCustomContact: true, note: "Özel Teklif / İrtibata Geçin (Kurumsal VIP)" }
  ];

  let superAdminUserTiers = [...defaultUserTiers];
  try {
    const savedTiers = localStorage.getItem('monacard_super_tiers');
    if (savedTiers) {
      const parsed = JSON.parse(savedTiers);
      superAdminUserTiers = parsed.map(t => {
        if (!t.yearlyPrice) {
          t.yearlyPrice = t.monthlyPrice ? Math.round(t.monthlyPrice * 10) : 1490;
        }
        return t;
      });
    }
  } catch (e) {
    console.warn('Super admin tiers storage error', e);
  }

  function saveSuperPricingToStorage() {
    try {
      localStorage.setItem('monacard_super_tiers', JSON.stringify(superAdminUserTiers));
    } catch (e) {}
  }

  function getUserTierForSeats(count) {
    if (count > 100) return superAdminUserTiers.find(t => t.id === 'tier-100plus') || superAdminUserTiers[superAdminUserTiers.length - 1];
    return superAdminUserTiers.find(t => count >= t.min && count <= t.max) || superAdminUserTiers[0];
  }

  // 4. SaaS Platform Genel Ayarları
  const defaultSuperSettings = {
    platformName: "MonaCard Multi-Tenant SaaS",
    supportEmail: "support@monacard.com",
    ownerName: "Muhiddin Öktem",
    currency: "TRY",
    defaultTrialDays: 14,
    defaultTrialSeats: 10,
    autoDemoNotification: true
  };

  let superAdminSettings = { ...defaultSuperSettings };
  try {
    const savedPlatform = localStorage.getItem('monacard_super_settings');
    if (savedPlatform) {
      superAdminSettings = { ...defaultSuperSettings, ...JSON.parse(savedPlatform) };
    }
  } catch (e) {}

  function saveSuperSettingsToStorage() {
    try {
      localStorage.setItem('monacard_super_settings', JSON.stringify(superAdminSettings));
    } catch (e) {}
  }

  // Super Admin Navigation Controller
  const superViews = {
    viewSuperDashboard: document.getElementById('viewSuperDashboard'),
    viewSuperCompanies: document.getElementById('viewSuperCompanies'),
    viewSuperDemoRequests: document.getElementById('viewSuperDemoRequests'),
    viewSuperPricing: document.getElementById('viewSuperPricing'),
    viewSuperSettings: document.getElementById('viewSuperSettings')
  };

  const superNavItems = document.querySelectorAll('.super-nav-item');

  function navigateToSuperView(viewId) {
    if (!superViews[viewId]) return;

    Object.values(superViews).forEach(v => {
      if (v) v.classList.remove('active');
    });
    superViews[viewId].classList.add('active');

    superNavItems.forEach(item => {
      const itemTarget = item.getAttribute('data-super-view');
      if (itemTarget === viewId) {
        item.classList.add('active');
      } else {
        item.classList.remove('active');
      }
    });

    if (viewId === 'viewSuperDashboard') renderSuperDashboard();
    if (viewId === 'viewSuperCompanies') renderSuperCompaniesTable();
    if (viewId === 'viewSuperDemoRequests') renderSuperDemoRequestsTable();
    if (viewId === 'viewSuperPricing') renderSuperPricingPlans();
    if (viewId === 'viewSuperSettings') renderSuperSettings();

    // Close mobile drawer on navigation
    toggleSuperMobileSidebar(false);
  }

  // Super Admin Mobile Drawer Toggles
  const superSidebar = document.getElementById('superSidebar');
  const btnToggleMobileSuperSidebar = document.getElementById('btnToggleMobileSuperSidebar');
  const superMobileBackdrop = document.getElementById('superMobileBackdrop');

  function toggleSuperMobileSidebar(open) {
    if (!superSidebar) return;
    const shouldOpen = open !== undefined ? open : !superSidebar.classList.contains('mobile-open');
    if (shouldOpen) {
      superSidebar.classList.add('mobile-open');
      if (superMobileBackdrop) superMobileBackdrop.classList.add('active');
    } else {
      superSidebar.classList.remove('mobile-open');
      if (superMobileBackdrop) superMobileBackdrop.classList.remove('active');
    }
  }

  if (btnToggleMobileSuperSidebar) {
    btnToggleMobileSuperSidebar.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleSuperMobileSidebar();
    });
  }
  if (superMobileBackdrop) {
    superMobileBackdrop.addEventListener('click', () => toggleSuperMobileSidebar(false));
  }

  superNavItems.forEach(item => {
    item.addEventListener('click', () => {
      const viewId = item.getAttribute('data-super-view');
      navigateToSuperView(viewId);
    });
  });

  // Topbar Dark Mode Toggle in Super Admin
  const btnSuperThemeToggle = document.getElementById('btnSuperThemeToggle');
  if (btnSuperThemeToggle) {
    btnSuperThemeToggle.addEventListener('click', () => {
      adminSettings.themeMode = adminSettings.themeMode === 'dark' ? 'light' : 'dark';
      saveAdminSettingsToStorage();
      applyCompanySettingsToApp();
      setRole('superadmin', true);
      showToast(adminSettings.themeMode === 'dark' ? '🌙 SaaS Karanlık Mod Aktif' : '☀️ SaaS Aydınlık Mod Aktif');
    });
  }

  // Dashboard quick buttons
  const btnDashNewCompany = document.getElementById('btnDashNewCompany');
  if (btnDashNewCompany) {
    btnDashNewCompany.addEventListener('click', () => {
      openSuperCompanyModalForAdd();
    });
  }

  const btnDashViewDemos = document.getElementById('btnDashViewDemos');
  const btnDashSeeAllDemos = document.getElementById('btnDashSeeAllDemos');
  if (btnDashViewDemos) {
    btnDashViewDemos.addEventListener('click', () => {
      navigateToSuperView('viewSuperDemoRequests');
    });
  }
  if (btnDashSeeAllDemos) {
    btnDashSeeAllDemos.addEventListener('click', () => {
      navigateToSuperView('viewSuperDemoRequests');
    });
  }

  // Topbar Global Search for Super Admin
  const superGlobalSearch = document.getElementById('superGlobalSearch');
  if (superGlobalSearch) {
    superGlobalSearch.addEventListener('input', (e) => {
      const q = e.target.value.trim().toLowerCase();
      if (!q) return;

      if (superViews.viewSuperCompanies && superViews.viewSuperCompanies.classList.contains('active')) {
        const cSearch = document.getElementById('superCompanySearch');
        if (cSearch) {
          cSearch.value = q;
          renderSuperCompaniesTable();
        }
      } else if (superViews.viewSuperDemoRequests && superViews.viewSuperDemoRequests.classList.contains('active')) {
        const dSearch = document.getElementById('superDemoSearch');
        if (dSearch) {
          dSearch.value = q;
          renderSuperDemoRequestsTable();
        }
      }
    });
  }

  // =========================================================
  // 1. DASHBOARD RENDERER (SaaS Executive Overview)
  // =========================================================
  function renderSuperDashboard() {
    const activeComps = superAdminCompanies.filter(c => c.status === 'active' || c.status === 'demo');
    const totalSeats = superAdminCompanies.reduce((acc, c) => acc + (c.activeEmployees || 0), 0);
    const pendingDemos = superAdminDemoRequests.filter(d => d.status === 'pending');
    const totalMrr = superAdminCompanies
      .filter(c => c.status === 'active')
      .reduce((acc, c) => acc + (c.mrr || (c.activeEmployees * c.pricePerSeat) || 0), 0);

    const superStatActiveCompanies = document.getElementById('superStatActiveCompanies');
    const superStatTotalSeats = document.getElementById('superStatTotalSeats');
    const superStatPendingDemos = document.getElementById('superStatPendingDemos');
    const superStatMRR = document.getElementById('superStatMRR');
    const dashPendingDemoCount = document.getElementById('dashPendingDemoCount');
    const superDemoBadgeDot = document.getElementById('superDemoBadgeDot');

    if (superStatActiveCompanies) superStatActiveCompanies.textContent = superAdminCompanies.length;
    if (superStatTotalSeats) superStatTotalSeats.textContent = totalSeats;
    if (superStatPendingDemos) superStatPendingDemos.textContent = pendingDemos.length;
    if (dashPendingDemoCount) dashPendingDemoCount.textContent = pendingDemos.length;
    const dashSeeAllCount = document.getElementById('dashSeeAllCount');
    if (dashSeeAllCount) dashSeeAllCount.textContent = pendingDemos.length;
    if (superDemoBadgeDot) {
      superDemoBadgeDot.textContent = pendingDemos.length;
      superDemoBadgeDot.style.display = pendingDemos.length > 0 ? 'flex' : 'none';
    }
    if (superStatMRR) {
      superStatMRR.textContent = `₺${totalMrr.toLocaleString('tr-TR')}`;
    }

    // Mini Demo Table in Dashboard
    const superDashDemoTableBody = document.getElementById('superDashDemoTableBody');
    if (superDashDemoTableBody) {
      const topPending = pendingDemos.slice(0, 5);
      if (topPending.length === 0) {
        superDashDemoTableBody.innerHTML = `
          <tr>
            <td colspan="5" style="text-align: center; padding: 24px; color: var(--super-text-sub);">
              🎉 Bekleyen demo talebi bulunmuyor. Tüm talepler işlendi!
            </td>
          </tr>
        `;
      } else {
        superDashDemoTableBody.innerHTML = topPending.map(d => {
          const matchedTier = getUserTierForSeats(d.employeeCount);
          return `
            <tr class="super-demo-row">
              <td class="super-demo-col-company">
                <div class="super-demo-col-header">
                  <span class="super-demo-mobile-tag">FİRMA & SEKTÖR</span>
                </div>
                <div class="font-bold text-slate-900">${d.companyName}</div>
                <div class="text-xs text-muted">${d.sector}</div>
              </td>
              <td class="super-demo-col-contact">
                <div class="super-demo-col-header">
                  <span class="super-demo-mobile-tag">YETKİLİ BİLGİSİ</span>
                </div>
                <div class="font-bold text-slate-800">${d.contactName}</div>
                <div class="text-xs text-muted font-mono">${d.email}</div>
                <div class="text-xs text-muted font-mono">${d.phone}</div>
              </td>
              <td class="super-demo-col-tier">
                <span class="super-demo-mobile-label">Çalışan Sayısı:</span>
                <span class="super-tier-badge">${matchedTier.label}</span>
              </td>
              <td class="super-demo-col-date">
                <span class="super-demo-mobile-label">Talep Tarihi:</span>
                <span class="text-xs text-slate-700 font-medium">${d.requestDate}</span>
              </td>
              <td class="super-demo-col-action text-right">
                <button class="super-act-btn square demo-approve" onclick="window.openSuperDemoApproveModal('${d.id}')" title="Demoyu Aç">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  <span class="super-demo-mobile-btn-text">Demoyu Aç</span>
                </button>
              </td>
            </tr>
          `;
        }).join('');
      }
    }

    // Activity Feed
    const superActivityFeed = document.getElementById('superActivityFeed');
    if (superActivityFeed) {
      const activities = [
        { icon: "⚡", text: "<strong>Atlas Finans</strong> 45 çalışan için kurumsal demo aktivasyonu talep etti.", time: "10 dk önce" },
        { icon: "🟢", text: "<strong>Vedubox Bilişim</strong> yıllık lisansını 50 çalışan kotasıyla yeniledi.", time: "2 saat önce" },
        { icon: "👑", text: "<strong>Acme Holding</strong> Professional plandan Enterprise plana yükseltildi.", time: "Dün" },
        { icon: "📩", text: "<strong>Nova Lojistik</strong> 30 kişilik saha ekibi için demo başvurusunda bulundu.", time: "2 gün önce" }
      ];

      superActivityFeed.innerHTML = activities.map(a => `
        <div class="super-activity-item">
          <div class="super-activity-icon bg-indigo-soft">${a.icon}</div>
          <div class="super-activity-text">${a.text}</div>
          <div class="super-activity-time">${a.time}</div>
        </div>
      `).join('');
    }
  }

  // =========================================================
  // 2. FİRMA YÖNETİMİ & FİRMA LİSTESİ RENDERER
  // =========================================================
  const superCompaniesTableBody = document.getElementById('superCompaniesTableBody');
  const superCompanySearch = document.getElementById('superCompanySearch');
  const superCompanyStatusFilter = document.getElementById('superCompanyStatusFilter');
  const superCompanyPlanFilter = document.getElementById('superCompanyPlanFilter');
  const superCompaniesCountBadge = document.getElementById('superCompaniesCountBadge');
  const btnOpenAddCompanyModal = document.getElementById('btnOpenAddCompanyModal');

  if (superCompanySearch) superCompanySearch.addEventListener('input', renderSuperCompaniesTable);
  if (superCompanyStatusFilter) superCompanyStatusFilter.addEventListener('change', renderSuperCompaniesTable);
  if (superCompanyPlanFilter) superCompanyPlanFilter.addEventListener('change', renderSuperCompaniesTable);

  if (btnOpenAddCompanyModal) {
    btnOpenAddCompanyModal.addEventListener('click', () => {
      openSuperCompanyModalForAdd();
    });
  }

  function renderSuperCompaniesTable() {
    if (!superCompaniesTableBody) return;

    const isEn = currentLang === 'en';

    let list = [...superAdminCompanies];

    const q = superCompanySearch ? superCompanySearch.value.trim().toLowerCase() : '';
    if (q) {
      list = list.filter(c =>
        c.name.toLowerCase().includes(q) ||
        c.managerName.toLowerCase().includes(q) ||
        c.email.toLowerCase().includes(q) ||
        c.sector.toLowerCase().includes(q)
      );
    }

    const statusVal = superCompanyStatusFilter ? superCompanyStatusFilter.value : 'all';
    if (statusVal !== 'all') {
      list = list.filter(c => c.status === statusVal);
    }

    const planVal = superCompanyPlanFilter ? superCompanyPlanFilter.value : 'all';
    if (planVal !== 'all') {
      list = list.filter(c => c.planId === planVal);
    }

    if (superCompaniesCountBadge) {
      superCompaniesCountBadge.textContent = isEn 
        ? `Total ${list.length} Companies Listed` 
        : `Toplam ${list.length} Firma Listeleniyor`;
    }

    if (list.length === 0) {
      superCompaniesTableBody.innerHTML = `
        <tr>
          <td colspan="8" style="text-align: center; padding: 32px; color: var(--super-text-sub);">
            ${isEn ? 'No companies found matching search or filter criteria.' : 'Arama veya filtreleme kriterlerine uygun firma bulunamadı.'}
          </td>
        </tr>
      `;
      return;
    }

    superCompaniesTableBody.innerHTML = list.map(c => {
      const statusClass = c.status === 'active' ? 'active' : c.status === 'demo' ? 'demo' : c.status === 'expired' ? 'expired' : 'suspended';
      const statusLabel = c.status === 'active' 
        ? (isEn ? '🟢 Active License' : '🟢 Aktif Lisans') 
        : c.status === 'demo' 
        ? (isEn ? '⚡ 14-Day Demo' : '⚡ 14 Gün Demo') 
        : c.status === 'expired' 
        ? (isEn ? '⚠️ Expired' : '⚠️ Süresi Doldu') 
        : (isEn ? '⏸️ Suspended' : '⏸️ Donduruldu');
      
      const quotaPct = Math.min(100, Math.round(((c.activeEmployees || 0) / (c.employeeQuota || 1)) * 100));
      const mrrVal = c.mrr || (c.activeEmployees * c.pricePerSeat) || 0;

      const logoHtml = c.logo
        ? `<img src="${c.logo}" alt="${c.name}" class="super-comp-logo">`
        : `<div class="super-comp-logo-placeholder">${c.name.substring(0, 2).toUpperCase()}</div>`;

      return `
        <tr>
          <td>
            <div class="super-company-cell">
              ${logoHtml}
              <div>
                <div class="font-bold text-slate-900">${c.name}</div>
                <div class="text-xs text-muted">${c.sector}</div>
              </div>
            </div>
          </td>
          <td>
            <div class="font-bold text-slate-800">${c.managerName}</div>
            <div class="text-xs text-muted font-mono">${c.email}</div>
            <div class="text-xs text-muted">${c.phone}</div>
          </td>
          <td>
            <span class="plan-badge-pill">${c.planName || 'Professional'}</span>
          </td>
          <td>
            <div class="quota-progress-col">
              <div class="flex justify-between items-center text-xs font-bold text-slate-800">
                <span>${c.activeEmployees} / ${c.employeeQuota}</span>
                <span class="text-indigo-600">%${quotaPct}</span>
              </div>
              <div class="quota-progress-bg">
                <div class="quota-progress-fill" style="width: ${quotaPct}%; background: ${quotaPct >= 90 ? '#EF4444' : '#6366F1'};"></div>
              </div>
            </div>
          </td>
          <td>
            <div class="font-bold text-xs text-slate-800">${c.expiryDate}</div>
            <div class="text-xs text-muted">${c.billingCycle === 'yearly' ? (isEn ? 'Annual' : 'Yıllık Peşin') : (isEn ? 'Monthly' : 'Aylık')}</div>
          </td>
          <td>
            <strong class="text-indigo-600 font-bold">${c.status === 'demo' ? (isEn ? '₺0 (Demo)' : '₺0 (Demo)') : `₺${mrrVal.toLocaleString(isEn ? 'en-US' : 'tr-TR')}${isEn ? '/mo' : '/ay'}`}</strong>
          </td>
          <td>
            <span class="status-pill ${statusClass}">${statusLabel}</span>
          </td>
          <td class="text-right">
            <div class="super-actions-cell">
              <button class="super-act-btn impersonate" onclick="window.impersonateCompany('${c.id}')" title="${isEn ? 'Switch to this company admin portal' : 'Bu firmanın Yönetici Paneline Geçiş Yap'}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h6v6"></path><path d="M10 14L21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg>
                <span>${isEn ? 'Manage' : 'Yönet'}</span>
              </button>
              <button class="super-act-btn" onclick="window.openSuperCompanyModalForEdit('${c.id}')" title="${isEn ? 'Edit company info & quota' : 'Firma Bilgilerini & Kotayı Düzenle'}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <button class="super-act-btn" onclick="window.openSuperExtendModal('${c.id}')" title="${isEn ? 'Extend License Duration' : 'Lisans Süresini Uzat'}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              </button>
              <button class="super-act-btn ${c.status === 'suspended' ? '' : 'danger'}" onclick="window.toggleSuperCompanySuspend('${c.id}')" title="${c.status === 'suspended' ? (isEn ? 'Activate Company' : 'Firmayı Aktifleştir') : (isEn ? 'Suspend Company' : 'Firmayı Dondur')}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="10" y1="15" x2="10" y2="9"></line><line x1="14" y1="15" x2="14" y2="9"></line></svg>
              </button>
              <button class="super-act-btn danger" onclick="window.deleteSuperCompany('${c.id}')" title="Firmayı Sil">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // Impersonation: Switch to that company's executive manager panel
  window.impersonateCompany = function(companyId) {
    const comp = superAdminCompanies.find(c => c.id === companyId);
    if (!comp) return;

    if (!adminSettings.companyProfile) {
      adminSettings.companyProfile = { ...defaultAdminSettings.companyProfile };
    }
    adminSettings.companyProfile.name = comp.name;
    adminSettings.companyProfile.sector = comp.sector;
    adminSettings.companyProfile.managerName = comp.managerName;
    adminSettings.companyProfile.email = comp.email;
    adminSettings.companyProfile.phone = comp.phone;

    saveAdminSettingsToStorage();
    setRole('admin');
    showToast(`🏢 "${comp.name}" yöneticisi olarak sisteme giriş yapıldı!`);
  };

  // Company Modals: Add / Edit
  const superCompanyModal = document.getElementById('superCompanyModal');
  const superCompanyForm = document.getElementById('superCompanyForm');

  function openSuperCompanyModalForAdd(initialQuota = 25, initialPlan = 'tier-21-30') {
    if (!superCompanyModal || !superCompanyForm) return;
    document.getElementById('superCompModalTitle').textContent = "Yeni Kurumsal Firma Oluştur";
    document.getElementById('editSuperCompanyId').value = "";
    superCompanyForm.reset();

    document.getElementById('superInputCompQuota').value = initialQuota;
    document.getElementById('superInputCompPlan').value = initialPlan;
    
    // Set 1 year ahead expiry date default
    const nextYear = new Date();
    nextYear.setFullYear(nextYear.getFullYear() + 1);
    document.getElementById('superInputCompExpiry').value = nextYear.toISOString().split('T')[0];

    openModal(superCompanyModal);
  }

  window.openSuperCompanyModalForEdit = function(companyId) {
    const comp = superAdminCompanies.find(c => c.id === companyId);
    if (!comp || !superCompanyModal || !superCompanyForm) return;

    document.getElementById('superCompModalTitle').textContent = "Firma Bilgilerini & Kotayı Düzenle";
    document.getElementById('editSuperCompanyId').value = comp.id;
    document.getElementById('superInputCompName').value = comp.name;
    document.getElementById('superInputCompSector').value = comp.sector;
    document.getElementById('superInputCompManager').value = comp.managerName;
    document.getElementById('superInputCompEmail').value = comp.email;
    document.getElementById('superInputCompPhone').value = comp.phone;
    document.getElementById('superInputCompPlan').value = comp.planId || 'tier-21-30';
    document.getElementById('superInputCompQuota').value = comp.employeeQuota || 25;
    document.getElementById('superInputCompStatus').value = comp.status || 'active';
    document.getElementById('superInputCompBilling').value = comp.billingCycle || 'yearly';
    document.getElementById('superInputCompExpiry').value = comp.expiryDate || '2027-01-01';

    openModal(superCompanyModal);
  };

  if (superCompanyForm) {
    superCompanyForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const editId = document.getElementById('editSuperCompanyId').value;
      const name = document.getElementById('superInputCompName').value.trim();
      const sector = document.getElementById('superInputCompSector').value.trim();
      const managerName = document.getElementById('superInputCompManager').value.trim();
      const email = document.getElementById('superInputCompEmail').value.trim();
      const phone = document.getElementById('superInputCompPhone').value.trim();
      const planId = document.getElementById('superInputCompPlan').value;
      const employeeQuota = parseInt(document.getElementById('superInputCompQuota').value, 10) || 25;
      const status = document.getElementById('superInputCompStatus').value;
      const billingCycle = document.getElementById('superInputCompBilling').value;
      const expiryDate = document.getElementById('superInputCompExpiry').value;

      const tierObj = superAdminUserTiers.find(p => p.id === planId) || getUserTierForSeats(employeeQuota);
      const planName = tierObj ? tierObj.label : '21-30 Kullanıcı';
      const pricePerSeat = tierObj ? tierObj.monthlyPrice : 159;
      const mrr = status === 'demo' ? 0 : employeeQuota * pricePerSeat;

      if (editId) {
        const idx = superAdminCompanies.findIndex(c => c.id === editId);
        if (idx !== -1) {
          superAdminCompanies[idx] = {
            ...superAdminCompanies[idx],
            name,
            sector,
            managerName,
            email,
            phone,
            planId,
            planName,
            employeeQuota,
            status,
            billingCycle,
            expiryDate,
            pricePerSeat,
            mrr
          };
          showToast(`"${name}" firma bilgileri ve kotası güncellendi! ✅`);
        }
      } else {
        const newComp = {
          id: "comp-" + Date.now(),
          name,
          sector,
          managerName,
          email,
          phone,
          planId,
          planName,
          employeeQuota,
          activeEmployees: 1,
          billingCycle,
          pricePerSeat,
          mrr,
          status,
          startDate: new Date().toISOString().split('T')[0],
          expiryDate,
          logo: ""
        };
        superAdminCompanies.unshift(newComp);
        showToast(`🎉 "${name}" firması ve ${employeeQuota} kişilik çalışan kotası başarıyla oluşturuldu!`);
      }

      saveSuperCompaniesToStorage();
      renderSuperCompaniesTable();
      renderSuperDashboard();
      closeModal(superCompanyModal);
    });
  }

  // Extend License & Quota Modal
  const superExtendLicenseModal = document.getElementById('superExtendLicenseModal');
  const superExtendForm = document.getElementById('superExtendForm');

  window.openSuperExtendModal = function(companyId) {
    const comp = superAdminCompanies.find(c => c.id === companyId);
    if (!comp || !superExtendLicenseModal) return;

    document.getElementById('extendCompId').value = comp.id;
    document.getElementById('superExtendSubtitle').textContent = `${comp.name} için lisans uzatma ve kota belirleme`;
    
    // Set closest tier dropdown option
    const quotaSelect = document.getElementById('extendQuotaInput');
    if (quotaSelect) {
      const q = comp.employeeQuota || 10;
      if (q <= 10) quotaSelect.value = "10";
      else if (q <= 20) quotaSelect.value = "20";
      else if (q <= 30) quotaSelect.value = "30";
      else if (q <= 40) quotaSelect.value = "40";
      else if (q <= 50) quotaSelect.value = "50";
      else if (q <= 100) quotaSelect.value = "100";
      else quotaSelect.value = "150";
    }

    document.getElementById('extendExpiryInput').value = comp.expiryDate || '2027-01-01';
    document.getElementById('extendStatusSelect').value = comp.status || 'active';

    openModal(superExtendLicenseModal);
  };

  if (superExtendForm) {
    superExtendForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const compId = document.getElementById('extendCompId').value;
      const newQuota = parseInt(document.getElementById('extendQuotaInput').value, 10) || 10;
      const newExpiry = document.getElementById('extendExpiryInput').value;
      const newStatus = document.getElementById('extendStatusSelect').value;

      const idx = superAdminCompanies.findIndex(c => c.id === compId);
      if (idx !== -1) {
        const matchedTier = getUserTierForSeats(newQuota);
        superAdminCompanies[idx].employeeQuota = newQuota;
        superAdminCompanies[idx].planId = matchedTier.id;
        superAdminCompanies[idx].planName = matchedTier.label;
        superAdminCompanies[idx].pricePerSeat = matchedTier.monthlyPrice || 149;
        superAdminCompanies[idx].expiryDate = newExpiry;
        superAdminCompanies[idx].status = newStatus;
        superAdminCompanies[idx].mrr = newStatus === 'demo' ? 0 : superAdminCompanies[idx].activeEmployees * superAdminCompanies[idx].pricePerSeat;

        saveSuperCompaniesToStorage();
        renderSuperCompaniesTable();
        renderSuperDashboard();
        showToast(`${superAdminCompanies[idx].name} lisansı ${newExpiry} tarihine kadar uzatıldı! 🔄✨`);
      }
      closeModal(superExtendLicenseModal);
    });
  }

  // Toggle Suspend / Active Company
  window.toggleSuperCompanySuspend = function(companyId) {
    const comp = superAdminCompanies.find(c => c.id === companyId);
    if (!comp) return;

    if (comp.status === 'suspended') {
      comp.status = 'active';
      showToast(`"${comp.name}" hesabı yeniden aktifleştirildi! 🟢`);
    } else {
      comp.status = 'suspended';
      showToast(`"${comp.name}" hesabı geçici olarak donduruldu! ⏸️`);
    }

    saveSuperCompaniesToStorage();
    renderSuperCompaniesTable();
    renderSuperDashboard();
  };

  // Delete Company
  window.deleteSuperCompany = function(companyId) {
    const comp = superAdminCompanies.find(c => c.id === companyId);
    if (!comp) return;

    if (confirm(`"${comp.name}" firmasını ve tüm bağlı personellerini sistemden tamamen silmek istediğinize emin misiniz?`)) {
      superAdminCompanies = superAdminCompanies.filter(c => c.id !== companyId);
      saveSuperCompaniesToStorage();
      renderSuperCompaniesTable();
      renderSuperDashboard();
      showToast(`"${comp.name}" firması silindi.`);
    }
  };

  // =========================================================
  // 3. DEMO TALEPLERİ HAVUZU & ONAYLAMA
  // =========================================================
  const superDemoTableBody = document.getElementById('superDemoTableBody');
  const superDemoSearch = document.getElementById('superDemoSearch');
  const superDemoStatusFilter = document.getElementById('superDemoStatusFilter');
  const superDemosCountBadge = document.getElementById('superDemosCountBadge');

  if (superDemoSearch) superDemoSearch.addEventListener('input', renderSuperDemoRequestsTable);
  if (superDemoStatusFilter) superDemoStatusFilter.addEventListener('change', renderSuperDemoRequestsTable);

  function renderSuperDemoRequestsTable() {
    if (!superDemoTableBody) return;

    let list = [...superAdminDemoRequests];

    const q = superDemoSearch ? superDemoSearch.value.trim().toLowerCase() : '';
    if (q) {
      list = list.filter(d =>
        d.companyName.toLowerCase().includes(q) ||
        d.contactName.toLowerCase().includes(q) ||
        d.email.toLowerCase().includes(q) ||
        d.phone.includes(q)
      );
    }

    const statusVal = superDemoStatusFilter ? superDemoStatusFilter.value : 'all';
    if (statusVal !== 'all') {
      list = list.filter(d => d.status === statusVal);
    }

    if (superDemosCountBadge) {
      superDemosCountBadge.textContent = `${list.length} Talep Listeleniyor`;
    }

    if (list.length === 0) {
      superDemoTableBody.innerHTML = `
        <tr>
          <td colspan="8" style="text-align: center; padding: 32px; color: var(--super-text-sub);">
            Seçilen filtreye uygun demo talebi bulunamadı.
          </td>
        </tr>
      `;
      return;
    }

    superDemoTableBody.innerHTML = list.map(d => {
      const statusPill = d.status === 'pending'
        ? `<span class="status-pill demo">⏳ Onay Bekliyor</span>`
        : d.status === 'approved'
        ? `<span class="status-pill active">✅ Demo Açıldı</span>`
        : `<span class="status-pill expired">❌ Reddedildi</span>`;

      const matchedTier = getUserTierForSeats(d.employeeCount);

      return `
        <tr>
          <td>
            <div class="font-bold text-slate-900" style="font-size: 13.5px;">${d.companyName}</div>
          </td>
          <td>
            <div class="font-bold text-slate-800">${d.contactName}</div>
            <div class="text-xs text-muted font-mono">${d.email}</div>
          </td>
          <td>
            <span class="text-xs font-mono font-bold">${d.phone}</span>
          </td>
          <td>
            <span class="super-tier-badge">${matchedTier.label}</span>
          </td>
          <td>
            <span class="text-xs text-slate-700">${d.requestDate}</span>
          </td>
          <td>${statusPill}</td>
          <td class="text-right">
            <div class="super-actions-cell">
              ${d.status === 'pending' ? `
                <button class="super-act-btn square demo-approve" onclick="window.openSuperDemoApproveModal('${d.id}')" title="Demo Aç (14 Gün)">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </button>
              ` : ''}
              <button class="super-act-btn square" onclick="window.openSuperDemoDetailsModal('${d.id}')" title="Başvuru Detayını Oku">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              </button>
              ${d.status === 'pending' ? `
                <button class="super-act-btn square danger" onclick="window.rejectSuperDemoRequest('${d.id}')" title="Talebi Reddet">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
              ` : ''}
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  // Demo Approve & Open Modal
  let currentApproveDemoId = null;
  const superDemoApproveModal = document.getElementById('superDemoApproveModal');
  const btnConfirmApproveDemo = document.getElementById('btnConfirmApproveDemo');
  const demoGeneratedCredentials = document.getElementById('demoGeneratedCredentials');

  window.openSuperDemoApproveModal = function(demoId) {
    const demo = superAdminDemoRequests.find(d => d.id === demoId);
    if (!demo || !superDemoApproveModal) return;

    currentApproveDemoId = demo.id;
    document.getElementById('demoApproveCompName').textContent = demo.companyName;
    document.getElementById('demoApproveContact').textContent = demo.contactName;
    document.getElementById('demoApproveEmail').textContent = demo.email;
    const matchedTier = getUserTierForSeats(demo.employeeCount);
    document.getElementById('demoApproveSeats').textContent = `${matchedTier.label} (Talep: ${demo.employeeCount} Kişi)`;
    const noteEl = document.getElementById('demoApproveNote');
    if (noteEl) noteEl.textContent = demo.note || '-';

    document.getElementById('approveDemoDays').value = demo.trialDays || 14;
    const quotaInput = document.getElementById('approveDemoQuota');
    if (quotaInput) quotaInput.value = 10; // Demo quota strictly fixed to 1-10

    if (demoGeneratedCredentials) demoGeneratedCredentials.classList.add('hidden');
    openModal(superDemoApproveModal);
  };

  if (btnConfirmApproveDemo) {
    btnConfirmApproveDemo.addEventListener('click', () => {
      const demo = superAdminDemoRequests.find(d => d.id === currentApproveDemoId);
      if (!demo) return;

      const trialDays = parseInt(document.getElementById('approveDemoDays').value, 10) || 14;
      const trialQuota = parseInt(document.getElementById('approveDemoQuota').value, 10) || 10;

      // 1. Mark demo as approved
      demo.status = 'approved';
      saveSuperDemosToStorage();

      // 2. Automatically create Company in Super Admin Companies List
      const expiryDate = new Date();
      expiryDate.setDate(expiryDate.getDate() + trialDays);

      const matchedTier = getUserTierForSeats(trialQuota);

      const existingComp = superAdminCompanies.find(c => c.name.toLowerCase() === demo.companyName.toLowerCase() || c.email === demo.email);
      if (!existingComp) {
        const newDemoCompany = {
          id: "comp-demo-" + Date.now(),
          name: demo.companyName,
          sector: demo.sector || "Genel Sektör",
          managerName: demo.contactName,
          email: demo.email,
          phone: demo.phone,
          planId: matchedTier.id,
          planName: `${matchedTier.label} (Demo)`,
          employeeQuota: trialQuota,
          activeEmployees: 1,
          billingCycle: "monthly",
          pricePerSeat: matchedTier.monthlyPrice || 149,
          mrr: 0,
          status: "demo",
          startDate: new Date().toISOString().split('T')[0],
          expiryDate: expiryDate.toISOString().split('T')[0],
          logo: ""
        };
        superAdminCompanies.unshift(newDemoCompany);
        saveSuperCompaniesToStorage();
      }

      // 3. Show credentials
      const slug = demo.companyName.toLowerCase().replace(/[^a-z0-9]/g, '');
      const genLink = `https://monacard.com/${slug}?demo=active`;
      const genPass = `Mona${Math.floor(1000 + Math.random() * 9000)}!`;

      if (demoGeneratedCredentials) {
        document.getElementById('demoGenLink').textContent = genLink;
        document.getElementById('demoGenPass').textContent = genPass;
        demoGeneratedCredentials.classList.remove('hidden');
      }

      renderSuperDemoRequestsTable();
      renderSuperCompaniesTable();
      renderSuperDashboard();

      showToast(`🎉 "${demo.companyName}" için ${trialDays} günlük kurumsal demo başarıyla aktifleştirildi! 🚀`);
    });
  }

  // Demo Details Modal
  const superDemoDetailsModal = document.getElementById('superDemoDetailsModal');
  const btnDetApproveDemoAction = document.getElementById('btnDetApproveDemoAction');

  window.openSuperDemoDetailsModal = function(demoId) {
    const demo = superAdminDemoRequests.find(d => d.id === demoId);
    if (!demo || !superDemoDetailsModal) return;

    currentApproveDemoId = demo.id;
    document.getElementById('detDemoCompName').textContent = demo.companyName;
    document.getElementById('detDemoSector').textContent = demo.sector;
    document.getElementById('detDemoContact').textContent = demo.contactName;
    
    const emailEl = document.getElementById('detDemoEmail');
    if (emailEl) {
      emailEl.textContent = demo.email;
      emailEl.href = `mailto:${demo.email}`;
    }

    const phoneEl = document.getElementById('detDemoPhone');
    if (phoneEl) {
      phoneEl.textContent = demo.phone;
      phoneEl.href = `tel:${demo.phone}`;
    }

    document.getElementById('detDemoSeats').textContent = `${demo.employeeCount} Çalışan`;
    document.getElementById('detDemoDate').textContent = demo.requestDate;
    document.getElementById('detDemoMessage').textContent = demo.note;

    if (btnDetApproveDemoAction) {
      btnDetApproveDemoAction.style.display = demo.status === 'pending' ? 'block' : 'none';
      btnDetApproveDemoAction.onclick = () => {
        closeModal(superDemoDetailsModal);
        openSuperDemoApproveModal(demo.id);
      };
    }

    openModal(superDemoDetailsModal);
  };

  window.rejectSuperDemoRequest = function(demoId) {
    const demo = superAdminDemoRequests.find(d => d.id === demoId);
    if (!demo) return;

    if (confirm(`"${demo.companyName}" demo talebini reddetmek istediğinize emin misiniz?`)) {
      demo.status = 'rejected';
      saveSuperDemosToStorage();
      renderSuperDemoRequestsTable();
      renderSuperDashboard();
      showToast(`Demo talebi reddedildi.`);
    }
  };

  // =========================================================
  // 4. KULLANICI SAYISINA GÖRE DİNAMİK FİYATLANDIRMA & DİLİMLER (YILLIK SATIŞ)
  // =========================================================
  const simEmployeeSlider = document.getElementById('simEmployeeSlider');
  const superTierPricingTableBody = document.getElementById('superTierPricingTableBody');
  const btnSaveAllPricingPlans = document.getElementById('btnSaveAllPricingPlans');
  const btnSaveAllPricingPlansBottom = document.getElementById('btnSaveAllPricingPlansBottom');

  function calculateDynamicPricing(tierIndex) {
    const idx = Math.max(0, Math.min(superAdminUserTiers.length - 1, tierIndex));
    const matchedTier = superAdminUserTiers[idx] || superAdminUserTiers[2];

    const simSelectedTierDisplay = document.getElementById('simSelectedTierDisplay');
    const simMatchedPlanBadge = document.getElementById('simMatchedPlanBadge');
    const simUnitSeatPrice = document.getElementById('simUnitSeatPrice');
    const simTotalArr = document.getElementById('simTotalArr');

    if (simSelectedTierDisplay) {
      simSelectedTierDisplay.textContent = matchedTier.label;
    }

    // Update marks active state
    document.querySelectorAll('.range-step-mark').forEach(m => {
      const step = parseInt(m.getAttribute('data-step'), 10);
      if (step === idx) m.classList.add('active');
      else m.classList.remove('active');
    });

    if (matchedTier.isCustomContact || idx === 6) {
      if (simMatchedPlanBadge) {
        simMatchedPlanBadge.textContent = "🏢 100+ Kullanıcı (Kurumsal VIP & Özel Çözüm)";
      }
      if (simUnitSeatPrice) {
        simUnitSeatPrice.textContent = "Özel Fiyatlandırma";
      }
      if (simTotalArr) {
        simTotalArr.textContent = "İrtibata Geçin (Özel Kurumsal Teklif)";
      }
      return { matchedTier, unitPrice: 0, totalYearlyArr: 0 };
    }

    const yearlyUnit = matchedTier.yearlyPrice || (matchedTier.monthlyPrice ? Math.round(matchedTier.monthlyPrice * 10) : 1490);
    const maxSeats = matchedTier.max || 10;
    const totalYearlyArr = maxSeats * yearlyUnit;

    if (simMatchedPlanBadge) {
      simMatchedPlanBadge.textContent = `👑 ${matchedTier.label} Dilimi Eşleşti`;
    }
    if (simUnitSeatPrice) {
      simUnitSeatPrice.textContent = `₺${yearlyUnit.toLocaleString('tr-TR')} / kişi / yıl`;
    }
    if (simTotalArr) {
      simTotalArr.textContent = `₺${totalYearlyArr.toLocaleString('tr-TR')} / yıl (${maxSeats} Kişilik Paket)`;
    }

    return { matchedTier, unitPrice: yearlyUnit, totalYearlyArr };
  }

  if (simEmployeeSlider) {
    simEmployeeSlider.addEventListener('input', (e) => {
      const step = parseInt(e.target.value, 10) || 0;
      calculateDynamicPricing(step);
    });
  }

  document.querySelectorAll('.range-step-mark').forEach(mark => {
    mark.addEventListener('click', () => {
      const step = parseInt(mark.getAttribute('data-step'), 10) || 0;
      if (simEmployeeSlider) simEmployeeSlider.value = step;
      calculateDynamicPricing(step);
    });
  });

  function renderSuperPricingPlans() {
    if (!superTierPricingTableBody) return;

    superTierPricingTableBody.innerHTML = superAdminUserTiers.map((tier, idx) => {
      if (tier.isCustomContact) {
        return `
          <tr>
            <td>
              <div class="font-bold text-slate-900 flex items-center gap-2">
                <span class="super-tier-badge" style="background:#F1F5F9; color:#475569; border-color:#CBD5E1;">🏢 ${tier.label}</span>
              </div>
            </td>
            <td>
              <span class="text-xs text-slate-600">${tier.note || '100 üzeri çalışanlar için özel teklif ve kurumsal SLA'}</span>
            </td>
            <td>
              <span class="text-sm font-bold text-indigo-600">Özel Teklif / İrtibata Geçin</span>
            </td>
            <td>
              <span class="status-pill active" style="background:#EEF2FF; color:#4F46E5; border-color:#C7D2FE;">⚡ Özel Görüşme</span>
            </td>
          </tr>
        `;
      }

      const yearlyPrice = tier.yearlyPrice || (tier.monthlyPrice ? Math.round(tier.monthlyPrice * 10) : 1490);

      return `
        <tr>
          <td>
            <div class="font-bold text-slate-900 flex items-center gap-2">
              <span class="super-tier-badge">👥 ${tier.label}</span>
            </div>
          </td>
          <td>
            <span class="text-xs text-slate-600">${tier.note || ''}</span>
          </td>
          <td>
            <div class="tier-price-input-wrap">
              <input type="number" id="inputTierPrice-${tier.id}" value="${yearlyPrice}" min="100" max="99999" step="10" class="tier-input-field" onchange="window.updateTierYearlyPrice('${tier.id}')">
              <span class="tier-unit-label">₺ / kişi / yıl</span>
            </div>
          </td>
          <td>
            <span class="status-pill active">🟢 Yayında</span>
          </td>
        </tr>
      `;
    }).join('');

    // Trigger dynamic calculation with current slider value
    const currentStep = simEmployeeSlider ? parseInt(simEmployeeSlider.value, 10) || 2 : 2;
    calculateDynamicPricing(currentStep);
  }

  window.updateTierYearlyPrice = function(tierId) {
    const tier = superAdminUserTiers.find(t => t.id === tierId);
    if (!tier) return;
    const priceInput = document.getElementById(`inputTierPrice-${tier.id}`);
    if (priceInput) {
      tier.yearlyPrice = parseInt(priceInput.value, 10) || 1490;
    }
    const currentStep = simEmployeeSlider ? parseInt(simEmployeeSlider.value, 10) || 2 : 2;
    calculateDynamicPricing(currentStep);
  };

  function saveAllPricingPlansHandler() {
    superAdminUserTiers.forEach(tier => {
      if (!tier.isCustomContact) {
        const priceInput = document.getElementById(`inputTierPrice-${tier.id}`);
        if (priceInput) {
          tier.yearlyPrice = parseInt(priceInput.value, 10) || tier.yearlyPrice || 1490;
        }
      }
    });

    saveSuperPricingToStorage();
    const currentStep = simEmployeeSlider ? parseInt(simEmployeeSlider.value, 10) || 2 : 2;
    calculateDynamicPricing(currentStep);
    showToast("💾 Yıllık kullanıcı dilim fiyatları başarıyla kaydedildi! ✨");
  }

  if (btnSaveAllPricingPlans) {
    btnSaveAllPricingPlans.addEventListener('click', saveAllPricingPlansHandler);
  }
  if (btnSaveAllPricingPlansBottom) {
    btnSaveAllPricingPlansBottom.addEventListener('click', saveAllPricingPlansHandler);
  }

  // =========================================================
  // 5. SAAS PLATFORM AYARLARI
  // =========================================================
  const btnSaveSuperPlatformSettings = document.getElementById('btnSaveSuperPlatformSettings');
  const settingPlatformName = document.getElementById('settingPlatformName');
  const settingSupportEmail = document.getElementById('settingSupportEmail');
  const settingOwnerName = document.getElementById('settingOwnerName');
  const settingCurrency = document.getElementById('settingCurrency');
  const settingDefaultTrialDays = document.getElementById('settingDefaultTrialDays');
  const settingDefaultTrialSeats = document.getElementById('settingDefaultTrialSeats');
  const settingAutoDemoNotification = document.getElementById('settingAutoDemoNotification');

  function renderSuperSettings() {
    if (settingPlatformName) settingPlatformName.value = superAdminSettings.platformName || "MonaCard Multi-Tenant SaaS";
    if (settingSupportEmail) settingSupportEmail.value = superAdminSettings.supportEmail || "support@monacard.com";
    if (settingOwnerName) settingOwnerName.value = superAdminSettings.ownerName || "Muhiddin Öktem";
    if (settingCurrency) settingCurrency.value = superAdminSettings.currency || "TRY";
    if (settingDefaultTrialDays) settingDefaultTrialDays.value = superAdminSettings.defaultTrialDays || 14;
    if (settingDefaultTrialSeats) settingDefaultTrialSeats.value = superAdminSettings.defaultTrialSeats || 10;
    if (settingAutoDemoNotification) settingAutoDemoNotification.checked = !!superAdminSettings.autoDemoNotification;
  }

  if (btnSaveSuperPlatformSettings) {
    btnSaveSuperPlatformSettings.addEventListener('click', () => {
      superAdminSettings = {
        platformName: settingPlatformName ? settingPlatformName.value.trim() : "MonaCard Multi-Tenant SaaS",
        supportEmail: settingSupportEmail ? settingSupportEmail.value.trim() : "support@monacard.com",
        ownerName: settingOwnerName ? settingOwnerName.value.trim() : "Muhiddin Öktem",
        currency: settingCurrency ? settingCurrency.value : "TRY",
        defaultTrialDays: settingDefaultTrialDays ? parseInt(settingDefaultTrialDays.value, 10) || 14 : 14,
        defaultTrialSeats: settingDefaultTrialSeats ? parseInt(settingDefaultTrialSeats.value, 10) || 10 : 10,
        autoDemoNotification: settingAutoDemoNotification ? settingAutoDemoNotification.checked : true
      };

      saveSuperSettingsToStorage();
      showToast("💾 SaaS Platform ayarları başarıyla güncellendi!");
    });
  }

  // Master Super Admin Render
  function renderSuperAll() {
    renderSuperDashboard();
    renderSuperCompaniesTable();
    renderSuperDemoRequestsTable();
    renderSuperPricingPlans();
    renderSuperSettings();
  }

  // Centralized Master Render
  function renderAdminAll() {
    renderAdminDashboard();
    renderAdminStaffTable();
    renderAdminCrmTable();
    renderAdminCalendar();
    renderAdminSettings();
    renderAdminProfile();
  }

  // Initial renders
  populateCompanyFilter();
  renderCrmList();
  initNewMeetingParticipantsMultiSelect();
  initEditMeetingParticipantsMultiSelect();
  populateMeetingCustomerSelect();
  renderMeetingsList();
  renderRemindersList();

  // Initial Language Apply
  document.querySelectorAll('.lang-switcher-pill').forEach(pill => {
    pill.querySelectorAll('.lang-btn').forEach(btn => {
      const bLang = btn.getAttribute('data-lang');
      if (bLang === currentLang || (bLang === 'eng' && currentLang === 'en')) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
  });
  updateStaticTranslations();

  // =========================================================
  // AUTH MODAL & LOGIN / REGISTER CONTROLLER
  // =========================================================
  const modalAuth = document.getElementById('modalAuth');
  const btnOpenAuthModal = document.getElementById('btnOpenAuthModal');
  const authBtnLabel = document.getElementById('authBtnLabel');
  const tabBtnLogin = document.getElementById('tabBtnLogin');
  const tabBtnRegister = document.getElementById('tabBtnRegister');
  const authPanelLogin = document.getElementById('authPanelLogin');
  const authPanelRegister = document.getElementById('authPanelRegister');
  const formAuthLogin = document.getElementById('formAuthLogin');
  const formAuthRegister = document.getElementById('formAuthRegister');
  const loginEmailInput = document.getElementById('loginEmail');
  const loginPasswordInput = document.getElementById('loginPassword');

  // Check saved logged in user
  function updateAuthButtonState() {
    try {
      const savedUserStr = localStorage.getItem('monacard_user');
      if (savedUserStr && authBtnLabel) {
        const savedUser = JSON.parse(savedUserStr);
        const firstName = (savedUser.name || 'Kullanıcı').split(' ')[0];
        const roleLabel = savedUser.role === 'superadmin' ? 'SaaS Sahibi' : savedUser.role === 'company_admin' ? 'Yönetici' : 'Personel';
        authBtnLabel.textContent = `👤 ${firstName} (${roleLabel})`;
      }
    } catch (e) {}
  }
  updateAuthButtonState();

  // Open Auth Modal
  if (btnOpenAuthModal) {
    btnOpenAuthModal.addEventListener('click', (e) => {
      e.preventDefault();
      if (modalAuth) {
        modalAuth.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    });
  }

  // Close Auth Modal
  document.querySelectorAll('[data-close="modalAuth"]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (modalAuth) {
        modalAuth.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  });

  if (modalAuth) {
    modalAuth.addEventListener('click', (e) => {
      if (e.target === modalAuth) {
        modalAuth.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  }

  // Tab switching (Login vs Register)
  if (tabBtnLogin && tabBtnRegister) {
    tabBtnLogin.addEventListener('click', () => {
      tabBtnLogin.classList.add('active');
      tabBtnRegister.classList.remove('active');
      if (authPanelLogin) authPanelLogin.classList.remove('hidden');
      if (authPanelRegister) authPanelRegister.classList.add('hidden');
    });

    tabBtnRegister.addEventListener('click', () => {
      tabBtnRegister.classList.add('active');
      tabBtnLogin.classList.remove('active');
      if (authPanelRegister) authPanelRegister.classList.remove('hidden');
      if (authPanelLogin) authPanelLogin.classList.add('hidden');
    });
  }

  // Quick Demo Chips click handler
  document.querySelectorAll('.quick-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      document.querySelectorAll('.quick-chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');

      const email = chip.getAttribute('data-login-email');
      if (email && loginEmailInput) {
        loginEmailInput.value = email;
      }
      if (loginPasswordInput) {
        loginPasswordInput.value = 'password';
      }
    });
  });

  // Handle Login Form Submit
  if (formAuthLogin) {
    formAuthLogin.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = loginEmailInput ? loginEmailInput.value.trim() : '';
      const password = loginPasswordInput ? loginPasswordInput.value.trim() : '';

      if (!email || !password) {
        showToast('Lütfen e-posta ve şifrenizi girin.');
        return;
      }

      const btnSubmit = document.getElementById('btnSubmitLogin');
      if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span>⏳ Giriş yapılıyor...</span>';
      }

      const res = await MonaCardAPI.login(email, password);

      if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg><span>🚀 Sisteme Giriş Yap</span>`;
      }

      if (res && res.status === 'success' && res.data) {
        const { token, user } = res.data;
        try {
          localStorage.setItem('monacard_token', token);
          localStorage.setItem('monacard_user', JSON.stringify(user));
        } catch (err) {}

        updateAuthButtonState();
        if (modalAuth) {
          modalAuth.classList.remove('active');
          document.body.style.overflow = '';
        }

        // Switch role based on user role
        if (user.role === 'superadmin') {
          setRole('superadmin');
          showToast(`Hoş geldiniz, ${user.name}! 👑 SaaS Süper Admin paneli açıldı.`);
        } else if (user.role === 'company_admin') {
          setRole('admin');
          showToast(`Hoş geldiniz, ${user.name}! 🏢 ${user.company?.name || 'Firma'} Yönetici paneli açıldı.`);
        } else {
          setRole('staff');
          showToast(`Hoş geldiniz, ${user.name}! 💼 Personel portalı açıldı.`);
        }
      } else {
        // Fallback for offline demo credentials matching
        if (email === 'superadmin@monacard.com') {
          setRole('superadmin');
          if (modalAuth) modalAuth.classList.remove('active');
          showToast('👑 Süper Admin (SaaS Sahibi) olarak giriş yapıldı!');
        } else if (email === 'muhiddinoktem@vedubox.com') {
          setRole('admin');
          if (modalAuth) modalAuth.classList.remove('active');
          showToast('🏢 Muhiddin Öktem (Firma Yöneticisi) olarak giriş yapıldı!');
        } else if (email.includes('ali') || email.includes('zeynep')) {
          setRole('staff');
          if (modalAuth) modalAuth.classList.remove('active');
          showToast('💼 Personel portalı olarak giriş yapıldı!');
        } else {
          showToast(res?.message || 'Geçersiz e-posta veya şifre.');
        }
      }
    });
  }

  // Handle Register Form Submit (New Company)
  if (formAuthRegister) {
    formAuthRegister.addEventListener('submit', async (e) => {
      e.preventDefault();
      const compName = document.getElementById('regCompanyName')?.value.trim();
      const sector = document.getElementById('regSector')?.value;
      const adminName = document.getElementById('regAdminName')?.value.trim();
      const title = document.getElementById('regTitle')?.value.trim();
      const email = document.getElementById('regEmail')?.value.trim();
      const phone = document.getElementById('regPhone')?.value.trim();
      const password = document.getElementById('regPassword')?.value;

      if (!compName || !adminName || !email || !password) {
        showToast('Lütfen tüm zorunlu alanları doldurunuz.');
        return;
      }

      const btnSubmit = document.getElementById('btnSubmitRegister');
      if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span>⏳ Firma oluşturuluyor...</span>';
      }

      const res = await MonaCardAPI.register({
        company_name: compName,
        sector: sector,
        name: adminName,
        title: title || 'Kurucu & Yönetici',
        email: email,
        phone: phone,
        password: password
      });

      if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><span>✨ Firmayı Kaydet &amp; Paneli Aç</span>`;
      }

      if (res && res.status === 'success' && res.data) {
        const { token, user } = res.data;
        try {
          localStorage.setItem('monacard_token', token);
          localStorage.setItem('monacard_user', JSON.stringify(user));
        } catch (err) {}

        updateAuthButtonState();
        if (modalAuth) {
          modalAuth.classList.remove('active');
          document.body.style.overflow = '';
        }

        // Apply new company settings
        adminSettings.companyProfile.name = user.company?.name || compName;
        adminSettings.companyProfile.managerName = user.name;
        adminSettings.companyProfile.sector = user.company?.sector || sector;
        adminSettings.companyProfile.email = user.email;
        saveAdminSettingsToStorage();

        setRole('admin');
        showToast(`🎉 Tebrikler! ${compName} firması başarıyla kuruldu ve Yönetici Paneli açıldı!`);
      } else {
        showToast(res?.message || 'Firma kaydı sırasında bir hata oluştu.');
      }
    });
  }

  // Apply initial active role (Customer, Staff, Admin, or SuperAdmin)
  setRole(activeRole, true);
});

