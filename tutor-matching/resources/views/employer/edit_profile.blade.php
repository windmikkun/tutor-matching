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
      <label for="first_name" class="custom-form-label">会社名・事業所名</label>
      <input type="text" class="custom-form-input" id="first_name" name="first_name" value="{{ old('first_name', $employer->first_name ?? '') }}">
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
      <progress class="upload-progress" value="0" max="100" style="width:100%;margin-top:10px;"></progress>
      @php
        use Illuminate\Support\Facades\Storage;
        $profileImageUrl = null;
        if ($employer && $employer->profile_image && Storage::disk('public')->exists($employer->profile_image)) {
            $profileImageUrl = Storage::url($employer->profile_image);
        }
      @endphp
      <img class="profile-image-preview" src="{{ $profileImageUrl ?? asset('images/default.png') }}" alt="プレビュー" style="max-width:200px;max-height:200px;display:block;margin-top:10px;">
    </div>
    <hr style="margin:2rem 0;">
    <div class="custom-form-group">
      <label class="custom-form-label">指導形態</label>
      <select class="custom-form-input" name="lesson_type">
        <option value="" disabled selected>選択してください</option>
        <option value="個別" {{ old('lesson_type', $employer->lesson_type ?? '') == '個別' ? 'selected' : '' }}>個別指導</option>
        <option value="集団" {{ old('lesson_type', $employer->lesson_type ?? '') == '集団' ? 'selected' : '' }}>集団指導</option>
      </select>
    </div>
    <div class="custom-form-group">
      <label for="student_count" class="custom-form-label">生徒数</label>
      <input type="number" class="custom-form-input" id="student_count" name="student_count" value="{{ old('student_count', $employer->student_count ?? '') }}" min="0">
    </div>
    <div class="custom-form-group">
      <label for="student_demographics" class="custom-form-label">生徒層</label>
      <input type="text" class="custom-form-input" id="student_demographics" name="student_demographics" value="{{ old('student_demographics', $employer->student_demographics ?? '') }}">
    </div>
    <div class="custom-form-group">
      <label for="hourly_rate" class="custom-form-label">時給（円）</label>
      <input type="number" class="custom-form-input" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $employer->hourly_rate ?? '') }}" min="0">
    </div>
    <div class="custom-form-group">
      <label class="custom-form-label">教室・施設等の画像（最大3枚）</label>
      <input type="file" class="custom-form-input" name="env_img[]" multiple accept="image/*">
      <div style="display:flex;gap:10px;margin-top:10px;">
        @if($employer && $employer->env_img)
          @foreach(json_decode($employer->env_img, true) as $img)
            @php
              $imgUrl = null;
              if ($img && Storage::disk('public')->exists($img)) {
                $imgUrl = Storage::url($img);
              }
            @endphp
            <img src="{{ $imgUrl ?? asset('images/default.png') }}" style="max-width:120px;max-height:120px;border-radius:8px;">
          @endforeach
        @endif
      </div>
    </div>
    <button type="submit" class="custom-submit-btn">保存</button>
</form>
</div>
        </div>
    </div>
</div>
@endsection
