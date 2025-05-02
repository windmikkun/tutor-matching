@extends('layouts.app')
@section('content')
<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center" style="background:transparent; padding-top:60px;">
    <div class="card shadow bg-white mx-auto" style="width:100%; max-width:600px; border-radius:1.5rem; overflow:hidden; margin-top:40px; margin-bottom:40px;">
        <div class="card-body p-5">
            <div class="mb-5 text-center">
                <h2 class="fw-bold mb-0" style="font-size:2rem;">求人者として新規登録</h2>
            </div>
            <div class="custom-form-center">
<form method="POST" action="{{ route('register') }}">
    @csrf
    <input type="hidden" name="user_type" value="employer">
    <style>
      .custom-form-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin-bottom: 1.5rem;
      }
      .custom-form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 1rem;
      }
      .custom-form-input {
        width: 100%;
        max-width: 340px;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid #bfc3c9;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.2s;
      }
      .custom-form-input:focus {
        border-color: #4a90e2;
        outline: none;
        background: #fafdff;
      }
      .custom-submit-btn {
        width: 100%;
        max-width: 340px;
        padding: 0.7rem 0;
        background: #3498db;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: bold;
        letter-spacing: 0.05em;
        margin-top: 0.5rem;
        transition: background 0.2s;
      }
      .custom-submit-btn:hover {
        background: #2176bd;
      }
    </style>
    <style>
      .custom-form-center {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }
    </style>


                <div class="custom-form-group">
  <label for="last_name" class="custom-form-label">会社名・事業所名</label>
  <input type="text" class="custom-form-input" id="last_name" name="last_name" required autofocus placeholder="例）株式会社サンプル">
</div>
<div class="custom-form-group">
  <label for="first_name" class="custom-form-label">担当者名</label>
  <input type="text" class="custom-form-input" id="first_name" name="first_name" required placeholder="例）山田太郎">
</div>
                <div class="custom-form-group">
  <label for="email" class="custom-form-label">メールアドレス</label>
  <input type="email" class="custom-form-input" id="email" name="email" required placeholder="例）sample@example.com">
</div>
                <div class="custom-form-group">
  <label for="password" class="custom-form-label">パスワード</label>
  <input type="password" class="custom-form-input" id="password" name="password" required placeholder="半角英数字8文字以上">
</div>
                <div class="custom-form-group">
  <label for="password_confirmation" class="custom-form-label">パスワード（確認用）</label>
  <input type="password" class="custom-form-input" id="password_confirmation" name="password_confirmation" required placeholder="もう一度入力">
</div>


                <button type="submit" class="custom-submit-btn">登録</button>
    </div>
            </form>
</div>
        </div>
    </div>
</div>
@endsection
