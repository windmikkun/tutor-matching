{{-- resources/views/teacher_list.blade.php --}}
@extends('layouts.app')
@section('content')
@include('components.search_form')
@php
  $user = Auth::user();
  $currentUserBookmarks = isset($user) ? $user->bookmarks->where('bookmarkable_type', 'teacher') : collect();
@endphp

@isset($teachers)
  @foreach($teachers as $teacher)
    @php
      if (!$teacher) continue;
      $isBookmarked = $currentUserBookmarks->where('bookmarkable_id', $teacher->id)->isNotEmpty();
      $bookmarkCount = $teacher->bookmarkedByEmployers->count() ?? 0;
    @endphp
    @include('components.list_card', [
      'fields' => [
        ['label' => '氏名', 'value' => $teacher->last_name.' '.$teacher->first_name],
        ['label' => '都道府県', 'value' => optional($teacher->user)->prefecture ?? '未設定'],
        ['label' => '市区町村', 'value' => optional($teacher->user)->address1 ?? '未設定'],
        ['label' => '得意科目', 'value' => $teacher->subject],
        ['label' => '学年', 'value' => $teacher->grade_level],
        ['label' => '自己紹介', 'value' => Str::limit($teacher->bio, 60)],
      ],
      'image' => $teacher->profile_image ?? asset('images/default.png'),
      'buttons' => [
        ['label' => '詳細', 'url' => route('teacher.show', ['id' => $teacher->id])],
        ['label' => 'スカウト', 'url' => route('scout.create', $teacher->id)]
      ],
      'bookmarkButton' => view('components.bookmark_button', [
        'isBookmarked' => $isBookmarked,
        'type' => 'teacher',
        'id' => $teacher->id,
        'bookmarkCount' => $bookmarkCount,
        'currentUserBookmarks' => $currentUserBookmarks
      ])->render()
    ])
  @endforeach
@endisset

@isset($entries)
  @foreach($entries as $entry)
    @php
      $teacher = $entry->user ? $entry->user->teacher : null;
      if (!$teacher) continue;
      $isBookmarked = $currentUserBookmarks->where('bookmarkable_id', $teacher->id)->isNotEmpty();
      $bookmarkCount = $teacher->bookmarkedByEmployers->count() ?? 0;
    @endphp
    @include('components.list_card', [
      'fields' => [
        ['label' => '氏名', 'value' => $teacher->last_name.' '.$teacher->first_name],
        ['label' => '都道府県', 'value' => optional($teacher->user)->prefecture ?? '未設定'],
        ['label' => '市区町村', 'value' => optional($teacher->user)->address1 ?? '未設定'],
        ['label' => '得意科目', 'value' => $teacher->subject],
        ['label' => '学年', 'value' => $teacher->grade_level],
        ['label' => '自己紹介', 'value' => Str::limit($teacher->bio, 60)],
      ],
      'image' => $teacher->profile_image ?? asset('images/default.png'),
      'buttons' => [
        ['label' => '詳細', 'url' => route('teacher.show', ['id' => $teacher->id])],
        [
          'custom' => true,
          'html' => view('components.reject_applicant_form', ['id' => $entry->id])->render()
        ],
        ['label' => 'スカウト', 'url' => route('scout.create', $teacher->id)]
      ],
      'bookmarkButton' => view('components.bookmark_button', [
        'isBookmarked' => $isBookmarked,
        'type' => 'teacher',
        'id' => $teacher->id,
        'bookmarkCount' => $bookmarkCount,
        'currentUserBookmarks' => $currentUserBookmarks
      ])->render()
    ])
  @endforeach
@endisset
<script src="/js/bookmark.js"></script>
@endsection
