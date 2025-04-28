@extends('layouts.app')
@section('page_title', 'プロフィール編集')
@section('content')
<div class="container" style="max-width:600px; margin:32px auto;">
  <div class="dashboard-box">
    <div class="dashboard-section-title">プロフィール編集</div>
      @include('components.profile-edit-form', [
        'action' => route('teacher.profile.update'),
        'user' => $user,
        'teacher' => $teacher,
        'method_field' => 'PUT'
      ])
  </div>
</div>
@endsection
