@extends('layouts.app')
@section('page_title', '求人リスト')
@section('content')
@include('components.search_form')
<div class="container" style="max-width:700px; margin:32px auto;">
  <div class="dashboard-box">
    
    @if(isset($jobs) && count($jobs) > 0)
      @php
        $user = Auth::user();
        $entries = $user ? \App\Models\Entry::where('user_id', $user->id)->pluck('employer_id')->toArray() : [];
      @endphp
      @foreach($jobs as $item)
        @php
          $already = in_array($item->id, $entries);
          $bookmarkCount = $item->bookmarkedByTeachers->count() ?? 0;
        @endphp
        @php
          // ログインユーザーのemployer bookmark一覧を取得（パフォーマンス改善にはwith/bookmarkedByTeachersで事前取得推奨）
          $currentUserBookmarks = isset($user) ? $user->bookmarks->where('bookmarkable_type', 'employer') : collect();
          $isBookmarked = $currentUserBookmarks->where('bookmarkable_id', $item->id)->isNotEmpty();
        @endphp
        @include('components.list_card', [
          'fields' => [
            ['label' => '塾名', 'value' => $item->first_name ? $item->first_name : '塾名未設定'],
            ['label' => '都道府県', 'value' => optional($item->user)->prefecture ?? '未設定'],
            ['label' => '市区町村', 'value' => optional($item->user)->address1 ?? '未設定'],
            ['label' => '指導形態', 'value' => $item->lesson_type ?? '未設定'],
            ['label' => '時給', 'value' => isset($item->hourly_rate) ? ($item->hourly_rate . '円') : '未設定'],
          ],
          'image' => ($item->profile_image ?? null) ? $item->profile_image : (($item->env_img && count(json_decode($item->env_img, true)) > 0) ? json_decode($item->env_img, true)[0] : asset('images/default.png')),
          'buttons' => [
            ['label' => '詳細', 'url' => route('job.show', ['id' => $item->id])],
            $already
              ? ['label' => '応募済', 'disabled' => true]
              : [
                  'label' => '応募する',
                  'url' => route('entry.create', ['id' => $item->id]),
                  'job_id' => $item->id
                ]
          ],
          'bookmarkButton' => view('components.bookmark_button', [
            'isBookmarked' => $isBookmarked,
            'type' => 'employer',
            'id' => $item->id,
            'bookmarkCount' => $bookmarkCount,
            'currentUserBookmarks' => $currentUserBookmarks
          ])->render()
        ])
      @endforeach
    @else
      <div class="text-muted">現在求人はありません。</div>
    @endif
  </div>
</div>
<script src="/js/bookmark.js"></script>
@endsection
