@extends('layouts.app')

@section('title', '法人求人リスト')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">法人求人リスト</h2>
    <ul class="list-group">
        @forelse($jobs as $job)
            <li class="list-group-item">
                <a href="#">{{ $job->title }}</a>
            </li>
        @empty
            <li class="list-group-item">求人がありません。</li>
        @endforelse
    </ul>
</div>
@endsection
