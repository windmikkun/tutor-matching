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
@php
    // 既にブックマークしている場合、該当Bookmarkレコードを取得
    $bookmarkId = null;
    if ($isBookmarked && isset($currentUserBookmarks)) {
        // 直近のブックマークIDを必ず取得する
        $bookmark = $currentUserBookmarks->where('bookmarkable_id', $id)->sortByDesc('id')->first();
        $bookmarkId = $bookmark ? $bookmark->id : null;
    } elseif ($isBookmarked && isset($bookmarkIdFromController)) {
        $bookmarkId = $bookmarkIdFromController;
    } else {
        $bookmarkId = null;
    }
    $bookmarkCount = isset($bookmarkCount) ? $bookmarkCount : (isset($count) ? $count : 0);
@endphp
<button
    type="button"
    class="bookmark-btn{{ $isBookmarked ? ' btn-gray-box bookmarked' : ' btn-blue-box' }}"
    style="vertical-align:middle; padding: 0.35em 1.1em; display: inline-flex; align-items: center; justify-content: center; gap: 0.3em; font-size: 1.1em;"
    data-url="{{ $isBookmarked && $bookmarkId ? route('bookmarks.destroy', ['bookmark' => $bookmarkId]) : route('bookmarks.store') }}"
    data-method="{{ $isBookmarked && $bookmarkId ? 'DELETE' : 'POST' }}"
    data-body='@json($body)'
    data-original-body='@json($body)'
    data-type="{{ $type }}"
    data-original-store-url="{{ route('bookmarks.store') }}"
    @if(!empty($bookmarkId))
        data-bookmark-id="{{ $bookmarkId }}"
    @endif
>
    <span class="bookmark-star" style="font-size:1.3em; vertical-align:middle;">{{ $isBookmarked ? '★' : '☆' }}</span>
    <span class="bookmark-count" style="font-weight:bold; font-size:1.1em; vertical-align:middle;">{{ $bookmarkCount }}</span>
</button>
{{-- ※ 必ず <head> で csrf-token メタタグが出力されていることを確認してください --}}
{{-- <script src="/js/bookmark.js"></script> をレイアウトや該当ページで読み込んでください --}}
