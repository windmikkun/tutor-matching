@extends('layouts.app')
@section('page_title', 'スカウトアンケート内容確認')
@section('content')
<div class="container" style="max-width:700px; margin:40px auto;">
    <div class="card shadow bg-white mx-auto" style="width:100%; max-width:700px; border-radius:1rem; overflow:hidden;">
        <div class="card-body p-5">
            <form id="confirmForm" method="POST" action="{{ route('scout.confirm.send', $teacher_id) }}">
                @csrf
                <input type="hidden" name="message" value="{{ $message }}">

                <div class="mb-4 text-center">
                    <div style="font-size:1.1rem; color:#555; margin-bottom:16px;">入力内容はこちらでよろしいですか？</div>
                    <div class="border p-3 bg-light mx-auto" style="max-width:640px; min-height:140px; font-size:1.15rem; text-align:left; white-space:pre-wrap;">{{ $message }}</div>
                </div>
                <div class="text-center mt-4 d-flex flex-row justify-content-center gap-3">
    <button type="button" class="btn-blue-box" style="background:#adb5bd; border-color:#adb5bd; color:#fff; min-width:140px; font-size:1.02rem;" onclick="history.back();">記入に戻る</button>
    <button type="submit" class="btn-blue-box" style="min-width:160px; font-size:1.08rem;">送信</button>
</div>
            </form>
        </div>
    </div>
</div>
<style>
.btn-blue-box {
    background: #4dabf7;
    border: 1.5px solid #4dabf7;
    color: #fff !important;
    font-weight: 500;
    min-width: 110px;
    padding: 10px 0;
    border-radius: 0.4rem;
    box-shadow: 0 2px 8px rgba(77,171,247,0.13);
    font-size: 1rem;
    transition: background 0.15s, color 0.15s;
    display: inline-block;
}
.btn-blue-box:hover, .btn-blue-box:focus {
    background: #339af0;
    color: #fff !important;
    text-decoration: none;
}
@media (max-width: 600px) {
    .btn-blue-box { min-width: 90px; font-size: 0.95rem; }
}
</style>

@endsection
