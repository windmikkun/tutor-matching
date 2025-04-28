<form action="{{ route('profile.image.upload') }}" method="POST" enctype="multipart/form-data" onsubmit="handleProfileImageUploadFormSubmit(event, this.querySelector('input[type=file]'))">
    @csrf
    <div class="mb-3">
        <label for="profile_image" class="form-label">プロフィール画像</label>
        <input type="file" accept="image/*" name="profile_image" id="profile_image" class="form-control"
               onchange="console.log('onchange fired');compressAndPreviewImage(this, document.getElementById('profile_image_preview'), document.getElementById('profile_image_progress'))">
    </div>
    <div class="mb-3">
        <progress id="profile_image_progress" class="upload-progress" value="0" max="100" style="width:100%"></progress>
    </div>
    <div class="mb-3">
        <img id="profile_image_preview" class="profile-image-preview" src="{{ $currentImage ?? asset('images/default.png') }}" alt="プレビュー" style="max-width:200px;max-height:200px;">
    </div>
    <button type="submit" class="btn btn-primary">画像をアップロード</button>
</form>
<script src="/js/image_upload.js"></script>
<!-- browser-image-compression CDN -->
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>
