@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <h1>{{ $employer->name ?? '塾詳細' }}</h1>
        <p><strong>住所:</strong> {{ $employer->address ?? '-' }}</p>
        <p><strong>電話番号:</strong> {{ $employer->phone ?? '-' }}</p>
        <p><strong>担当者:</strong> {{ $employer->contact_person ?? '-' }}</p>
        <p><strong>説明:</strong> {{ $employer->description ?? '-' }}</p>
        <!-- 必要に応じて他の項目も追加 -->
        <div class="mt-3">
            <x-bookmark_button 
                :isBookmarked="isset($isBookmarked) ? $isBookmarked : false" 
                type="employer" 
                :id="$employer->id" 
            />
            <span id="bookmark-count" class="ms-2" style="font-size: 1.2em; color: #ffc107; vertical-align: middle;">
                ★ {{ $bookmarkCount }}
            </span>
            {{-- ↑ ブックマーク数を動的に更新するためのID付き --}}
        </div>
    </div>
@endsection
