@extends('layouts.app')

@section('title', 'Personel Portalı | ' . ($user->name ?? 'MonaCard'))

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 24px 16px; min-height: 100vh;">
  
  <!-- Top Bar -->
  <header style="display:flex; justify-content:space-between; align-items:center; background:#FFFFFF; border-radius:20px; padding:16px 24px; box-shadow:0 4px 20px rgba(0,0,0,0.04); margin-bottom:20px;">
    <div style="display:flex; align-items:center; gap:14px;">
      <div style="width:48px; height:48px; border-radius:50%; background:#00A86B; padding:2px;">
        <img src="{{ $card->avatar_url ? asset($card->avatar_url) : asset('avatar_clean.png') }}" alt="{{ $user->name }}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
      </div>
      <div>
        <h2 style="font-family:'Space Grotesk',sans-serif; font-size:17px; font-weight:700; color:#0F172A; margin:0;">
          {{ $user->name }}
        </h2>
        <span style="font-size:12.5px; color:#64748B;">{{ $user->title ?? 'Personel' }} • {{ $company->name ?? 'Kurumsal' }}</span>
      </div>
    </div>

    <div style="display:flex; align-items:center; gap:10px;">
      @if($card)
        <a href="{{ route('card.show', $card->slug) }}" target="_blank" style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; background:#ECFDF5; color:#059669; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
          <span>🌐 Kartımı Gör</span>
        </a>
      @endif

      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" style="padding:8px 14px; background:#F1F5F9; color:#64748B; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
          Çıkış
        </button>
      </form>
    </div>
  </header>

  <!-- Performance Target Tracker (Aylık Hedefler) -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:20px;">
    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:6px;">🔥 Sıcak Lead Hedefi</div>
      <div style="display:flex; align-items:baseline; gap:8px;">
        <span style="font-size:26px; font-weight:800; color:#EF4444; font-family:'Space Grotesk',sans-serif;">{{ $hotLeadsCount }}</span>
        <span style="font-size:14px; color:#94A3B8;">/ {{ $target->monthly_hot_lead_goal ?? 10 }} Hedef</span>
      </div>
    </div>

    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:6px;">📅 Bu Ayki Toplantılar</div>
      <div style="display:flex; align-items:baseline; gap:8px;">
        <span style="font-size:26px; font-weight:800; color:#00A86B; font-family:'Space Grotesk',sans-serif;">{{ $meetingsCount }}</span>
        <span style="font-size:14px; color:#94A3B8;">/ {{ $target->monthly_meeting_goal ?? 20 }} Hedef</span>
      </div>
    </div>

    <div style="background:#FFFFFF; border-radius:18px; padding:18px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
      <div style="font-size:13px; color:#64748B; font-weight:600; margin-bottom:6px;">👥 Toplam CRM Kontaklarım</div>
      <div style="font-size:26px; font-weight:800; color:#0284C7; font-family:'Space Grotesk',sans-serif;">
        {{ $customers->count() }}
      </div>
    </div>
  </div>

  <!-- Grid Content: CRM & Profile -->
  <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
    
    <!-- LEFT: CRM LEADS & MEETINGS -->
    <div style="display:flex; flex-direction:column; gap:20px;">
      
      <!-- CRM Customer Pool -->
      <div style="background:#FFFFFF; border-radius:20px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
          <h3 style="font-family:'Space Grotesk',sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin:0;">
            📇 CRM Müşteri Havuzum
          </h3>
          <button onclick="document.getElementById('addCustomerModal').style.display='flex'" style="padding:6px 12px; background:#00A86B; color:#FFF; border:none; border-radius:10px; font-size:12px; font-weight:600; cursor:pointer;">
            + Lead Ekle
          </button>
        </div>

        @if($customers->isEmpty())
          <p style="font-size:13px; color:#94A3B8; text-align:center; padding:20px 0;">Henüz eklenmiş müşteri kaydı bulunmuyor.</p>
        @else
          <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($customers as $c)
              <div style="padding:12px; border-radius:12px; background:#F8FAFC; border:1px solid #E2E8F0; display:flex; justify-content:space-between; align-items:center;">
                <div>
                  <div style="font-weight:700; font-size:13.5px; color:#0F172A;">{{ $c->name }}</div>
                  <div style="font-size:12px; color:#64748B;">{{ $c->company_name ?: 'Şirket Belirtilmedi' }} • {{ $c->phone ?: $c->email }}</div>
                </div>
                <span style="font-size:11px; font-weight:700; padding:4px 8px; border-radius:20px; 
                  @if($c->stage == 'hot') background:#FEE2E2; color:#DC2626; 
                  @elseif($c->stage == 'warm') background:#FEF3C7; color:#D97706; 
                  @else background:#F1F5F9; color:#64748B; @endif">
                  {{ strtoupper($c->stage) }}
                </span>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Scheduled Meetings -->
      <div style="background:#FFFFFF; border-radius:20px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
          <h3 style="font-family:'Space Grotesk',sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin:0;">
            📅 Randevu & Toplantılar
          </h3>
          <button onclick="document.getElementById('addMeetingModal').style.display='flex'" style="padding:6px 12px; background:#0284C7; color:#FFF; border:none; border-radius:10px; font-size:12px; font-weight:600; cursor:pointer;">
            + Toplantı Ekle
          </button>
        </div>

        @if($meetings->isEmpty())
          <p style="font-size:13px; color:#94A3B8; text-align:center; padding:20px 0;">Planlanmış toplantı bulunmuyor.</p>
        @else
          <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($meetings as $m)
              <div style="padding:12px; border-radius:12px; background:#F8FAFC; border:1px solid #E2E8F0;">
                <div style="font-weight:700; font-size:13.5px; color:#0F172A; margin-bottom:2px;">{{ $m->title }}</div>
                <div style="font-size:12px; color:#64748B;">🕒 {{ \Carbon\Carbon::parse($m->start_time)->format('d.m.Y H:i') }} ({{ ucfirst($m->meeting_type) }})</div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

    </div>

    <!-- RIGHT: DIGITAL CARD PROFILE EDITOR & REMINDERS -->
    <div style="display:flex; flex-direction:column; gap:20px;">
      
      <!-- Card Editor -->
      <div style="background:#FFFFFF; border-radius:20px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <h3 style="font-family:'Space Grotesk',sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin-bottom:14px;">
          ⚙️ Kartvizit Profilimi Düzenle
        </h3>

        <form method="POST" action="{{ route('staff.card.update') }}">
          @csrf
          <div style="margin-bottom:12px;">
            <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Hakkımda / Biyografi</label>
            <textarea name="bio" rows="3" style="width:100%; border:1px solid #CBD5E1; border-radius:10px; padding:8px 12px; font-size:13px; outline:none; font-family:inherit;">{{ old('bio', $card->bio ?? '') }}</textarea>
          </div>

          <div style="margin-bottom:12px;">
            <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Direkt Telefon</label>
            <input type="text" name="direct_phone" value="{{ old('direct_phone', $card->direct_phone ?? $user->phone) }}" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          </div>

          <div style="margin-bottom:12px;">
            <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">İş E-Postası</label>
            <input type="email" name="work_email" value="{{ old('work_email', $card->work_email ?? $user->email) }}" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          </div>

          <div style="margin-bottom:12px;">
            <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Web Sitesi</label>
            <input type="url" name="website" value="{{ old('website', $card->website ?? $company->website) }}" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          </div>

          <div style="margin-bottom:16px;">
            <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Tema Rengi (HEX)</label>
            <input type="text" name="theme_color" value="{{ old('theme_color', $card->theme_color ?? '#00A86B') }}" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          </div>

          <button type="submit" style="width:100%; height:42px; background:#00A86B; color:#FFF; border:none; border-radius:10px; font-weight:700; font-size:13.5px; cursor:pointer;">
            Profilimi Kaydet
          </button>
        </form>
      </div>

      <!-- Reminders -->
      <div style="background:#FFFFFF; border-radius:20px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
          <h3 style="font-family:'Space Grotesk',sans-serif; font-size:16px; font-weight:700; color:#0F172A; margin:0;">
            ⏰ Hatırlatıcılar & Görevler
          </h3>
        </div>

        <form method="POST" action="{{ route('staff.reminder.store') }}" style="display:flex; gap:8px; margin-bottom:14px;">
          @csrf
          <input type="text" name="title" required placeholder="+ Yeni görev / hatırlatıcı yazın..." style="flex:1; height:38px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          <button type="submit" style="padding:0 14px; background:#0F172A; color:#FFF; border:none; border-radius:10px; font-size:12.5px; font-weight:600; cursor:pointer;">
            Ekle
          </button>
        </form>

        <div style="display:flex; flex-direction:column; gap:8px;">
          @forelse($reminders as $r)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 12px; background:#F8FAFC; border-radius:10px; font-size:13px;">
              <span style="{{ $r->is_completed ? 'text-decoration:line-through; color:#94A3B8;' : 'color:#0F172A;' }}">
                {{ $r->title }}
              </span>
              <form method="POST" action="{{ route('staff.reminder.toggle', $r->id) }}" style="margin:0;">
                @csrf
                <button type="submit" style="background:none; border:none; cursor:pointer; font-size:15px;">
                  {{ $r->is_completed ? '✅' : '⭕' }}
                </button>
              </form>
            </div>
          @empty
            <p style="font-size:13px; color:#94A3B8; text-align:center;">Henüz görev eklenmedi.</p>
          @endforelse
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal: Add Lead -->
<div id="addCustomerModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
  <div style="background:#FFFFFF; border-radius:24px; padding:28px 24px; width:100%; max-width:440px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <h3 style="font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; color:#0F172A; margin:0;">+ Yeni Müşteri Adayı Ekle</h3>
      <button onclick="document.getElementById('addCustomerModal').style.display='none'" style="background:none; border:none; font-size:22px; cursor:pointer; color:#64748B;">&times;</button>
    </div>

    <form method="POST" action="{{ route('staff.customer.store') }}">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Müşteri Ad Soyad *</label>
        <input type="text" name="name" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: Mehmet Öz">
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Şirket Adı</label>
        <input type="text" name="company_name" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: ABC Holding">
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Telefon Numarası</label>
        <input type="text" name="phone" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="+90 5XX XXX XX XX">
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Aşama (Lead Stage)</label>
        <select name="stage" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          <option value="hot">🔥 Sıcak (Hot Lead)</option>
          <option value="warm" selected>🌤️ Ilık (Warm Lead)</option>
          <option value="cold">❄️ Soğuk (Cold Lead)</option>
        </select>
      </div>
      <div style="margin-bottom:16px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Görüşme Notu</label>
        <textarea name="note" rows="2" style="width:100%; border:1px solid #CBD5E1; border-radius:10px; padding:8px 12px; font-size:13px; outline:none;" placeholder="İlk temas detayları..."></textarea>
      </div>

      <button type="submit" style="width:100%; height:44px; background:#00A86B; color:#FFF; border:none; border-radius:12px; font-weight:700; font-size:13.5px; cursor:pointer;">
        CRM Havuzuna Kaydet
      </button>
    </form>
  </div>
</div>

<!-- Modal: Add Meeting -->
<div id="addMeetingModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.6); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:16px;">
  <div style="background:#FFFFFF; border-radius:24px; padding:28px 24px; width:100%; max-width:440px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <h3 style="font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:700; color:#0F172A; margin:0;">+ Yeni Toplantı Planla</h3>
      <button onclick="document.getElementById('addMeetingModal').style.display='none'" style="background:none; border:none; font-size:22px; cursor:pointer; color:#64748B;">&times;</button>
    </div>

    <form method="POST" action="{{ route('staff.meeting.store') }}">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Toplantı Başlığı *</label>
        <input type="text" name="title" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;" placeholder="Örn: Ürün Tanıtım & Demo">
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Toplantı Türü</label>
        <select name="meeting_type" style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
          <option value="meet">Google Meet</option>
          <option value="zoom">Zoom</option>
          <option value="teams">Microsoft Teams</option>
          <option value="physical">Yüz Yüze / Ofis</option>
        </select>
      </div>
      <div style="margin-bottom:16px;">
        <label style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:4px;">Tarih ve Saat *</label>
        <input type="datetime-local" name="start_time" required style="width:100%; height:40px; border:1px solid #CBD5E1; border-radius:10px; padding:0 12px; font-size:13px; outline:none;">
      </div>

      <button type="submit" style="width:100%; height:44px; background:#0284C7; color:#FFF; border:none; border-radius:12px; font-weight:700; font-size:13.5px; cursor:pointer;">
        Takvime Kaydet
      </button>
    </form>
  </div>
</div>
@endsection
