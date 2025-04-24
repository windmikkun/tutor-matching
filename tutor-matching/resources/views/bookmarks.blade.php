@extends('layouts.app')
@section('page_title', 'ブックマーク一覧')
@section('content')
<div class="container" style="max-width:700px; margin:32px auto;">
  <div class="dashboard-box">
    
    @if(isset($bookmarks) && count($bookmarks) > 0)
      @foreach($bookmarks as $item)
        @php
          // ブックマークのタイプによって表示内容を切り替え
          $isJob = isset($item->name) && isset($item->address);
          $isTeacher = isset($item->first_name) && isset($item->last_name);
        @endphp
        @if($isJob)
          @include('components.list_card', [
            'fields' => [
              ['label' => '塾名', 'value' => $item->name],
              ['label' => '住所', 'value' => $item->address],
              ['label' => '説明', 'value' => $item->description],
            ],
            'image' => asset('images/default_company.png'),
            'buttons' => [
              ['label' => '詳細', 'url' => route('job.show', $item->id)]
            ]
          ])
        @elseif($isTeacher)
          @include('components.list_card', [
            'fields' => [
              ['label' => '氏名', 'value' => $item->first_name.' '.$item->last_name],
              ['label' => '得意科目', 'value' => $item->subject],
              ['label' => '学年', 'value' => $item->grade_level],
            ],
            'image' => $item->profile_image ?? asset('images/default_teacher.png'),
            'buttons' => [
              ['label' => '詳細', 'url' => route('teacher.show', ['id' => $item->id])]
            ]
          ])
        @else
          @include('components.list_card', [
            'fields' => [
              ['label' => 'タイトル', 'value' => $item->title ?? 'タイトルなし'],
            ],
            'image' => asset('images/default.png'),
            'buttons' => []
          ])
        @endif
      @endforeach
    @else
      <div class="text-center py-5">
        <div class="text-muted mb-3" style="font-size:1.1rem;">まだブックマークがありません。</div>
        @php $user = Auth::user(); @endphp
        @if($user && method_exists($user, 'isTeacher') && $user->isTeacher())
          <a href="{{ url('/jobs') }}" class="btn-blue-box" style="min-width:220px;">リストを見る</a>
        @elseif($user && method_exists($user, 'isEmployer') && $user->isEmployer())
          <a href="{{ url('/teachers') }}" class="btn-blue-box" style="min-width:220px;">リストを見る</a>
        @else
          <a href="{{ url('/jobs') }}" class="btn-blue-box" style="min-width:220px;">リストを見る</a>
        @endif
      </div>
    @endif
  </div>
</div>
@endsection
