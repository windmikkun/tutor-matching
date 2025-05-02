{{-- resources/views/scout_detail.blade.php --}}
@extends('layouts.app')

@section('page_title', 'スカウト詳細')
@section('content')
<div class="container" style="max-width:600px; margin:40px auto;">
  <div class="card shadow bg-white mx-auto" style="width:100%; max-width:600px; border-radius:1.2rem; overflow:hidden;">
    <div class="card-body p-5">
      <h3 class="mb-4 text-center" style="font-weight:bold; font-size:1.45rem; color:#2563eb;">スカウト詳細</h3>
      <div class="mb-4">
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
  </div>
</div>
@endsection
