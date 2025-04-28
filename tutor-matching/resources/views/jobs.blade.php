@extends('layouts.app')
@section('page_title', '求人リスト')
@section('content')
<div class="container" style="max-width:700px; margin:32px auto;">
  <div class="dashboard-box">
    
    @if(isset($jobs) && count($jobs) > 0)
      @php
        $user = Auth::user();
        $entries = $user ? \App\Models\Entry::where('user_id', $user->id)->pluck('employer_id')->toArray() : [];
      @endphp
      @foreach($jobs as $item)
        @php $already = in_array($item->id, $entries); @endphp
        @include('components.list_card', [
          'fields' => [
            ['label' => '塾名', 'value' => $item->first_name ? $item->first_name : '塾名未設定'],
            ['label' => '募集科目', 'value' => $item->recruiting_subject ?? '未設定'],
            ['label' => '住所', 'value' => $item->address ?? '住所未設定'],
            ['label' => '説明', 'value' => $item->description ?? '説明未設定'],
          ],
          'image' => ($item->profile_image ?? null) ? $item->profile_image : (($item->env_img && count(json_decode($item->env_img, true)) > 0) ? json_decode($item->env_img, true)[0] : asset('images/default_company.png')),
          'buttons' => [
            ['label' => '詳細', 'url' => route('job.show', ['id' => $item->id])],
            $already
              ? ['label' => '応募済', 'disabled' => true]
              : ['label' => '応募する', 'job_id' => $item->id]
          ]
        ])
      @endforeach
    @else
      <div class="text-muted">現在求人はありません。</div>
    @endif
  </div>
</div>
@endsection
