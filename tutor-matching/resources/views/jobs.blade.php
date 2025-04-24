@extends('layouts.app')
@section('page_title', '求人リスト')
@section('content')
<div class="container" style="max-width:700px; margin:32px auto;">
  <div class="dashboard-box">
    
    @if(isset($jobs) && count($jobs) > 0)
      @foreach($jobs as $item)
        @include('components.list_card', [
          'fields' => [
            ['label' => '塾名', 'value' => $item->name ?? '塾名未設定'],
            ['label' => '担当者', 'value' => $item->contact_person ?? '未設定'],
            ['label' => '電話番号', 'value' => $item->phone ?? '未設定'],
            ['label' => '住所', 'value' => $item->address ?? '住所未設定'],
            ['label' => '説明', 'value' => $item->description ?? '説明未設定'],
          ],
          'image' => asset('images/default_company.png'),
          'buttons' => [
            ['label' => '詳細', 'url' => route('job.show', ['id' => $item->id])],
            ['label' => '応募する', 'url' => '#']
          ]
        ])
      @endforeach
    @else
      <div class="text-muted">現在求人はありません。</div>
    @endif
  </div>
</div>
@endsection
