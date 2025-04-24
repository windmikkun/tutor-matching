@extends('layouts.app')
@section('content')
<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center" style="background:transparent; padding-top:60px;">
    <div class="card shadow bg-white mx-auto" style="width:100%; max-width:600px; border-radius:1.5rem; overflow:hidden; margin-top:40px; margin-bottom:40px;">
        <div class="card-body p-5">
            <div class="mb-5 text-center">
                <h2 class="fw-bold mb-0" style="font-size:2rem;">雇用者プロフィール編集</h2>
            </div>
            <div class="custom-form-center">
<form method="POST" action="{{ route('employer.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
        background: #6c757d;
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
        background: #495057;
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
      <label for="name" class="custom-form-label">会社名・事業所名</label>
      <input type="text" class="custom-form-input" id="name" name="name" value="{{ old('name', $employer->name ?? '') }}" required>
    </div>
    <div class="custom-form-group">
      <label for="contact_person" class="custom-form-label">担当者名</label>
      <input type="text" class="custom-form-input" id="contact_person" name="contact_person" value="{{ old('contact_person', $employer->contact_person ?? '') }}">
    </div>
    <div class="custom-form-group">
      <label for="phone" class="custom-form-label">電話番号</label>
      <input type="tel" class="custom-form-input" id="phone" name="phone" value="{{ old('phone', $employer->phone ?? '') }}">
    </div>
    <div class="custom-form-group">
      <label for="address" class="custom-form-label">住所</label>
      <input type="text" class="custom-form-input" id="address" name="address" value="{{ old('address', $employer->address ?? '') }}">
    </div>
    <div class="custom-form-group">
      <label for="description" class="custom-form-label">事業内容・説明</label>
      <textarea class="custom-form-input" id="description" name="description" rows="3">{{ old('description', $employer->description ?? '') }}</textarea>
    </div>
    <div class="custom-form-group">
      <label for="profile_image" class="custom-form-label">プロフィール画像</label>
      <input type="file" class="custom-form-input" id="profile_image" name="profile_image">
    </div>
    <button type="submit" class="custom-submit-btn">保存</button>
</form>
</div>
        </div>
    </div>
</div>
@endsection
