{{-- スカウトアンケートページ: resources/views/scout_survey.blade.php --}}
@extends('layouts.app')
@section('page_title', 'スカウトアンケート')
@section('content')
<div class="container" style="max-width:700px; margin:40px auto;">
    <div class="card shadow bg-white mx-auto" style="width:100%; max-width:700px; border-radius:1rem; overflow:hidden;">
        <div class="card-body p-5">
            <form method="POST" action="{{ route('scout.confirm', $teacher_id) }}">
                @csrf
                <div class="mb-4 text-center">
                    <div style="font-size:1.1rem; color:#555; margin-bottom:16px;">スカウト相手への質問などを記入して下さい</div>
                    <textarea name="message" id="message" class="form-control mx-auto" style="width:100%; max-width:640px; min-height:260px; font-size:1.15rem;" rows="12" required placeholder="例：自己紹介や質問など自由にご記入ください"></textarea>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn-blue-box" style="min-width:160px; font-size:1.08rem;">送信</button>
                </div>
            </form>
        </div>
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
