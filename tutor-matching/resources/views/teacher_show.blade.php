{{-- resources/views/teacher_show.blade.php --}}
@extends('layouts.app')

@section('page_title', '講師詳細')
@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-center">
        @include('components.list_card', [
            'fields' => [
                ['label' => '氏名', 'value' => $teacher->first_name.' '.$teacher->last_name],
                ['label' => '担当科目', 'value' => $teacher->subject],
                ['label' => '学年', 'value' => $teacher->grade_level],
                ['label' => '自己紹介', 'value' => $teacher->bio],
            ],
            'image' => $teacher->profile_image ?? asset('images/default_teacher.png'),
            'buttons' => [
                ['label' => '一覧に戻る', 'url' => route('teacher.list')],
                ['label' => 'スカウトする', 'url' => route('scout.create', ['id' => $teacher->id])]
            ]
        ])
    </div>
</div>
@endsection
