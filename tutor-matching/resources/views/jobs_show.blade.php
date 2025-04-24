@extends('layouts.app')
@section('page_title', '求人詳細')
@section('content')
<div class="container" style="max-width:700px; margin:32px auto;">
  @include('components.list_card', [
        'fields' => [
          ['label' => '塾名', 'value' => $job->name ?? '塾名未設定'],
          ['label' => '担当者', 'value' => $job->contact_person ?? '未設定'],
          ['label' => '電話番号', 'value' => $job->phone ?? '未設定'],
          ['label' => '住所', 'value' => $job->address ?? '未設定'],
          ['label' => '仕事内容', 'value' => $job->description ?? '未設定'],
        ],
        'image' => asset('images/default_company.png'),
        'buttons' => [
          ['label' => '一覧に戻る', 'url' => url('/jobs')],
          ['label' => '応募する', 'url' => route('entry.create', ['id' => $job->id])]
        ]
      ])
    </div>
  </div>
</div>
@endsection
