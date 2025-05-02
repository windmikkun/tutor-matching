@extends('layouts.app')
@section('content')
<div class="container" style="max-width:600px; margin:32px auto;">
  <div class="card shadow-sm" style="border-radius:1.5rem; background:#fff;">
    <div class="mb-5 text-center">
      <h2 class="fw-bold mb-0" style="font-size:2rem; color:#2563eb;">プロフィール更新完了</h2>
    </div>
    <div class="alert alert-success text-center" style="font-size:1.15rem;">
      プロフィール情報が正常に更新されました。
    </div>
    <div class="text-center" style="margin:2.2rem 0 1.2rem 0;">
      <a href="{{ route('teacher.account.edit') }}" class="btn-blue-box" style="font-size:1.12rem; min-width:180px;">会員情報編集に進む</a>
      <a href="{{ route('dashboard') }}" class="btn-blue-box" style="font-size:1.12rem; min-width:180px; margin-left:16px;">ダッシュボードへ</a>
    </div>
  </div>
</div>
@endsection
