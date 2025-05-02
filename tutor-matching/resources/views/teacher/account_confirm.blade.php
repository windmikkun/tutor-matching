@extends('layouts.app')
@section('content')
<div class="container" style="max-width:600px; margin:32px auto;">
  <div class="card shadow-sm" style="border-radius:1.5rem; background:#fff;">
    <div class="mb-5 text-center">
      <h2 class="fw-bold mb-0" style="font-size:2rem;">会員情報確認</h2>
    </div>
    <form method="post" action="{{ route('teacher.account.update') }}">
      @csrf
      @method('PUT')
      <table class="table">
        <tr><th>メールアドレス</th><td>{{ $inputs['email'] ?? '' }}</td></tr>
        <tr><th>電話番号</th><td>{{ $inputs['phone'] ?? '' }}</td></tr>
        <tr><th>郵便番号</th><td>{{ $inputs['postal_code'] ?? '' }}</td></tr>
        <tr><th>都道府県</th><td>{{ $inputs['prefecture'] ?? '' }}</td></tr>
        <tr><th>市区町村</th><td>{{ $inputs['address1'] ?? '' }}</td></tr>
      </table>
      @foreach($inputs as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
      @endforeach
      <div class="mt-4 text-center">
        <button type="submit" class="btn-blue-box" style="font-size:1.12rem; min-width:180px;">保存</button>
        <a href="{{ route('teacher.account.edit') }}" class="btn-blue-box" style="font-size:1.12rem; min-width:180px; margin-left:16px;">戻る</a>
      </div>
    </form>
  </div>
</div>
@endsection
