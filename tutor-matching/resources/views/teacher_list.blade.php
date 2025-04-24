{{-- resources/views/teacher_list.blade.php --}}
@extends('layouts.app')
@section('content')
@foreach($teachers as $item)
  @include('components.list_card', [
    'fields' => [
      ['label' => '氏名', 'value' => $item->first_name.' '.$item->last_name],
      ['label' => '得意科目', 'value' => $item->subject],
      ['label' => '学年', 'value' => $item->grade_level],
      ['label' => '自己紹介', 'value' => Str::limit($item->bio, 60)],
    ],
    'image' => $item->profile_image ?? asset('images/default_teacher.png'),
    'buttons' => [
      ['label' => '詳細', 'url' => route('teacher.show', ['id' => $item->id])],
      ['label' => 'スカウト', 'url' => route('scout.create', $item->id)]
    ]
  ])
@endforeach
@endsection
