@extends('layouts.app')
@section('content')
<div class="container" style="max-width:600px; margin:32px auto;">
  <div class="card shadow-sm" style="border-radius:1.5rem; background:#fff;">
  <div class="mb-5 text-center">
    <h2 class="fw-bold mb-0" style="font-size:2rem;">会員情報編集</h2>
  </div>
    @include('components.profile-edit-form-account', [
      'action' => route('teacher.account.update'),
      'user' => $user,
      'method_field' => 'PATCH'
    ])
  </div>
</div>
@endsection
