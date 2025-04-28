<div class="form-bottom-space" style="margin-top:2.2rem;">
<form method="{{ $method ?? 'POST' }}" action="{{ $action }}" style="max-width:400px; margin:0 auto;">
    @csrf
    @if(isset($method_field))
        @method($method_field)
    @endif
    <div class="mb-3 text-start">
        <label for="email" class="form-label w-100 form-label-top-space">メールアドレス</label>
        <input id="email" type="email" name="email" class="form-control w-100 mt-1" style="border-radius:10px;" value="{{ old('email', $user->email ?? '') }}">
        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 text-start">
        <label for="postal_code" class="form-label w-100">郵便番号</label>
        <input id="postal_code" type="text" name="postal_code" class="form-control w-100 mt-1" style="border-radius:10px;" value="{{ old('postal_code', $user->postal_code ?? '') }}">
        @error('postal_code')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 text-start">
        <label for="phone" class="form-label w-100">電話番号</label>
        <input id="phone" type="text" name="phone" class="form-control w-100 mt-1" style="border-radius:10px;" value="{{ old('phone', $user->phone ?? '') }}">
        @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mt-4 text-center">
        <button type="submit" class="btn-blue-box" style="font-size:1.12rem; min-width:180px;">保存</button>
    </div>
</form>
</div>
