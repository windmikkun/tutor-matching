@extends('layouts.app')
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
.dashboard-list-card-link {
  display: flex;
  align-items: center;
  justify-content: space-between;
  text-decoration: none;
  color: inherit;
  transition: background 0.15s;
  border-radius: 8px;
  background: #fff;
  margin-bottom: 18px;
  padding: 14px 18px;
  min-height: 48px;
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
.dashboard-list-card-link {
  cursor: pointer;
  text-decoration: none;
}
.dashboard-list-card-link:hover {
  background: #f2f4f8;
  color: #007bff;
}
.dashboard-list-card-link:hover .list-btn {
  color: #007bff;
}
.dashboard-list-card-link:hover .list-btn {
  color: #007bff;
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
</style>
<style>
.dashboard-double-box-parent {
  max-width: 700px;
  margin: 32px auto 0 auto;
  width: 100%;
  text-align: center;
}
.dashboard-double-box {
  display: inline-block;
  width: 49.5%;
  min-width: 200px;
  min-height: 260px;
  vertical-align: top;
  background: #fff;
  border-radius: 12px;
  margin-bottom: 0;
  margin-right: 1%;
  box-sizing: border-box;
}
.dashboard-double-box:last-child {
  margin-right: 0;
}
@media (max-width: 767px) {
  .dashboard-double-box {
    display: block;
    width: 100%;
    margin-right: 0;
    margin-bottom: 16px;
  }
}
</style>
<div class="dashboard-double-box-parent" style="display: flex; gap: 2%; justify-content: center; align-items: stretch; flex-wrap: wrap;">

  <div class="dashboard-double-box" style="padding:32px 24px; text-align:left; width:48%; min-width:240px; box-sizing:border-box;">
    <ul class="dashboard-menu-list w-100">
      <li><a href="{{ route('employer.profile.edit') }}" class="dashboard-menu-link">プロフィール編集</a></li>
<li><a href="{{ route('employer.account.edit') }}" class="dashboard-menu-link">会員情報編集</a></li>
            <li><a href="{{ route('chat') }}" class="dashboard-menu-link">チャット画面</a></li>
      <li><a href="{{ route('teacher.list') }}" class="dashboard-menu-link">講師リスト</a></li>
      <li><a href="{{ url('/entries/applicants') }}" class="dashboard-menu-link">応募者一覧</a></li>
      <li><a href="{{ url('/bookmarks') }}" class="dashboard-menu-link">ブックマーク一覧</a></li>
    </ul>
  </div>
  <div class="dashboard-double-box" style="padding:32px 24px; display:flex; flex-direction:column; justify-content:center; align-items:center; width:48%; min-width:240px; box-sizing:border-box;">
    <div class="dashboard-section-title text-center" style="margin-bottom:8px; font-size:1.4rem; font-weight:bold;">求人募集状況</div>
    <div class="dashboard-status-divider"></div>
        <div style="display:flex; flex-direction:column; gap:10px; align-items:center; justify-content:center;">
          <div style="display:flex; align-items:center; gap:8px; font-size:1.1rem;">
            <span style="min-width:64px; text-align:center;">未対応</span>
            <span style="font-weight:bold; font-size:1.3rem; min-width:32px; text-align:center;">
                {{ ($scoutCounts['new'] ?? 0) + ($scoutCounts['pending'] ?? 0) }}
            </span>
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
  {{-- 上部：プロフィール＋スカウト状況 --}}
  <div class="dashboard-box mt-4" style="max-width:700px; margin:24px auto 0 auto; width:100%;">
    <div class="dashboard-section-title">応募者一覧</div>
    @if(isset($entries) && count($entries) > 0)
      @foreach($entries as $entry)
        @if($entry->user && $entry->user->teacher)
          <a href="{{ route('teacher.show', $entry->user->teacher->id) }}" class="dashboard-list-card dashboard-list-card-link">
            <span class="list-title">
              {{ ($entry->user->teacher->last_name ?? '') . ' ' . ($entry->user->teacher->first_name ?? '') }}
            </span>
            <span class="list-btn"><i class="bi bi-play-fill"></i></span>
          </a>
        @else
          <span class="dashboard-list-card dashboard-list-card-link text-muted">講師情報がありません</span>
        @endif
      @endforeach
    @else
      <div class="text-muted">現在応募してきた講師はいません</div>
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
        </div>
      @endforeach
    @else
      <div class="text-muted">現在チャットはありません</div>
    @endif
  </div>
</div>
@endsection
