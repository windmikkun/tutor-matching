{{-- resources/views/teacher_show.blade.php --}}
@extends('layouts.app')

@section('page_title', '講師詳細')
@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-center">
        @include('components.list_card', [
            'image' => $teacher->profile_image ?? asset('images/default_teacher.png'),
            'fields' => [
                ['label' => '氏名', 'value' => $teacher->last_name.' '.$teacher->first_name],
                ['label' => '都道府県', 'value' => optional($teacher->user)->prefecture ?? '未設定'],
                ['label' => '市区町村', 'value' => optional($teacher->user)->address1 ?? '未設定'],
                ['label' => '最終学歴', 'value' => $teacher->education],
                ['label' => '在学中の大学', 'value' => $teacher->current_school],
                ['label' => '自己アピール', 'value' => $teacher->self_appeal],
            ],
            'buttons' => [
                ['label' => '一覧に戻る', 'url' => route('teacher.list')],
                ['label' => 'スカウトする', 'url' => route('scout.create', ['id' => $teacher->id])]
            ],
            'bookmarkButton' => view('components.bookmark_button', [
                'isBookmarked' => $isBookmarked ?? false,
                'bookmarkCount' => $bookmarkCount ?? 0,
                'type' => 'teacher',
                'id' => $teacher->id,
                'currentUserBookmarks' => $currentUserBookmarks ?? collect()
            ])->render()
        ])
        
            @php
                $youtubeUrl = $teacher->trial_lesson;
                $youtubeId = null;
                if (preg_match('/youtu.be\/([\w-]+)/', $youtubeUrl, $matches)) {
                    $youtubeId = $matches[1];
                } elseif (preg_match('/youtube.com\/watch\?v=([\w-]+)/', $youtubeUrl, $matches)) {
                    $youtubeId = $matches[1];
                }
            @endphp
            @if ($youtubeId)
                <div class="mt-3 mb-2 px-4">
                    <div style="max-width:480px; margin:0 auto;">
                        <iframe width="100%" height="270" src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen style="border-radius:12px;"></iframe>
                    </div>
                </div>
            @endif
        
    </div>
</div>
<script src="/js/bookmark.js"></script>
@endsection
