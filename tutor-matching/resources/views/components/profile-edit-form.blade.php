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
    <div class="mb-3 text-start">
        <label for="profile_image" class="form-label w-100">プロフィール画像</label>
        <input type="file" class="form-control w-100 mt-1" id="profile_image" name="profile_image">
        <progress class="upload-progress" value="0" max="100" style="width:100%;margin-top:10px;"></progress>
        <img class="profile-image-preview" src="{{ $teacher && $teacher->profile_image ? asset('storage/'.$teacher->profile_image) : asset('images/default.png') }}" alt="プレビュー" style="max-width:200px;max-height:200px;display:block;margin-top:10px;">
    </div>
    <div class="mb-3 text-start">
        <label for="education" class="form-label w-100">学歴</label>
        <input id="education" type="text" name="education" class="form-control w-100 mt-1" value="{{ old('education', $teacher->education ?? '') }}">
        @error('education')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 text-start">
        <label for="current_school" class="form-label w-100">在学中の学校</label>
        <input id="current_school" type="text" name="current_school" class="form-control w-100 mt-1" value="{{ old('current_school', $teacher->current_school ?? '') }}">
        @error('current_school')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 text-start">
        <label for="trial_lesson" class="form-label w-100">プレ授業欄</label>
        <textarea id="trial_lesson" name="trial_lesson" class="form-control w-100 mt-1" rows="3">{{ old('trial_lesson', $teacher->trial_lesson ?? '') }}</textarea>
        @error('trial_lesson')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 text-start">
        <label for="estimated_hourly_rate" class="form-label w-100">推定時給（円）</label>
        <input id="estimated_hourly_rate" type="number" name="estimated_hourly_rate" class="form-control w-100 mt-1" value="{{ old('estimated_hourly_rate', $teacher->estimated_hourly_rate ?? '') }}" min="0">
        @error('estimated_hourly_rate')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mt-4 text-center">
        <button type="submit" class="btn-blue-box" style="font-size:1.12rem; min-width:180px;">保存</button>
    </div>
</form>
</div>
