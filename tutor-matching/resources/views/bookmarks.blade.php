@extends('layouts.app')
@section('page_title', 'ブックマーク一覧')
@section('content')
<div class="container" style="max-width:700px; margin:32px auto;">
  <div class="dashboard-box">
    
    @php
      $userType = Auth::user()->user_type ?? '';
    @endphp
    @if($userType === 'teacher')
      @if(isset($employers) && count($employers) > 0)
        @foreach($employers as $employer)
          @include('components.list_card', [
            'fields' => [
              ['label' => '塾名', 'value' => $employer->name],
              ['label' => '担当者', 'value' => $employer->contact_person ?? '-'],
              ['label' => '説明', 'value' => $employer->description ?? '-'],
            ],
            'image' => $employer->profile_image ?? asset('images/default_company.png'),
            'buttons' => [
              ['label' => '詳細', 'url' => route('employer.show', ['id' => $employer->id])]
            ]
          ])
        @endforeach
      @else
        <div class="text-center py-5">
          <div class="text-muted mb-3" style="font-size:1.1rem;">まだブックマークがありません。</div>
          <a href="{{ url('/jobs') }}" class="btn-blue-box" style="min-width:220px;">リストを見る</a>
        </div>
      @endif
    @else
      @if(isset($teachers) && count($teachers) > 0)
        @php
          $user = Auth::user();
          $currentUserBookmarks = isset($user) ? $user->bookmarks->where('bookmarkable_type', 'teacher') : collect();
        @endphp
        @foreach($teachers as $item)
          @php
            $isBookmarked = $currentUserBookmarks->where('bookmarkable_id', $item->id)->isNotEmpty();
            $bookmarkCount = $item->bookmarkedByEmployers->count() ?? 0;
          @endphp
          @include('components.list_card', [
            'fields' => [
              ['label' => '氏名', 'value' => $item->last_name.' '.$item->first_name],
              ['label' => '都道府県', 'value' => optional($item->user)->prefecture ?? '未設定'],
              ['label' => '市区町村', 'value' => optional($item->user)->address1 ?? '未設定'],
              ['label' => '得意科目', 'value' => $item->subject],
              ['label' => '学年', 'value' => $item->grade_level],
              ['label' => '自己紹介', 'value' => Str::limit($item->bio, 60)],
            ],
            'image' => $item->profile_image ?? asset('images/default.png'),
            'buttons' => [
              ['label' => '詳細', 'url' => route('teacher.show', ['id' => $item->id])],
              ['label' => 'スカウト', 'url' => route('scout.create', $item->id)]
            ],
            'bookmarkButton' => view('components.bookmark_button', [
              'isBookmarked' => $isBookmarked,
              'type' => 'teacher',
              'id' => $item->id,
              'bookmarkCount' => $bookmarkCount,
              'currentUserBookmarks' => $currentUserBookmarks
            ])->render()
          ])
        @endforeach
      @else
        <div class="text-center py-5">
          <div class="text-muted mb-3" style="font-size:1.1rem;">まだブックマークがありません。</div>
          <a href="{{ url('/teachers') }}" class="btn-blue-box" style="min-width:220px;">リストを見る</a>
        </div>
      @endif
    @endif
  </div>
</div>
@endsection
