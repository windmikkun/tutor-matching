@extends('layouts.app')

@section('title', 'スカウト一覧')
@section('page_title', 'スカウト一覧')
@section('content')
<div class="container py-4">

    <!-- スカウト一覧表示エリア（例） -->
    @if(isset($scouts) && count($scouts) > 0)
      @foreach($scouts as $item)
        @include('components.list_card', [
          'fields' => [
            ['label' => '講師名', 'value' => $item->teacher_name ?? '不明'],
            ['label' => 'メッセージ', 'value' => $item->message ?? ''],
          ],
          'image' => asset('images/default_teacher.png'),
          'buttons' => [
            ['label' => '<a href="{{ route('scout.show', $item->id) }}" class="btn-blue-box">詳細</a>']
          ]
        ])
      @endforeach
    @else
      <div class="text-muted">スカウトがありません。</div>
    @endif
</div>
@endsection
