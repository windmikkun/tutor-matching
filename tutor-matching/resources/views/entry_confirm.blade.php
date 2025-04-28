@extends('layouts.app')
@section('page_title', '応募確認')
@section('content')
<div style="position:fixed; inset:0; z-index:1000; display:flex; align-items:center; justify-content:center; background:rgba(245,246,248,0.97);">
    <div class="card shadow bg-white" style="border-radius:1rem; overflow:hidden; min-width:320px; max-width:480px; width:100%;">
        <div class="card-body p-5 text-center">
            <h3 class="mb-4">{{ $job->first_name ?? $job->name ?? 'この塾' }}に応募しますか？</h3>
            <div class="text-start mb-4" style="font-size:0.97rem;">
                <div class="mb-1"><strong>住所:</strong> {{ $job->address ?? '未設定' }}</div>
                <div class="mb-1"><strong>募集科目:</strong> {{ $job->recruiting_subject ?? '未設定' }}</div>
                <div class="mb-1"><strong>説明:</strong> {{ $job->description ?? '未設定' }}</div>
            </div>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ url('/jobs') }}" class="btn-blue-box" style="background:#adb5bd; border-color:#adb5bd; color:#fff;">一覧に戻る</a>
                <form method="POST" action="{{ route('entry.store', $job->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-blue-box">応募する</button>
                </form>
            </div>
            <style>
            .btn-blue-box {
                background: #2563eb;
                color: #fff !important;
                font-weight: 600;
                border: none;
                border-radius: 0.7em;
                min-width: 110px;
                padding: 10px 0;
                box-shadow: 0 2px 6px rgba(37,99,235,0.08);
                font-size: 1.1rem;
                transition: background 0.15s, color 0.15s;
                display: inline-block;
                text-decoration: none;
            }
            .btn-blue-box:hover, .btn-blue-box:focus {
                background: #1e40af;
                color: #fff !important;
                text-decoration: none;
            }
            @media (max-width: 600px) {
                .btn-blue-box { min-width: 90px; font-size: 0.95rem; }
            }
            </style>
        </div>
    </div>
</div>
@endsection
