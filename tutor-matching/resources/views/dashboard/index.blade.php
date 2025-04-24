@extends('layouts.app')

@section('title', 'ダッシュボード')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">ダッシュボード</h2>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><a href="{{ route('jobs.index') }}">求人リストを見る</a></li>
        <li class="list-group-item"><a href="{{ route('profile.edit') }}">プロフィール編集</a></li>
        <li class="list-group-item"><a href="/chatify">チャット</a></li>
        <li class="list-group-item"><a href="{{ route('scout.index') }}">スカウト一覧</a></li>
    </ul>
</div>
@endsection
