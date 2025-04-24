@extends('layouts.app')
@section('page_title', '講師ダッシュボード')
@section('content')
<style>
.dashboard-box {
  border: none;
  border-radius: 8px;
  background: #fff;
  margin-bottom: 24px;
  padding: 18px 16px 20px 16px;
}
.dashboard-section-title {
  font-size: 1rem;
  font-weight: bold;
  margin-bottom: 10px;
}
.dashboard-menu-list {
  padding-left: 0;
  margin-bottom: 0;
}
.dashboard-menu-list li {
  list-style: none;
  margin-bottom: 16px;
  font-size: 1.1rem;
}
.dashboard-menu-list li:last-child { margin-bottom: 0; }
.dashboard-menu-link {
  color: #222;
  text-decoration: none;
}
.dashboard-menu-link:hover { text-decoration: underline; }
.dashboard-status-table {
  width: 100%;
  margin-top: 16px;
  margin-bottom: 0;
}
.dashboard-status-table td {
  border: none;
  font-size: 1.1rem;
  padding: 4px 0;
}
.dashboard-status-table .label {
  width: 60px;
  text-align: right;
  padding-right: 12px;
}
.dashboard-status-table .value {
  width: 50px;
  text-align: left;
  font-weight: bold;
}
.dashboard-status-table .unit {
  width: 24px;
  text-align: left;
}
.dashboard-status-divider {
  border-top: none;
  margin: 10px 0 14px 0;
}
.dashboard-list-card {
  border: none;
  border-radius: 8px;
  background: #fff;
  margin-bottom: 18px;
  padding: 14px 18px;
  display: flex;
  align-items: center;
  min-height: 48px;
}
.dashboard-list-card .list-title {
  font-size: 1.2rem;
  font-weight: 500;
  margin-right: auto;
}
.dashboard-list-card .list-btn {
  margin-left: 12px;
  background: none;
  border: none;
  outline: none;
  color: #888;
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
@media (max-width: 767px) {
  .dashboard-row-flex { flex-direction: column; }
  .dashboard-row-flex > .col-left, .dashboard-row-flex > .col-right { width: 100%; max-width: 100%; }
}
.dashboard-double-box-parent {
  display: flex;
  flex-direction: row;
  gap: 2%;
  justify-content: center;
  align-items: stretch;
  flex-wrap: wrap;
  max-width: 700px;
  margin: 32px auto 0 auto;
  width: 100%;
  text-align: center;
}
.dashboard-double-box {
  width: 48%;
  min-width: 200px;
  min-height: 260px;
  background: #fff;
  border-radius: 12px;
  margin-bottom: 0;
  box-sizing: border-box;
}
@media (max-width: 767px) {
  .dashboard-double-box-parent {
    flex-direction: column;
  }
  .dashboard-double-box {
    width: 100%;
    margin-bottom: 16px;
  }
}
</style>
<div class="dashboard-double-box-parent">
  <div class="dashboard-double-box">
    <ul class="dashboard-menu-list w-100">
      <li><a href="{{ route('teacher.profile.edit') }}" class="dashboard-menu-link">プロフィール編集</a></li>
      <li><a href="{{ route('teacher.profile.edit') }}" class="dashboard-menu-link">会員情報編集</a></li>
      <li><a href="{{ route('chat') }}" class="dashboard-menu-link">チャット画面</a></li>
      <li><a href="{{ url('/scouts') }}" class="dashboard-menu-link">スカウト一覧</a></li>
      <li><a href="{{ url('/bookmarks') }}" class="dashboard-menu-link">ブックマーク一覧</a></li>
    </ul>
  </div>
  <div class="dashboard-double-box">
    <div class="dashboard-section-title text-center" style="font-size:1.4rem; font-weight:bold;">スカウト状況</div>
    <div class="dashboard-status-divider"></div>
    <div style="display:flex; flex-direction:column; gap:10px; align-items:center; justify-content:center;">
      <div style="display:flex; align-items:center; gap:8px; font-size:1.1rem;">
        <span style="min-width:48px; text-align:center;">新着</span>
        <span style="font-weight:bold; font-size:1.3rem; min-width:32px; text-align:center;">{{ $scoutCounts['new'] ?? 0 }}</span>
        <span style="min-width:24px; text-align:center;">件</span>
      </div>
      <div style="display:flex; align-items:center; gap:8px; font-size:1.1rem;">
        <span style="min-width:48px; text-align:center;">検討中</span>
        <span style="font-weight:bold; font-size:1.3rem; min-width:32px; text-align:center;">{{ $scoutCounts['pending'] ?? 0 }}</span>
        <span style="min-width:24px; text-align:center;">件</span>
      </div>
      <div style="display:flex; align-items:center; gap:8px; font-size:1.1rem;">
        <span style="min-width:48px; text-align:center;">成立</span>
        <span style="font-weight:bold; font-size:1.3rem; min-width:32px; text-align:center;">{{ $scoutCounts['matched'] ?? 0 }}</span>
        <span style="min-width:24px; text-align:center;">件</span>
      </div>
    </div>
  </div>
</div>
<div class="dashboard-box mt-4" style="max-width:700px; margin:24px auto 0 auto; width:100%;">
  <div class="dashboard-section-title">スカウト一覧</div>
  @if(isset($scouts) && count($scouts) > 0)
    @foreach($scouts->take(5) as $scout)
      <div class="dashboard-list-card">
        <span class="list-title">{{ $scout->employer_name ?? $scout->teacher_name ?? 'スカウト' }}</span>
        <a href="{{ route('scout.show', $scout->id) }}" class="list-btn"><i class="bi bi-play-fill"></i></a>
      </div>
    @endforeach
  @else
    <div class="text-muted">現在届いているスカウトはありません</div>
  @endif
</div>
<div class="dashboard-box" style="max-width:700px; margin:24px auto 0 auto; width:100%;">
  <div class="dashboard-section-title">チャット</div>
  @if(isset($latestMessages) && count($latestMessages) > 0)
    @foreach($latestMessages as $chat)
      <div class="dashboard-list-card">
        <span class="list-title">
          {{ $chat->from_id == Auth::id() ? ($chat->toUser->full_name ?? '相手') : ($chat->fromUser->full_name ?? '相手') }}
        </span>
        <a href="{{ route('chat.show', $chat->from_id == Auth::id() ? $chat->to_id : $chat->from_id) }}" class="list-btn"><i class="bi bi-play-fill"></i></a>
      </div>
    @endforeach
  @else
    <div class="text-muted">現在チャットはありません</div>
  @endif
</div>
</div>

  {{-- チャット --}}
  <div class="border rounded p-3 px-4 bg-white" style="max-width:700px; margin:24px auto 0 auto; width:100%;">
    <div class="fw-bold mb-3">チャット</div>
    @if(isset($latestMessages) && count($latestMessages) > 0)
      @foreach($latestMessages as $chat)
        <div class="d-flex align-items-center border rounded mb-3 px-3 py-2 bg-light" style="min-height:56px;">
          <span class="me-auto fw-semibold">
            {{ $chat->from_id == Auth::id() ? ($chat->toUser->full_name ?? '相手') : ($chat->fromUser->full_name ?? '相手') }}
          </span>
          <a href="{{ route('chat.show', $chat->from_id == Auth::id() ? $chat->to_id : $chat->from_id) }}" class="btn btn-outline-secondary btn-sm rounded-circle ms-2" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center;">
            <i class="bi bi-play-fill fs-4"></i>
          </a>
        </div>
      @endforeach
    @else
      <div class="text-muted">現在チャットはありません</div>
    @endif
  </div>
</div>

@if(session('scout_sent'))
<script>alert('スカウトを送信しました！');</script>
@endif
@endsection
