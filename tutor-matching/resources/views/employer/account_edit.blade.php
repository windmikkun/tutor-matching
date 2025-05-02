@extends('layouts.app')
@section('content')
<div class="container" style="max-width:600px; margin:32px auto;">
  <div class="card shadow-sm" style="border-radius:1.5rem; background:#fff; padding:32px 24px; height:auto;">
    <div class="mb-5 text-center">
      <h2 class="fw-bold mb-0" style="font-size:2rem;">会員情報編集</h2>
    </div>
    <form method="post" action="{{ route('employer.account.confirm') }}">
      @csrf
      @include('components.profile-edit-form-account', [
        'action' => route('employer.account.confirm'),
        'user' => $user
      ])
      <div class="mt-2 text-center">
        <button type="submit" class="btn-blue-box" style="font-size:1.12rem; min-width:180px;">確認画面へ</button>
      </div>
    </form>

  </div>
</div>
@if (session('status') === 'profile-updated')
  <script>alert('保存が完了しました');</script>
@endif
@endsection
