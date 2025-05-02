<div class="form-bottom-space" style="margin-top:2.2rem;">
  <div class="card shadow-sm" style="border-radius:1.5rem; background:#fff; max-width:480px; margin:0 auto;">
    <form method="{{ $method ?? 'POST' }}" action="{{ $action }}" style="max-width:400px; margin:0 auto;" enctype="multipart/form-data">
    @csrf
    @if(isset($method_field))
        @method($method_field)
    @endif
    

    <div class="mb-3 text-start">
        <label for="profile_image" class="form-label w-100">プロフィール画像</label>
        <input type="file" class="form-control w-100 mt-1" id="profile_image" name="profile_image">
        <progress id="profile_image_progress" class="upload-progress" value="0" max="100" style="width:100%;margin-top:10px;"></progress>
        <img id="profile_image_preview" class="profile-image-preview" src="{{ $teacher && $teacher->profile_image ? asset('storage/'.$teacher->profile_image) : asset('images/default.png') }}" alt="プレビュー" style="max-width:200px;max-height:200px;display:block;margin-top:10px;">
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
        <label for="trial_lesson" class="form-label w-100">プレ授業 (Youtube動画URL)</label>
        <input id="trial_lesson" type="url" name="trial_lesson" class="form-control w-100 mt-1" value="{{ old('trial_lesson', $teacher->trial_lesson ?? '') }}" placeholder="https://www.youtube.com/watch?v=xxxxxxx">
        @error('trial_lesson')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 text-start">
        <label for="self_appeal" class="form-label w-100">自己アピール</label>
        <textarea id="self_appeal" name="self_appeal" class="form-control w-100 mt-1" rows="3">{{ old('self_appeal', $teacher->self_appeal ?? '') }}</textarea>
        @error('self_appeal')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3 text-start">
        <label for="estimated_hourly_rate" class="form-label w-100">希望時給（円）</label>
        <input id="estimated_hourly_rate" type="number" name="estimated_hourly_rate" class="form-control w-100 mt-1" value="{{ old('estimated_hourly_rate', $teacher->estimated_hourly_rate ?? '') }}" min="0">
        @error('estimated_hourly_rate')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mt-4 text-center">
        <button type="submit" class="btn-blue-box" style="font-size:1.12rem; min-width:180px;">保存</button>
    </div>
</form>
</div>
