@extends('layouts.app')
@section('page_title', 'スカウト一覧')
@section('content')
<div class="container" style="max-width:800px; margin:40px auto;">
    @forelse($scouts as $scout)
        @php
            $fields = [
                ['label' => '送信者(塾)', 'value' => $scout->employer->user->name ?? $scout->employer_id],
                ['label' => '内容', 'value' => $scout->message],
                ['label' => '状態', 'value' => $scout->status],
                ['label' => '送信日時', 'value' => $scout->created_at],
            ];
            $buttons = [
                [
                    'label' => '承諾',
                    'accept' => true,
                    'url' => route('scout.confirm.send', ['id' => $scout->id]),
                ],
                [
                    'label' => '拒否',
                    'url' => route('scout.reject', ['id' => $scout->id]),
                ],
            ];
@endphp
        @php
    $profileImage = $scout->employer->profile_image ?? null;
    $envImgs = $scout->employer->env_img ? json_decode($scout->employer->env_img, true) : [];
    $cardImage = $profileImage
        ? $profileImage
        : (isset($envImgs[0]) ? $envImgs[0] : asset('images/default.png'));
@endphp
@component('components.list_card', ['fields' => $fields, 'buttons' => $buttons, 'image' => $cardImage])
@endcomponent
    @empty
        <div class="text-center text-secondary py-5">スカウトはありません</div>
    @endforelse
</div>
@endsection
