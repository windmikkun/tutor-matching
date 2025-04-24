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
      @if(!empty($employer->profile_image))
        <div class="my-3">
          <img src="{{ asset('storage/' . $employer->profile_image) }}" alt="プロフィール画像" class="img-thumbnail" style="max-width:200px;">
        </div>
      @endif
    </div>
  </div>
  <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-secondary">編集する</a>
</div>
@endsection
