@extends('layouts.app')
@section('page_title', 'スカウト一覧')
@section('content')
<div class="container" style="max-width:800px; margin:40px auto;">
    <div class="card shadow bg-white mx-auto" style="width:100%; max-width:800px; border-radius:1rem; overflow:hidden;">
        <div class="card-body p-5">
            <h3 class="mb-4 text-center">スカウト一覧</h3>
            <table class="table table-bordered align-middle text-center bg-white">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>送信者(雇用者ID)</th>
                        <th>受信者(講師ID)</th>
                        <th>内容</th>
                        <th>状態</th>
                        <th>送信日時</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($scouts as $scout)
                    <tr>
                        <td>{{ $scout->id }}</td>
                        <td>{{ $scout->employer_id }}</td>
                        <td>{{ $scout->teacher_id }}</td>
                        <td class="text-start">{{ $scout->message }}</td>
                        <td>{{ $scout->status }}</td>
                        <td>{{ $scout->created_at }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary">スカウトはありません</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
