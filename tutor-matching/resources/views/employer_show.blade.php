@extends('layouts.app')
@section('page_title', '塾詳細')
@section('content')
<div class="container" style="max-width:700px; margin:32px auto;">
  @php
    $envImgs = ($employer->env_img && is_string($employer->env_img)) ? json_decode($employer->env_img, true) : [];
  @endphp
  @php
    // ログインユーザーのこのemployerへのブックマークIDを必ず取得
    $bookmarkId = null;
    if (!isset($currentUserBookmarks) && auth()->check()) {
        $currentUserBookmarks = auth()->user()->bookmarks->where('bookmarkable_type', 'employer');
    }
    if (isset($currentUserBookmarks)) {
        $bookmark = $currentUserBookmarks->where('bookmarkable_id', $employer->id)->first();
        if ($bookmark) {
            $bookmarkId = $bookmark->id;
        }
    }
  @endphp
  @include('components.list_card', [
        'fields' => [
          ['label' => '塾名', 'value' => $employer->name ?? '塾名未設定'],
          ['label' => '担当者', 'value' => $employer->contact_person ?? '-'],
          ['label' => '説明', 'value' => $employer->description ?? '-'],
          ['label' => '住所', 'value' => $employer->address ?? '-'],
          ['label' => '電話番号', 'value' => $employer->phone ?? '-'],
        ],
        'image' => ($employer->profile_image ?? null) ? $employer->profile_image : (($employer->env_img && count($envImgs) > 0) ? $envImgs[0] : asset('images/default_company.png')),
        'buttons' => [
          ['label' => 'ダッシュボードへ', 'url' => route('dashboard.teacher')],
          ['label' => 'チャット画面へ', 'url' => route('chat.show', ['id' => $employer->user_id])],
        ],
        'bookmarkButton' => view('components.bookmark_button', [
            'isBookmarked' => $isBookmarked ?? false,
            'type' => 'employer',
            'id' => $employer->id,
            'count' => $bookmarkCount ?? 0,
            'bookmarkId' => $bookmarkId,
            'currentUserBookmarks' => $currentUserBookmarks ?? null
        ])->render(),
        'env_imgs' => $envImgs
      ])
</div>
<script src="/js/bookmark.js"></script>
@endsection
