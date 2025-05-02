@extends('layouts.app')
@section('content')
<div class="container" style="max-width:600px; margin:32px auto;">
  <div class="card shadow-sm" style="border-radius:1.5rem; background:#fff;">
  <div class="mb-5 text-center">
    <h2 class="fw-bold mb-0" style="font-size:2rem;">会員情報編集</h2>
  </div>
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
    <form method="post" action="{{ route('teacher.account.confirm') }}" class="text-center my-4">
      @csrf
      @include('components.profile-edit-form-account', [
        'action' => route('teacher.profile.update'),
        'user' => $user
      ])
      <button type="submit" class="btn-blue-box" style="margin-bottom:48px;display:inline-block;">確認画面へ</button>
    </form>
  </div>
</div>
@if (session('status') === 'profile-updated')
  <script>alert('保存が完了しました');</script>
@endif
@endsection
