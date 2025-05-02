@extends('layouts.app')
@section('page_title', '講師ダッシュボード')
@section('content')
<style>
.dashboard-list-card-link {
  display: flex;
  align-items: center;
  justify-content: space-between;
  text-decoration: none;
  color: inherit;
  transition: background 0.15s;
}
.dashboard-list-card-link:hover {
  background: #f2f4f8;
  cursor: pointer;
  color: #007bff;
}
.dashboard-list-card-link .list-btn {
  color: #888;
  transition: color 0.15s;
}
.dashboard-list-card-link:hover .list-btn {
  color: #007bff;
}
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
      <li><a href="{{ route('teacher.account.edit') }}" class="dashboard-menu-link">会員情報編集</a></li>
      <li><a href="{{ route('chat') }}" class="dashboard-menu-link">チャット画面</a></li>

      <li><a href="{{ url('/entries') }}" class="dashboard-menu-link">応募済一覧</a></li>
      <li><a href="{{ url('/bookmarks') }}" class="dashboard-menu-link">ブックマーク一覧</a></li>
    </ul>
  </div>
  <div class="dashboard-double-box" style="height:100%; min-height:260px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
    <div style="flex:1; width:100%; display:flex; flex-direction:column; align-items:center; justify-content:center;">
      <div class="dashboard-section-title text-center" style="font-size:1.4rem; font-weight:bold; margin-bottom:18px;">スカウト状況</div>
      <div class="dashboard-status-divider" style="margin-bottom:18px;"></div>
      <div style="display:flex; flex-direction:column; gap:32px; align-items:center; justify-content:center; width:100%;">
         <div style="display:flex; align-items:center; gap:14px; font-size:1.15rem;">
           <span style="min-width:64px; text-align:center;">未対応</span>
           <span style="font-weight:bold; font-size:1.6rem; min-width:36px; text-align:center;">
               {{ $scoutCounts['unresponded'] ?? 0 }}
           </span>
           <span style="min-width:24px; text-align:center;">件</span>
         </div>
         <div style="display:flex; align-items:center; gap:14px; font-size:1.15rem;">
           <span style="min-width:64px; text-align:center;">スカウト総数</span>
           <span style="font-weight:bold; font-size:1.6rem; min-width:36px; text-align:center;">
               {{ $scoutCounts['total'] ?? 0 }}
           </span>
           <span style="min-width:24px; text-align:center;">件</span>
         </div>
      </div>
    </div>
  </div>
</div>
<div class="dashboard-box mt-4" style="max-width:700px; margin:24px auto 0 auto; width:100%;">
  <div class="dashboard-section-title">スカウト一覧</div>
  @if(isset($scouts) && count($scouts) > 0)
    @foreach($scouts as $scout)
      <a href="{{ route('employer.show', $scout->employer_id) }}" class="dashboard-list-card dashboard-list-card-link">
        <span class="list-title">{{ $scout->employer_name }}</span>
        <span class="list-btn"><i class="bi bi-play-fill"></i></span>
      </a>
    @endforeach
  @else
    <div class="text-muted">現在届いているスカウトはありません</div>
  @endif
</div>
<div class="dashboard-box" style="max-width:700px; margin:24px auto 0 auto; width:100%;">
  <div class="dashboard-section-title">チャット</div>
  @if(isset($latestMessages) && count($latestMessages) > 0)
    @foreach($latestMessages as $chat)
      <a href="{{ route('chat.show', $chat->from_id == Auth::id() ? $chat->to_id : $chat->from_id) }}" class="dashboard-list-card dashboard-list-card-link">
        <span class="list-title">
          {{ $chat->target_full_name ?? '相手' }}
        </span>
        <span class="list-btn"><i class="bi bi-play-fill"></i></span>
      </a>
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
