@extends('layouts.app')

@section('title', 'Firma Yönetici Paneli | ' . ($company->name ?? 'MonaCard'))

@section('content')
<div style="max-width: 1100px; margin: 0 auto; padding: 24px 16px; min-height: 100vh;">
  
  <!-- Header Bar -->
  <header style="display:flex; justify-content:space-between; align-items:center; background:#FFFFFF; border-radius:20px; padding:18px 24px; box-shadow:0 4px 20px rgba(0,0,0,0.04); margin-bottom:20px;">
    <div style="display:flex; align-items:center; gap:14px;">
      <div style="width:48px; height:48px; border-radius:14px; background:#F8FAFC; border:1px solid #E2E8F0; display:flex; align-items:center; justify-content:center; overflow:hidden;">
        @if($company->logo_url)
          <img src="{{ asset($company->logo_url) }}" alt="{{ $company->name }}" style="width:100%; height:100%; object-fit:contain;">
        @else
          <span style="font-size:20px;">🏢</span>
        @endif
      </div>
      <div>
        <h2 style="font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; color:#0F172A; margin:0;">
          {{ $company->name }}
        </h2>
        <span style="font-size:12.5px; color:#64748B;">Firma Yöneticisi: <strong>{{ $user->name }}</strong></span>
      </div>
    </div>

    <div style="display:flex; align-items:center; gap:10px;">
      <a href="/" target="_blank" style="padding:8px 14px; background:#ECFDF5; color:#059669; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
        🌐 Kartviziti İncele
      </a>
      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" style="padding:8px 14px; background:#F1F5F9; color:#64748B; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
          Çıkış Yap
        </button>
      </form>
    </div>
  </header>

  <!-- KPI Metrics Bar -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:24px;">
    <!-- Quota Box -->
    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04); border-left:4px solid #00A86B;">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:6px;">👥 Kullanıcı Kotası</div>
      <div style="display:flex; align-items:baseline; gap:6px;">
        <span style="font-size:26px; font-weight:800; color:#0F172A; font-family:'Space Grotesk',sans-serif;">{{ $totalStaff }}</span>
        <span style="font-size:14px; color:#94A3B8;">/ {{ $company->user_quota }} Aktif</span>
      </div>
    </div>

    <!-- Active Cards -->
    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04); border-left:4px solid #0284C7;">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:6px;">📇 Aktif Dijital Kartvizitler</div>
      <div style="font-size:26px; font-weight:800; color:#0284C7; font-family:'Space Grotesk',sans-serif;">
        {{ $activeCardsCount }}
      </div>
    </div>

    <!-- Total CRM Leads -->
    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04); border-left:4px solid #6366F1;">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:6px;">📈 Toplam Kurumsal Lead</div>
      <div style="font-size:26px; font-weight:800; color:#6366F1; font-family:'Space Grotesk',sans-serif;">
        {{ $totalCrmCount }}
      </div>
    </div>

    <!-- Hot Leads -->
    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04); border-left:4px solid #EF4444;">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:6px;">🔥 Sıcak Müşteri Adayları</div>
      <div style="font-size:26px; font-weight:800; color:#EF4444; font-family:'Space Grotesk',sans-serif;">
        {{ $hotLeadsCount }}
      </div>
    </div>
  </div>

  <!-- Sections Grid -->
  <div style="display:flex; flex-direction:column; gap:24px;">

    <!-- SECTION 1: STAFF MANAGEMENT -->
    <div style="background:#FFFFFF; border-radius:20px; padding:24px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
        <div>
          <h3 style="font-family:'Space Grotesk',sans-serif; font-size:17px; font-weight:700; color:#0F172A; margin:0;">
            👥 Ekip Üyeleri & Dijital Kartvizitleri
          </h3>
          <p style="font-size:13px; color:#64748B; margin:2px 0 0 0;">Personel kartvizitlerini oluşturun, kota ve yetkilerini yönetin.</p>
        </div>

        <div style="display:flex; gap:8px;">
          <button onclick="document.getElementById('transferModal').style.display='flex'" style="padding:8px 14px; background:#F1F5F9; color:#334155; border:1px solid #CBD5E1; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
            🔄 Müşteri Aktar
          </button>
          <button onclick="document.getElementById('addStaffModal').style.display='flex'" style="padding:8px 16px; background:#00A86B; color:#FFF; border:none; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer;">
            + Yeni Personel Ekle
          </button>
        </div>
      </div>

      <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:13.5px;">
          <thead>
            <tr style="border-bottom:2px solid #F1F5F9; color:#64748B;">
              <th style="padding:12px 10px;">Personel</th>
              <th style="padding:12px 10px;">Departman & Ünvan</th>
              <th style="padding:12px 10px;">E-Posta & Tel</th>
              <th style="padding:12px 10px;">Lead Sayısı</th>
              <th style="padding:12px 10px;">Durum</th>
              <th style="padding:12px 10px; text-align:right;">İşlem</th>
            </tr>
          </thead>
          <tbody>
            @foreach($staffMembers as $staff)
              <tr style="border-bottom:1px solid #F8FAFC;">
                <td style="padding:12px 10px; font-weight:700; color:#0F172A;">
                  {{ $staff->name }}
                  @if($staff->role === 'company_admin')
                    <span style="font-size:11px; background:#E0E7FF; color:#4338CA; padding:2px 6px; border-radius:6px; margin-left:4px;">Yönetici</span>
                  @endif
                </td>
                <td style="padding:12px 10px; color:#475569;">
                  {{ $staff->title ?: 'Uzman' }} <br>
                  <span style="font-size:12px; color:#94A3B8;">{{ $staff->department ?: 'Genel' }}</span>
                </td>
                <td style="padding:12px 10px; color:#475569;">
                  {{ $staff->email }} <br>
                  <span style="font-size:12px; color:#94A3B8;">{{ $staff->phone ?: '-' }}</span>
                </td>
                <td style="padding:12px 10px; font-weight:700; color:#0284C7;">
                  {{ $staff->customers_count }} Lead
                </td>
                <td style="padding:12px 10px;">
                  @if($staff->status === 'active')
                    <span style="font-size:12px; font-weight:700; color:#059669; background:#ECFDF5; padding:4px 8px; border-radius:10px;">Aktif</span>
                  @else
                    <span style="font-size:12px; font-weight:700; color:#DC2626; background:#FEE2E2; padding:4px 8px; border-radius:10px;">Kapatıldı</span>
                  @endif
                </td>
                <td style="padding:12px 10px; text-align:right;">
                  <form method="POST" action="{{ route('admin.staff.toggle', $staff->id) }}" style="display:inline;">
                    @csrf
                    @if($staff->status === 'active')
                      <button type="submit" style="padding:6px 10px; background:#FFF1F2; color:#E11D48; border:1px solid #FECDD3; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;" title="Kartı iptal et">
                        Kartı İptal Et
                      </button>
                    @else
                      <button type="submit" style="padding:6px 10px; background:#ECFDF5; color:#059669; border:1px solid #A7F3D0; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;" title="Kartı tekrar aktif et">
                        Aktif Et
                      </button>
                    @endif
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- SECTION 2: PRODUCTS VITRINE & SETTINGS (2 COLUMN GRID) -->
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
      
      <!-- PRODUCTS CATALOG -->
      <div style="background:#FFFFFF; border-radius:20px; padding:24px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
          <h3 style="font-family:'Space Grotesk',sans-serif; font-size:17px; font-weight:700; color:#0F172A; margin:0;">
            📦 Ürün & Çözüm Vitrini
          </h3>
          <button onclick="document.getElementById('addProductModal').style.display='flex'" style="padding:6px 12px; background:#00A86B; color:#FFF; border:none; border-radius:10px; font-size:12px; font-weight:600; cursor:pointer;">
            + Ürün Ekle
          </button>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px;">
          @forelse($products as $p)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 12px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px;">
              <div>
                <div style="font-weight:700; font-size:13.5px; color:#0F172A;">{{ $p->name }}</div>
                <div style="font-size:12px; color:#64748B;">{{ $p->price ? number_format($p->price,0,',','.').' '.$p->currency : 'Fiyat Belirtilmedi' }}</div>
              </div>
              <form method="POST" action="{{ route('admin.product.delete', $p->id) }}" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Bu ürünü vitrinden silmek istediğinize emin misiniz?')" style="background:none; border:none; color:#EF4444; font-size:16px; cursor:pointer;">
                  🗑️
                </button>
              </form>
            </div>
          @empty
            <p style="font-size:13px; color:#94A3B8; text-align:center; padding:20px 0;">Henüz vitrine ürün eklenmemiş.</p>
          @endforelse
        </div>
      </div>

      <!-- BRANDING & SETTINGS -->
      <div style="background:#FFFFFF; border-radius:20px; padding:24px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <h3 style="font-family:'Space Grotesk',sans-serif; font-size:17px; font-weight:700; color:#0F172A; margin-bottom:16px;">
          🎨 Kurumsal Kimlik & Ayarlar
        </h3>

        <form method="POST" action="{{ route('admin.settings.update') }}">
          @csrf
          <div style="margin-bottom:12px;">
            <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Şirket Adı</label>
            <input type="text" name="name" value="{{ old('name', $company->name) }}" required style="width:100%; height:38px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
            <div>
              <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Kurumsal Renk</label>
              <input type="text" name="brand_color" value="{{ old('brand_color', $company->brand_color) }}" style="width:100%; height:38px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
            </div>
            <div>
              <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Tema Modu</label>
              <select name="theme_mode" style="width:100%; height:38px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
                <option value="light" {{ $company->theme_mode == 'light' ? 'selected' : '' }}>Aydınlık (Light)</option>
                <option value="dark" {{ $company->theme_mode == 'dark' ? 'selected' : '' }}>Karanlık (Dark)</option>
              </select>
            </div>
          </div>

          <div style="margin-bottom:12px;">
            <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Web Sitesi</label>
            <input type="url" name="website" value="{{ old('website', $company->website) }}" style="width:100%; height:38px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          </div>

          <div style="margin-bottom:16px;">
            <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Ofis Adresi</label>
            <textarea name="address" rows="2" style="width:100%; border:1px solid #CBD5E1; border-radius:10px; padding:6px 12px; font-size:13px; outline:none; font-family:inherit;">{{ old('address', $company->address) }}</textarea>
          </div>

          <button type="submit" style="width:100%; height:40px; background:#0F172A; color:#FFF; border:none; border-radius:10px; font-weight:700; font-size:13px; cursor:pointer;">
            Ayarları Güncelle
          </button>
        </form>
      </div>

    </div>

  </div>
</div>

<!-- Modal: Add Staff -->
<div id="addStaffModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
  <div style="background:#FFFFFF; border-radius:24px; padding:28px 24px; width:100%; max-width:460px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <h3 style="font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; color:#0F172A; margin:0;">+ Yeni Personel Kartı Oluştur</h3>
      <button onclick="document.getElementById('addStaffModal').style.display='none'" style="background:none; border:none; font-size:22px; cursor:pointer; color:#64748B;">&times;</button>
    </div>

    <form method="POST" action="{{ route('admin.staff.store') }}">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Ad Soyad *</label>
        <input type="text" name="name" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: Ahmet Yılmaz">
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Ünvan</label>
          <input type="text" name="title" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: Satış Müdürü">
        </div>
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Departman</label>
          <input type="text" name="department" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: Pazarlama">
        </div>
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">E-Posta Adresi *</label>
        <input type="email" name="email" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="personel@sirketiniz.com">
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Telefon</label>
        <input type="text" name="phone" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="+90 5XX XXX XX XX">
      </div>
      <div style="margin-bottom:16px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Giriş Şifresi *</label>
        <input type="password" name="password" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="En az 6 karakter">
      </div>

      <button type="submit" style="width:100%; height:44px; background:#00A86B; color:#FFF; border:none; border-radius:12px; font-weight:700; font-size:13.5px; cursor:pointer;">
        Personeli ve Kartviziti Oluştur
      </button>
    </form>
  </div>
</div>

<!-- Modal: Transfer Clients -->
<div id="transferModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
  <div style="background:#FFFFFF; border-radius:24px; padding:28px 24px; width:100%; max-width:440px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <h3 style="font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; color:#0F172A; margin:0;">🔄 Müşteri Lead Aktarımı</h3>
      <button onclick="document.getElementById('transferModal').style.display='none'" style="background:none; border:none; font-size:22px; cursor:pointer; color:#64748B;">&times;</button>
    </div>

    <form method="POST" action="{{ route('admin.staff.transfer') }}">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Müşterileri Alınacak Personel (Kaynak)</label>
        <select name="from_staff_id" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          @foreach($staffMembers as $s)
            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->customers_count }} Lead)</option>
          @endforeach
        </select>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Aktarılacak Personel (Hedef)</label>
        <select name="to_staff_id" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          @foreach($staffMembers as $s)
            <option value="{{ $s->id }}">{{ $s->name }}</option>
          @endforeach
        </select>
      </div>

      <button type="submit" style="width:100%; height:44px; background:#0284C7; color:#FFF; border:none; border-radius:12px; font-weight:700; font-size:13.5px; cursor:pointer;">
        Tüm Müşterileri Aktar
      </button>
    </form>
  </div>
</div>

<!-- Modal: Add Product -->
<div id="addProductModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
  <div style="background:#FFFFFF; border-radius:24px; padding:28px 24px; width:100%; max-width:440px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <h3 style="font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; color:#0F172A; margin:0;">+ Vitrine Ürün / Çözüm Ekle</h3>
      <button onclick="document.getElementById('addProductModal').style.display='none'" style="background:none; border:none; font-size:22px; cursor:pointer; color:#64748B;">&times;</button>
    </div>

    <form method="POST" action="{{ route('admin.product.store') }}">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Ürün / Çözüm Adı *</label>
        <input type="text" name="name" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: MonaCard Dijital Çözüm Paketi">
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Açıklama</label>
        <textarea name="description" rows="2" style="width:100%; border:1px solid #CBD5E1; border-radius:10px; padding:6px 12px; font-size:13px; outline:none; font-family:inherit;" placeholder="Kısa tanıtım metni..."></textarea>
      </div>
      <div style="display:grid; grid-template-columns:2fr 1fr; gap:10px; margin-bottom:16px;">
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Fiyat (Opsiyonel)</label>
          <input type="number" name="price" step="0.01" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: 4500">
        </div>
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Para Birimi</label>
          <input type="text" name="currency" value="₺" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
        </div>
      </div>

      <button type="submit" style="width:100%; height:44px; background:#00A86B; color:#FFF; border:none; border-radius:12px; font-weight:700; font-size:13.5px; cursor:pointer;">
        Vitrine Ekle
      </button>
    </form>
  </div>
</div>
@endsection
