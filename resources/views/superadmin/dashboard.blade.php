@extends('layouts.app')

@section('title', 'SaaS Süper Admin | MonaCard')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; padding: 24px 16px; min-height: 100vh;">
  
  <!-- Header Bar -->
  <header style="display:flex; justify-content:space-between; align-items:center; background:#0F172A; color:#FFF; border-radius:20px; padding:18px 24px; box-shadow:0 10px 30px rgba(0,0,0,0.2); margin-bottom:24px;">
    <div style="display:flex; align-items:center; gap:14px;">
      <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg, #00A86B, #008f5a); display:flex; align-items:center; justify-content:center; font-size:22px;">
        👑
      </div>
      <div>
        <h2 style="font-family:'Space Grotesk',sans-serif; font-size:19px; font-weight:700; color:#FFF; margin:0;">
          MonaCard 2.0 SaaS Süper Admin
        </h2>
        <span style="font-size:12.5px; color:#94A3B8;">Platform Yönetimi & Kurumsal Müşteriler</span>
      </div>
    </div>

    <div style="display:flex; align-items:center; gap:10px;">
      <button onclick="document.getElementById('addCompanyModal').style.display='flex'" style="padding:8px 16px; background:#00A86B; color:#FFF; border:none; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer;">
        + Yeni Firma Tanımla
      </button>
      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" style="padding:8px 14px; background:rgba(255,255,255,0.1); color:#FFF; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
          Çıkış
        </button>
      </form>
    </div>
  </header>

  <!-- KPI Metrics Bar -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:24px;">
    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:4px;">🏢 Toplam Müşteri Firma</div>
      <div style="font-size:26px; font-weight:800; color:#0F172A; font-family:'Space Grotesk',sans-serif;">{{ $totalCompanies }}</div>
    </div>

    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:4px;">📇 Dağıtılan Kartvizitler</div>
      <div style="font-size:26px; font-weight:800; color:#00A86B; font-family:'Space Grotesk',sans-serif;">{{ $totalCards }}</div>
    </div>

    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:4px;">💰 Tahmini Aylık Ciro (MRR)</div>
      <div style="font-size:26px; font-weight:800; color:#0284C7; font-family:'Space Grotesk',sans-serif;">₺{{ number_format($estimatedMrr, 0, ',', '.') }}</div>
    </div>

    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:4px;">📩 Bekleyen Demo Talepleri</div>
      <div style="font-size:26px; font-weight:800; color:#EF4444; font-family:'Space Grotesk',sans-serif;">{{ $pendingDemos }}</div>
    </div>
  </div>

  <!-- COMPANIES TABLE -->
  <div style="background:#FFFFFF; border-radius:20px; padding:24px; box-shadow:0 4px 20px rgba(0,0,0,0.04); margin-bottom:24px;">
    <h3 style="font-family:'Space Grotesk',sans-serif; font-size:17px; font-weight:700; color:#0F172A; margin-bottom:16px;">
      🏢 Kayıtlı Kurumsal Müşteriler (Firmalar)
    </h3>

    <div style="overflow-x:auto;">
      <table style="width:100%; border-collapse:collapse; text-align:left; font-size:13.5px;">
        <thead>
          <tr style="border-bottom:2px solid #F1F5F9; color:#64748B;">
            <th style="padding:12px 10px;">Firma Adı</th>
            <th style="padding:12px 10px;">Sektör</th>
            <th style="padding:12px 10px;">Personel / Kota</th>
            <th style="padding:12px 10px;">Paket</th>
            <th style="padding:12px 10px;">Durum</th>
            <th style="padding:12px 10px; text-align:right;">Kota Güncelle</th>
          </tr>
        </thead>
        <tbody>
          @foreach($companies as $c)
            <tr style="border-bottom:1px solid #F8FAFC;">
              <td style="padding:12px 10px; font-weight:700; color:#0F172A;">
                {{ $c->name }} <br>
                <span style="font-size:12px; color:#94A3B8; font-weight:normal;">{{ $c->website ?: $c->email }}</span>
              </td>
              <td style="padding:12px 10px; color:#475569;">{{ $c->sector ?: '-' }}</td>
              <td style="padding:12px 10px; font-weight:700; color:#0284C7;">
                {{ $c->users_count }} / {{ $c->user_quota }}
              </td>
              <td style="padding:12px 10px;">
                <span style="font-size:12px; font-weight:600; text-transform:uppercase; background:#F1F5F9; color:#334155; padding:3px 8px; border-radius:8px;">
                  {{ $c->plan }}
                </span>
              </td>
              <td style="padding:12px 10px;">
                @if($c->subscription_status === 'active')
                  <span style="font-size:12px; font-weight:700; color:#059669; background:#ECFDF5; padding:4px 8px; border-radius:10px;">Aktif</span>
                @else
                  <span style="font-size:12px; font-weight:700; color:#DC2626; background:#FEE2E2; padding:4px 8px; border-radius:10px;">{{ ucfirst($c->subscription_status) }}</span>
                @endif
              </td>
              <td style="padding:12px 10px; text-align:right;">
                <form method="POST" action="{{ route('superadmin.company.quota', $c->id) }}" style="display:inline-flex; align-items:center; gap:6px;">
                  @csrf
                  <input type="number" name="user_quota" value="{{ $c->user_quota }}" min="1" style="width:60px; height:32px; border:1px solid #CBD5E1; border-radius:8px; padding:0 6px; font-size:13px; text-align:center;">
                  <input type="hidden" name="subscription_status" value="{{ $c->subscription_status }}">
                  <input type="hidden" name="plan" value="{{ $c->plan }}">
                  <button type="submit" style="padding:6px 10px; background:#0F172A; color:#FFF; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                    Kaydet
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- DEMO REQUESTS TABLE -->
  <div style="background:#FFFFFF; border-radius:20px; padding:24px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
    <h3 style="font-family:'Space Grotesk',sans-serif; font-size:17px; font-weight:700; color:#0F172A; margin-bottom:16px;">
      📩 Gelen Demo ve Satış Talepleri
    </h3>

    <div style="overflow-x:auto;">
      <table style="width:100%; border-collapse:collapse; text-align:left; font-size:13.5px;">
        <thead>
          <tr style="border-bottom:2px solid #F1F5F9; color:#64748B;">
            <th style="padding:12px 10px;">Firma & İletişim</th>
            <th style="padding:12px 10px;">E-Posta & Tel</th>
            <th style="padding:12px 10px;">Çalışan Sayısı</th>
            <th style="padding:12px 10px;">Durum</th>
            <th style="padding:12px 10px; text-align:right;">Durumu Güncelle</th>
          </tr>
        </thead>
        <tbody>
          @forelse($demoRequests as $d)
            <tr style="border-bottom:1px solid #F8FAFC;">
              <td style="padding:12px 10px; font-weight:700; color:#0F172A;">
                {{ $d->company_name }} <br>
                <span style="font-size:12px; color:#64748B; font-weight:normal;">{{ $d->contact_name }}</span>
              </td>
              <td style="padding:12px 10px; color:#475569;">
                {{ $d->email }} <br>
                <span style="font-size:12px; color:#94A3B8;">{{ $d->phone ?: '-' }}</span>
              </td>
              <td style="padding:12px 10px; font-weight:600; color:#334155;">{{ $d->employee_count ?: '-' }}</td>
              <td style="padding:12px 10px;">
                <span style="font-size:12px; font-weight:700; padding:4px 8px; border-radius:10px;
                  @if($d->status == 'pending') background:#FEF3C7; color:#D97706;
                  @elseif($d->status == 'contacted') background:#E0E7FF; color:#4338CA;
                  @elseif($d->status == 'converted') background:#ECFDF5; color:#059669;
                  @else background:#FEE2E2; color:#DC2626; @endif">
                  {{ ucfirst($d->status) }}
                </span>
              </td>
              <td style="padding:12px 10px; text-align:right;">
                <form method="POST" action="{{ route('superadmin.demo.status', $d->id) }}" style="display:inline-flex; gap:6px;">
                  @csrf
                  <select name="status" onchange="this.form.submit()" style="height:32px; border:1px solid #CBD5E1; border-radius:8px; font-size:12px; padding:0 6px;">
                    <option value="pending" {{ $d->status == 'pending' ? 'selected' : '' }}>Beklemede</option>
                    <option value="contacted" {{ $d->status == 'contacted' ? 'selected' : '' }}>Görüşüldü</option>
                    <option value="converted" {{ $d->status == 'converted' ? 'selected' : '' }}>Satış Yapıldı</option>
                    <option value="rejected" {{ $d->status == 'rejected' ? 'selected' : '' }}>İptal</option>
                  </select>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center; padding:20px; color:#94A3B8;">Henüz demo talebi bulunmuyor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- Modal: Add Company Provisioning -->
<div id="addCompanyModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
  <div style="background:#FFFFFF; border-radius:24px; padding:28px 24px; width:100%; max-width:480px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <h3 style="font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; color:#0F172A; margin:0;">+ Yeni Kurumsal Müşteri Tanımla</h3>
      <button onclick="document.getElementById('addCompanyModal').style.display='none'" style="background:none; border:none; font-size:22px; cursor:pointer; color:#64748B;">&times;</button>
    </div>

    <form method="POST" action="{{ route('superadmin.company.store') }}">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Şirket Adı *</label>
        <input type="text" name="name" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: Acme Tech Ltd.">
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Sektör</label>
          <input type="text" name="sector" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: Finans">
        </div>
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Kullanıcı Kotası</label>
          <input type="number" name="user_quota" value="10" min="1" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
        </div>
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Yönetici Adı *</label>
        <input type="text" name="admin_name" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Yönetici isim">
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Yönetici E-Posta *</label>
        <input type="email" name="admin_email" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="admin@acme.com">
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px;">
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Şifre *</label>
          <input type="password" name="admin_password" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="••••••••">
        </div>
        <div>
          <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Paket</label>
          <select name="plan" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
            <option value="pro">Pro Plan</option>
            <option value="enterprise" selected>Enterprise Plan</option>
          </select>
        </div>
      </div>

      <button type="submit" style="width:100%; height:44px; background:#00A86B; color:#FFF; border:none; border-radius:12px; font-weight:700; font-size:13.5px; cursor:pointer;">
        Şirketi Oluştur ve Başlat
      </button>
    </form>
  </div>
</div>
@endsection
