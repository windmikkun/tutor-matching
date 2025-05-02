@extends('layouts.app')
@section('page_title', '応募済一覧')
@section('content')
<div class="container" style="max-width:700px; margin:32px auto;">
  <div class="dashboard-box">
    <div class="dashboard-section-title">応募済一覧</div>
    @php
      $entries = \App\Models\Entry::where('user_id', Auth::id())->orderByDesc('created_at')->get();
    @endphp
    @if($entries->count() > 0)
      @foreach($entries as $entry)
        @php
          $job = $entry->employer;
        @endphp
        @include('components.list_card', [
          'fields' => [
            ['label' => '塾名', 'value' => $job->first_name ?? '塾名未設定'],
            ['label' => '都道府県', 'value' => optional($job->user)->prefecture ?? '未設定'],
            ['label' => '市区町村', 'value' => optional($job->user)->address1 ?? '未設定'],
            ['label' => '説明', 'value' => $job->description ?? '説明未設定'],
          ],
          'image' => ($job->profile_image ?? null) ? $job->profile_image : (($job->env_img && count(json_decode($job->env_img, true)) > 0) ? json_decode($job->env_img, true)[0] : asset('images/default.png')),
          'buttons' => [
            ['label' => '詳細', 'url' => route('job.show', ['id' => $job->id])],
            
            ['label' => '応募済', 'disabled' => true]
          ]
        ])
      @endforeach
    @else
      <div class="text-muted">応募済みの求人はありません</div>
    @endif
  </div>
</div>
@endsection
