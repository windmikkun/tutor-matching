{{-- resources/views/scout_detail.blade.php --}}
@extends('layouts.app')

@section('page_title', 'スカウト詳細')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-center">
        @include('components.list_card', [
            'fields' => [
                ['label' => 'スカウトID', 'value' => $scout->id],
                ['label' => '送信者(雇用者ID)', 'value' => $scout->employer_id],
                ['label' => '受信者(講師ID)', 'value' => $scout->teacher_id],
                ['label' => '内容', 'value' => $scout->message],
                ['label' => '状態', 'value' => $scout->status],
                ['label' => '送信日時', 'value' => $scout->created_at],
            ],
            'image' => asset('images/scout.png'),
            'buttons' => [
                ['label' => '一覧に戻る', 'url' => route('scout.list')],
            ]
        ])
    </div>
</div>
@endsection
