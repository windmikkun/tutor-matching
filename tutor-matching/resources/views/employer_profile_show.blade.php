@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h2 class="mb-4">塾プロフィール</h2>
  <div class="card mb-3">
    <div class="card-body">
      <h4 class="card-title">{{ $employer->company_name ?? $employer->name }}</h4>
      <p class="card-text mb-1"><strong>メールアドレス:</strong> {{ $user->email }}</p>
      @if(!empty($employer->contact_person))
        <p class="card-text mb-1"><strong>担当者:</strong> {{ $employer->contact_person }}</p>
      @endif
      @if(!empty($employer->phone))
        <p class="card-text mb-1"><strong>電話番号:</strong> {{ $employer->phone }}</p>
      @endif
      @if(!empty($employer->address))
        <p class="card-text mb-1"><strong>住所:</strong> {{ $employer->address }}</p>
      @endif
      @if(!empty($employer->description))
        <p class="card-text mb-1"><strong>説明:</strong> {{ $employer->description }}</p>
      @endif
      @php
        use Illuminate\Support\Facades\Storage;
        $profileImageUrl = null;
        if (!empty($employer->profile_image) && Storage::disk('public')->exists($employer->profile_image)) {
            $profileImageUrl = Storage::url($employer->profile_image);
        } elseif (!empty($employer->env_img)) {
            $imgs = json_decode($employer->env_img, true);
            if (!empty($imgs) && isset($imgs[0]) && Storage::disk('public')->exists($imgs[0])) {
                $profileImageUrl = Storage::url($imgs[0]);
            }
        }
      @endphp
      <div class="my-3 text-center">
        <img src="{{ $profileImageUrl ?? asset('images/default.png') }}" alt="プロフィール画像" style="width:80px; height:80px; object-fit:cover; border-radius:50%; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
      </div>
      @if(!empty($employer->env_img))
        <div class="mb-3">
          <div>教室・施設等の画像</div>
          <div style="display:flex;gap:10px;">
            @foreach(json_decode($employer->env_img, true) as $img)
              @php
                $imgUrl = null;
                if ($img && Storage::disk('public')->exists($img)) {
                  $imgUrl = Storage::url($img);
                }
              @endphp
              <img src="{{ $imgUrl ?? asset('images/default.png') }}" alt="教室画像" class="img-thumbnail" style="max-width:120px;max-height:120px;border-radius:8px;">
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
  <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-secondary">編集する</a>
</div>
@endsection
