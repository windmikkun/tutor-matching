@extends('layouts.app')
@section('content')
<div class="container py-4">
  @include('components.image_upload_form', ['currentImage' => $employer->profile_image ?? $user->profile_image ?? null])
  <h2 class="mb-4">会員情報編集（求人用）</h2>
  <form method="POST" action="#">
    @csrf
    <div class="mb-3">
      <label class="form-label">会社名/事業者名（任意）</label>
      <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $employer->company_name ?? $user->name) }}">
      @error('company_name')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
    </div>
    <div class="mb-3">
      <label class="form-label">担当者名</label>
      <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name) }}">
      @error('last_name')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
    </div>
    <div class="mb-3">
      <label class="form-label">メールアドレス</label>
      <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
    </div>
    <!-- 必要に応じて他の項目も追加 -->
    <button type="submit" class="btn btn-primary">保存</button>
  </form>
</div>
<script src="/js/image_upload.js"></script>
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>
@endsection
