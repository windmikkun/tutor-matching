@extends('layouts.app')
@section('content')
<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center" style="background:transparent; padding-top:60px;">
    <div class="card shadow bg-white mx-auto" style="width:100%; max-width:600px; border-radius:1.5rem; overflow:hidden; margin-top:40px; margin-bottom:40px;">
        <div class="card-body p-5">
            <div class="mb-5 text-center">
                <h2 class="fw-bold mb-0" style="font-size:2rem;">講師プロフィール編集</h2>
            </div>
            <div class="custom-form-center">
<form method="POST" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data">
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
      <label for="first_name" class="custom-form-label">姓</label>
      <input type="text" class="custom-form-input" id="first_name" name="first_name" value="{{ old('first_name', $teacher->first_name ?? '') }}" required>
    </div>
    <div class="custom-form-group">
      <label for="last_name" class="custom-form-label">名</label>
      <input type="text" class="custom-form-input" id="last_name" name="last_name" value="{{ old('last_name', $teacher->last_name ?? '') }}" required>
    </div>
    <div class="custom-form-group">
      <label for="subject" class="custom-form-label">得意科目</label>
      <input type="text" class="custom-form-input" id="subject" name="subject" value="{{ old('subject', $teacher->subject ?? '') }}">
    </div>
    <div class="custom-form-group">
      <label for="grade_level" class="custom-form-label">学年</label>
      <input type="text" class="custom-form-input" id="grade_level" name="grade_level" value="{{ old('grade_level', $teacher->grade_level ?? '') }}">
    </div>
    <div class="custom-form-group">
      <label for="bio" class="custom-form-label">自己紹介</label>
      <textarea class="custom-form-input" id="bio" name="bio" rows="3">{{ old('bio', $teacher->bio ?? '') }}</textarea>
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
