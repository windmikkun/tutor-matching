{{-- 共通リストレイアウト: resources/views/components/list_layout.blade.php --}}
<div class="pt-3 pb-5 w-100">
    @if(count($items) > 0)
        @foreach ($items as $item)
            @include($cardView, ['item' => $item])
        @endforeach
    @else
        <div class="alert alert-secondary text-center">データが見つかりません</div>
    @endif
</div>
