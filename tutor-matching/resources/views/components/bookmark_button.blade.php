{{-- 共通ブックマークボタン --}}
@php
    $isBookmarked = $isBookmarked ?? false;
    $type = $type ?? '';
    $id = $id ?? '';
    if ($type === 'employer') {
        $body = ['employer_id' => $id];
    } elseif ($type === 'teacher') {
        $body = ['teacher_id' => $id];
    } else {
        $body = [];
    }
@endphp
<button
    type="button"
    class="bookmark-btn mx-2{{ $isBookmarked ? ' btn-gray-box bookmarked' : ' btn-blue-box' }}"
    style="vertical-align:middle;"
    data-url="{{ route('bookmarks.store') }}"
    data-method="POST"
    data-body='@json($body)'
>
    <span class="bookmark-star">{{ $isBookmarked ? '★' : '☆' }}</span>x<span class="bookmark-count">{{ isset($bookmarkCount) ? $bookmarkCount : 0 }}</span>
</button>
{{-- ※ 必ず <head> で csrf-token メタタグが出力されていることを確認してください --}}
{{-- <script src="/js/bookmark.js"></script> をレイアウトや該当ページで読み込んでください --}}
