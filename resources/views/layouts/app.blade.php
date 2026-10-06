<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <title>@yield('title', 'MonaCard - Akıllı Dijital Kartvizit & Kurumsal CRM')</title>
  <meta name="description" content="@yield('description', 'MonaCard Akıllı Dijital Kartvizit ve Kurumsal SaaS Platformu')">
  <meta name="theme-color" content="#00A86B">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts: Space Grotesk & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="{{ asset('style.css') }}?v={{ file_exists(public_path('style.css')) ? filemtime(public_path('style.css')) : time() }}">
  @yield('styles')
</head>
<body class="@yield('body_class', 'role-customer')">

  <!-- Desktop Ambient Glow Background -->
  <div class="desktop-bg-decoration">
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>
  </div>

  @if(session('success'))
    <div style="position:fixed; top:20px; right:20px; z-index:99999; background:rgba(0,168,107,0.95); color:#fff; padding:14px 22px; border-radius:14px; font-weight:600; box-shadow:0 10px 30px rgba(0,0,0,0.3); backdrop-filter:blur(10px); display:flex; align-items:center; gap:10px; animation:slideDown 0.4s ease;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
      <span>{{ session('success') }}</span>
      <button onclick="this.parentElement.remove()" style="background:none; border:none; color:#fff; font-size:18px; cursor:pointer; margin-left:8px;">&times;</button>
    </div>
  @endif

  @if(session('info'))
    <div style="position:fixed; top:20px; right:20px; z-index:99999; background:rgba(30,41,59,0.95); color:#fff; padding:14px 22px; border-radius:14px; font-weight:600; box-shadow:0 10px 30px rgba(0,0,0,0.3); backdrop-filter:blur(10px); display:flex; align-items:center; gap:10px;">
      <span>ℹ️ {{ session('info') }}</span>
      <button onclick="this.parentElement.remove()" style="background:none; border:none; color:#fff; font-size:18px; cursor:pointer; margin-left:8px;">&times;</button>
    </div>
  @endif

  @if(isset($errors) && $errors->any())
    <div style="position:fixed; top:20px; right:20px; z-index:99999; background:rgba(239,68,68,0.95); color:#fff; padding:14px 22px; border-radius:14px; font-weight:600; box-shadow:0 10px 30px rgba(0,0,0,0.3); backdrop-filter:blur(10px); max-width:400px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
        <strong>⚠️ Dikkat</strong>
        <button onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; color:#fff; font-size:18px; cursor:pointer;">&times;</button>
      </div>
      <ul style="margin:0; padding-left:18px; font-size:13px;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @yield('content')

  @yield('scripts')
</body>
</html>
