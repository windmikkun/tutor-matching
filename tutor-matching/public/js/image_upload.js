// 画像アップロード（3MB→500KBフロント圧縮＆プレビュー＆プログレスバー）
// 必要: browser-image-compression（npm/yarnで追加推奨）

async function compressAndPreviewImage(inputElem, maxSizeKB = 200) {
    // inputから同じform内の要素を探す
    const form = inputElem.closest('form');
    const progressElem = form ? form.querySelector('.upload-progress') : null;
    const previewElem = form ? form.querySelector('img.profile-image-preview') : null;
    if (!previewElem || !progressElem) {
        alert('アップロード用プレビュー要素が見つかりません');
        return;
    }
    const file = inputElem.files[0];
    if (!file) return;
    // 拡張子＋MIMEタイプチェック（JPEG/PNG/WebP/AVIFのみ対応）
    const allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
    const allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
    const ext = file.name.split('.').pop().toLowerCase();
    console.log('ext:', ext, 'type:', file.type);
    if (!allowedExts.includes(ext) || !allowedMimes.includes(file.type)) {
        alert('JPEG/PNG/WebP/AVIFのみ対応です。');
        inputElem.value = '';
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('画像は5MB以下にしてください。');
        inputElem.value = '';
        return;
    }
    progressElem.value = 0;
    progressElem.max = 100;
    
    // 圧縮処理
    const options = {
        maxSizeMB: maxSizeKB / 1024,
        maxWidthOrHeight: 1024,
        useWebWorker: true,
        onProgress: (p) => progressElem.value = p
    };
    progressElem.value = 10;
    let compressedFile;
    try {
        compressedFile = await imageCompression(file, options);
    } catch (e) {
        alert('画像圧縮に失敗しました');
        return;
    }
    progressElem.value = 80;
    // プレビュー表示
    const reader = new FileReader();
    reader.onload = function(e) {
        previewElem.src = e.target.result;
        progressElem.value = 100;
    };
    reader.readAsDataURL(compressedFile);
    // 圧縮後ファイルをinputに保持（FormData送信用）
    inputElem.compressedFile = compressedFile;
}

// フォーム送信時に圧縮済みファイルを送る
async function handleProfileImageUploadFormSubmit(e, inputElem) {
    e.preventDefault();
    const form = e.target;
    const compressedFile = inputElem.compressedFile || inputElem.files[0];
    if (!compressedFile) {
        alert('画像を選択してください');
        return;
    }
    const formData = new FormData(form);
    formData.set('profile_image', compressedFile, compressedFile.name);
    // プログレス表示
    const progressElem = form.querySelector('.upload-progress');
    progressElem.value = 0;
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(async r => {
        let res;
        try {
            res = await r.json();
        } catch (e) {
            res = {};
        }
        progressElem.value = 100;
        if (r.ok && res.success) {
            alert('アップロード完了');
            window.location.reload();
        } else {
            // サーバー側のエラーメッセージを優先表示
            alert(res.error || res.message || 'アップロード失敗（ファイル形式やサイズを確認してください）');
        }
    }).catch(() => {
        alert('アップロード失敗（通信エラー）');
    });
}

// windowでグローバル化
window.compressAndPreviewImage = compressAndPreviewImage;
window.handleProfileImageUploadFormSubmit = handleProfileImageUploadFormSubmit;

document.addEventListener('DOMContentLoaded', function() {
  var input = document.getElementById('profile_image');
  if (input) {
    input.addEventListener('change', function() {
      console.log('onchange fired');
      compressAndPreviewImage(this, 200);
      compressAndPreviewImage(input, 200);
    });
  }
});
