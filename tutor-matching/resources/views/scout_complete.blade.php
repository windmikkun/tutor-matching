@extends('layouts.app')
@section('page_title', 'スカウト送信完了')
@section('content')
<div class="container" style="max-width:700px; margin:40px auto;">
    <div class="card shadow bg-white mx-auto" style="width:100%; max-width:700px; border-radius:1rem; overflow:hidden;">
        <div class="card-body p-5 text-center">
            <h3 class="mb-4" style="color:#339af0;">スカウト送信完了</h3>
            <div class="mb-4" style="font-size:1.18rem; color:#444;">スカウトを送信しました。</div>
            <div class="mb-4" style="font-size:1.08rem; color:#666;">
                10秒後に自動的に講師一覧ページへ移動します。<br>
                <span id="countdown" style="font-size:1.4rem; color:#339af0; font-weight:bold;">10</span> 秒
            </div>
            <a href="{{ route('teacher.list') }}" class="btn-blue-box" style="min-width:160px;">講師一覧に戻る</a>
        </div>
    </div>
</div>
<script>
let count = 10;
const countdown = document.getElementById('countdown');
const interval = setInterval(() => {
    count--;
    countdown.textContent = count;
    if (count <= 0) {
        clearInterval(interval);
        window.location.href = "{{ route('teacher.list') }}";
    }
}, 1000);
</script>
@endsection
