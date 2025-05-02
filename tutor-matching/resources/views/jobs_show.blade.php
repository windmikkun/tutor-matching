@extends('layouts.app')
@section('page_title', '求人詳細')
@section('content')
<div class="container" style="max-width:700px; margin:32px auto;">
  @php
    // 画像URL取得ロジックは既存のままlist_cardに渡す
@endphp
@php
    $envImgs = ($job->env_img && is_string($job->env_img)) ? json_decode($job->env_img, true) : [];
@endphp
@include('components.list_card', [
        'fields' => [
          ['label' => '塾名', 'value' => $job->first_name ?? '塾名未設定'],
          ['label' => '募集科目', 'value' => $job->recruiting_subject ?? '未設定'],
          ['label' => '都道府県', 'value' => optional($job->user)->prefecture ?? '未設定'],
          ['label' => '県庁所在地', 'value' => optional($job->user)->address1 ?? '未設定'],
          ['label' => '仕事内容', 'value' => $job->description ?? '未設定'],
          ['label' => '指導形態', 'value' => $job->lesson_type ?? '未設定'],
          ['label' => '生徒数', 'value' => $job->student_count ?? '未設定'],
          ['label' => '生徒層', 'value' => $job->student_demographics ?? '未設定'],
          ['label' => '時給', 'value' => isset($job->hourly_rate) ? ($job->hourly_rate . '円') : '未設定'],
        ],
        'image' => ($job->profile_image ?? null) ? $job->profile_image : (($job->env_img && count(json_decode($job->env_img, true)) > 0) ? json_decode($job->env_img, true)[0] : asset('images/default.png')),
        'buttons' => [
          ['label' => '一覧に戻る', 'url' => url('/jobs')],
          ['label' => '応募する', 'url' => route('entry.create', ['id' => $job->id])]
        ],
        'bookmarkButton' => view('components.bookmark_button', [
            'isBookmarked' => $isBookmarked ?? false,
            'type' => 'employer',
            'id' => $job->id,
            'count' => $bookmarkCount ?? 0,
            'currentUserBookmarks' => $currentUserBookmarks ?? null
        ])->render(),
        'env_imgs' => $envImgs
      ])
    </div>
  </div>
</div>
<script src="/js/bookmark.js"></script>
@endsection
