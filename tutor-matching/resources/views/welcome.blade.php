<!doctype html>
<html lang="ja" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FreelanceCowTeacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      html, body {
        height: 100%;
        background: #fff;
      }
      .cover-container {
        max-width: 42em;
      }
      .cow-logo {
        font-size: 5rem;
        line-height: 1;
        margin-bottom: 1rem;
      }
      .cow-pattern {
        position: absolute;
        top: 0; left: 0; width: 100vw; height: 100vh;
        pointer-events: none;
        z-index: 0;
      }
      /* シンプルな牛柄をSVGで作成 */
    </style>
  </head>
  <body class="d-flex h-100 text-center text-bg-light">
    <svg class="cow-pattern" viewBox="0 0 100 100" preserveAspectRatio="none">
      <ellipse cx="15" cy="20" rx="9" ry="6" fill="#222" opacity="0.15"/>
      <ellipse cx="80" cy="40" rx="12" ry="8" fill="#222" opacity="0.13"/>
      <ellipse cx="50" cy="80" rx="14" ry="7" fill="#222" opacity="0.11"/>
      <ellipse cx="70" cy="15" rx="7" ry="4" fill="#222" opacity="0.12"/>
      <ellipse cx="30" cy="60" rx="8" ry="5" fill="#222" opacity="0.14"/>
    </svg>
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column position-relative" style="z-index:1;">
      <header class="mb-auto">
  <div class="d-flex align-items-center justify-content-between w-100">
    <div class="text-start ps-4" style="min-width:220px;">
      <h3 class="mb-0 fw-bold">FreelanceCowTeacher</h3>
    </div>
    <nav class="nav nav-masthead justify-content-end pe-4">
      @auth
@php
  $lastName = Auth::user()->last_name ?? '';
  //$userType = Auth::user()->user_type ?? '';
@endphp

@php
  $dashboardRoute = route('dashboard');
  $dashboardLabel = 'マイページ';
@endphp
  @if($dashboardRoute)
    <a class="nav-link fw-bold py-1 px-0" href="{{ $dashboardRoute }}" title="マイページ" style="cursor:pointer;" target="_self">
      マイページ
    </a>
  @endif
@endauth
@guest
  <a class="nav-link fw-bold py-1 px-0" href="{{ route('login') }}">ログイン</a>
@endguest
    </nav>
  </div>
</header>
      <main class="px-3 my-auto">
        <img src="{{ asset('images/logo.png') }}" alt="CowTeacherロゴ" class="main-cow-logo" aria-label="牛ロゴ" />
<style>
.main-cow-logo {
  display: block;
  margin: 0 auto 24px auto;
  height: 140px;
  width: auto;
  box-shadow: 0 8px 32px rgba(0,0,0,0.10), 0 1.5px 8px rgba(0,0,0,0.07);
  border-radius: 18px;
  background: #222222;
  bacground-opacity: 0.11;
  animation: cow-float 2.5s ease-in-out infinite alternate;
}
@keyframes cow-float {
  0% { transform: translateY(0); }
  100% { transform: translateY(-16px); }
}
</style>
        <h1 class="fw-bold mb-3">FreelanceCowTeacher</h1>
        <p class="lead mb-4">あなたのスキルで、自由に働く。<br>牛のようにのびのびと、教育のフィールドへ。</p>
        <div class="d-grid gap-3 col-8 mx-auto mb-4">
  @auth
    @php
      $user = Auth::user();
      $listUrl = '';
      if (method_exists($user, 'isTeacher') && $user->isTeacher()) {
        $listUrl = url('/jobs');
      } elseif (method_exists($user, 'isEmployer') && $user->isEmployer()) {
        $listUrl = url('/teachers');
      }
    @endphp
    @if($listUrl)
      <a href="{{ $listUrl }}" class="btn btn-black-invert btn-lg fw-bold">リストへ</a>
    @endif
  @endauth
          @guest
<a href="{{ route('register.teacher') }}" class="btn btn-black-invert btn-lg fw-bold">講師として登録</a>
@endguest
<style>
.btn-black-invert {
  background: #222;
  color: #fff;
  border: 2px solid #222;
  transition: background 0.2s, color 0.2s;
}
.btn-black-invert:hover, .btn-black-invert:focus {
  background: #fff;
  color: #222;
  border: 2px solid #222;
  text-decoration: none;
}
</style>
          @guest
<a href="{{ route('register.employer') }}" class="btn btn-white-invert btn-lg fw-bold">講師をお探しの方</a>
@endguest
<style>
.btn-white-invert {
  background: #fff;
  color: #222;
  border: 2px solid #222;
  transition: background 0.2s, color 0.2s;
}
.btn-white-invert:hover, .btn-white-invert:focus {
  background: #222;
  color: #fff;
  border: 2px solid #222;
  text-decoration: none;
}
</style>
        </div>
      </main>
      <footer class="mt-auto text-black-50">
        <p>&copy; 2025 FreelanceCowTeacher</p>
      </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
