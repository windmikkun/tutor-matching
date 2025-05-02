<form method="POST" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="profile_image" class="form-label">プロフィール画像</label>
        <input type="file" accept="image/*" name="profile_image" id="profile_image" class="form-control">
    </div>

    <div class="mb-3">
        @php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    $imgPath = '';
    if (!empty($currentImage) && (Str::startsWith($currentImage, 'http') || (Storage::disk('public')->exists($currentImage)))) {
        $imgPath = Str::startsWith($currentImage, 'http') ? $currentImage : asset('storage/'.$currentImage);
    } else {
        $imgPath = asset('images/default.png');
    }
@endphp
<img id="profile_image_preview" class="profile-image-preview" src="{{ $imgPath }}" alt="プレビュー" style="max-width:200px;max-height:200px;">
    </div>
    <button type="submit" class="btn btn-primary">画像をアップロード</button>
</form>

