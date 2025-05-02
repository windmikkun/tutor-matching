@extends('layouts.app')
@section('content')
<div class="container" style="max-width:600px; margin:32px auto;">
  <div class="card shadow-sm" style="border-radius:1.5rem; background:#fff;">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <form method="post" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data" class="text-center my-4">
      @csrf

      <div class="mb-3 text-center">
        <label for="subject" class="form-label w-100">得意科目</label>
        <input id="subject" type="text" name="subject" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" value="{{ old('subject', $teacher->subject ?? '') }}">
        @error('subject')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3 text-center">
        <label for="grade_level" class="form-label w-100">学年</label>
        <input id="grade_level" type="text" name="grade_level" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" value="{{ old('grade_level', $teacher->grade_level ?? '') }}">
        @error('grade_level')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3 text-center">
        <label for="bio" class="form-label w-100">自己紹介</label>
        <textarea id="bio" name="bio" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" rows="2">{{ old('bio', $teacher->bio ?? '') }}</textarea>
        @error('bio')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3 text-center">
        <label for="self_appeal" class="form-label w-100">自己アピール</label>
        <textarea id="self_appeal" name="self_appeal" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" rows="3">{{ old('self_appeal', $teacher->self_appeal ?? '') }}</textarea>
        @error('self_appeal')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3 text-center">
        <label for="trial_lesson" class="form-label w-100">プレ授業 (Youtube動画URL)</label>
        <input id="trial_lesson" type="url" name="trial_lesson" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" value="{{ old('trial_lesson', $teacher->trial_lesson ?? '') }}" placeholder="https://www.youtube.com/watch?v=xxxxxxx">
        @error('trial_lesson')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3 text-center">
        <label for="estimated_hourly_rate" class="form-label w-100">希望時給（円）</label>
        <input id="estimated_hourly_rate" type="number" name="estimated_hourly_rate" class="form-control mt-1" style="border-radius:10px; max-width:400px; margin:0 auto; display:block;" value="{{ old('estimated_hourly_rate', $teacher->estimated_hourly_rate ?? '') }}" min="0">
        @error('estimated_hourly_rate')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      <div class="mt-4 text-center">
        <button type="submit" class="btn-blue-box" style="font-size:1.12rem; min-width:180px;">保存</button>
      </div>
    </form>
  </div>
</div>
@endsection
