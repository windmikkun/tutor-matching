<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
.toast-notification {
  position: fixed;
  top: 32px;
  right: 32px;
  z-index: 9999;
  background: #3490dc;
  color: #fff;
  padding: 16px 32px;
  border-radius: 8px;
  font-size: 1.1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.12);
  opacity: 0;
  transition: opacity 0.4s;
}
.toast-notification.show {
  opacity: 1;
}
</style>
</head>
    <body>

@if (session('status') === 'profile-updated')
  <div id="toast" class="toast-notification">保存が完了しました</div>
  <script>
    window.addEventListener('DOMContentLoaded', function() {
      var toast = document.getElementById('toast');
      if (toast) {
        setTimeout(function() { toast.classList.add('show'); }, 100);
        setTimeout(function() { toast.classList.remove('show'); }, 3200);
      }
    });
  </script>
@endif
        <div class="min-h-screen bg-gray-100">
            <header class="bg-white shadow" style="position:fixed; top:0; left:0; width:100vw; z-index:2100;">
                @include('layouts.navigation')

            </header>

            <!-- Page Content -->
            <main style="padding-top:64px; padding-bottom:64px;">
                @yield('content')
            </main>
            <!-- 共通フッターメニュー -->
            @if(Auth::check())
            <style>
              .footer-menu-bar {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100vw;
  background: #fff;
  box-shadow: 0 -2px 16px rgba(0,0,0,0.07);
  border-top: 1px solid #e3e6e8;
  z-index: 2200;
  min-height: 54px;
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: flex-end;
  gap: 32px;
}
              .footer-menu-btn {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border: none;
                background: none;
                outline: none;
                width: 54px;
                height: 54px;
                border-radius: 50%;
                margin: 0 10px;
                transition: background 0.15s;
                color: #1976d2;
                font-weight: 500;
                text-decoration: none;
                padding: 0;
              }
              .footer-menu-btn.active {
  color: #1565c0;
  text-decoration: none;
}
.footer-menu-btn:focus,
.footer-menu-btn:hover {
  background: #e3f0fc;
  color: #1565c0;
  text-decoration: none;
}
              .footer-menu-icon {
                font-size: 1.4rem;
                margin-bottom: 0px;
                display: block;
              }
              .footer-menu-label {
                font-size: 0.82rem;
                letter-spacing: 0.01em;
                text-align: center;
                margin-top: 2px;
                color: #1976d2;
              }
              @media (max-width: 600px) {
  .footer-menu-bar { min-height: 46px; }
  .footer-menu-btn { width: 36px; height: 36px; margin: 0 6px; }
  .footer-menu-label { font-size: 0.72rem; }
}
            </style>
            <nav class="footer-menu-bar">
              <a href="{{ route('dashboard') }}" class="footer-menu-btn @if(Route::currentRouteName() == 'dashboard') active @endif" aria-label="ダッシュボード">
                <span class="footer-menu-icon"><i class="bi bi-house-door"></i></span>
              </a>
              <a href="/chatify" class="footer-menu-btn @if(Request::is('chatify')) active @endif" aria-label="チャット">
                <span class="footer-menu-icon"><i class="bi bi-chat-dots"></i></span>
              </a>
              @php($user = Auth::user())
              @if($user && isset($user->teacher) && $user->teacher)
                <a href="{{ url('/list') }}" class="footer-menu-btn @if(Request::is('list')) active @endif" aria-label="リスト">
                  <i class="bi bi-list-ul" style="font-size:1.6rem;"></i>
                </a>
              @elseif($user && isset($user->employer) && $user->employer)
                <a href="{{ route('teacher.list') }}" class="footer-menu-btn @if(Route::currentRouteName() == 'teacher.list') active @endif" aria-label="講師リスト">
                  <i class="bi bi-list-ul" style="font-size:1.6rem;"></i>
                </a>
              @endif
            </nav>
            @endif
        </div>
    <script src="/js/image_upload.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>
    </body>
</html>
