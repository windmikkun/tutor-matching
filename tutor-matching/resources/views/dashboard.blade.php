{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

{{-- resources/views/teacher/dashboard.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- ヘッダー --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="ロゴ" height="40">
        </div>
        <nav>
            <a href="{{ route('dashboard') }}" class="mx-2">ダッシュボード</a>
            @php
    $userType = Auth::user()->user_type ?? '';
@endphp
@if ($userType === 'teacher')
    <a href="{{ route('teacher.profile.edit') }}" class="mx-2">プロフィール</a>
@elseif ($userType === 'corporate_employer' || $userType === 'employer')
    <a href="{{ route('employer.profile.edit') }}" class="mx-2">プロフィール</a>
@endif
            <a href="/chatify" class="mx-2">メッセージ</a>
        </nav>
        <div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-outline-primary">ログアウト</button>
</form>
        </div>
    </div>

    {{-- メイン --}}
    <div class="row">
        {{-- プロフィールサマリー --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
    @if ($teacher)
        <img src="{{ $teacher->profile_image ?? asset('images/default_teacher.png') }}" class="rounded-circle mb-3" width="80" height="80" alt="プロフィール画像">
        <h5 class="card-title">{{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
        <p class="card-text">{{ $teacher->bio }}</p>
    @else
        <img src="{{ asset('images/default_teacher.png') }}" class="rounded-circle mb-3" width="80" height="80" alt="プロフィール画像">
        <h5 class="card-title text-danger">講師プロフィール未登録</h5>
        <p class="card-text text-muted">プロフィール情報がありません。</p>
    @endif
    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">プロフィール編集</a>
</div>
            </div>
        </div>

        {{-- スカウト状況 --}}
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header">スカウト状況</div>
                <div class="card-body">
                    <ul class="list-group list-group-horizontal">
                        <li class="list-group-item flex-fill text-center">
                            <div class="h4 mb-1">{{ $scoutCounts['new'] }}</div>
                            <div>新着</div>
                        </li>
                        <li class="list-group-item flex-fill text-center">
                            <div class="h4 mb-1">{{ $scoutCounts['pending'] }}</div>
                            <div>検討中</div>
                        </li>
                        <li class="list-group-item flex-fill text-center">
                            <div class="h4 mb-1">{{ $scoutCounts['matched'] }}</div>
                            <div>成立</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- 新着スカウト一覧 --}}
    <div class="card mb-4">
        <div class="card-header">新着スカウト一覧</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>塾名</th>
                        <th>メッセージ</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newScouts as $scout)
                        <tr>
                            <td>{{ $scout->employer->name }}</td>
                            <td>{{ Str::limit($scout->message, 30) }}</td>
                            <td>
                                <a href="{{ route('teacher.scout.reply', $scout->id) }}" class="btn btn-sm btn-primary">返信</a>
                                <a href="{{ route('teacher.scout.show', $scout->id) }}" class="btn btn-sm btn-outline-secondary">詳細</a>
                                <a href="/scouts" class="btn btn-sm btn-info ms-2">スカウト一覧を見る</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">新着スカウトはありません</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


</div>

{{-- フッター --}}
<footer class="bg-light text-center py-3 mt-4 border-top">
    <small>&copy; {{ date('Y') }} 塾講師逆スカウト求人サイト</small>
</footer>
@endsection
