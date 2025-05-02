
// async function handleProfileImageUploadFormSubmit(e, inputElem) {
//     e.preventDefault();
//     const form = e.target;
//     const progressElem = form.querySelector('.upload-progress');
//     const compressedFile = inputElem.compressedFile || inputElem.files[0];
//     if (!compressedFile) {
//         showToast('画像を選択してください', true);
//         if(progressElem) progressElem.value = 0;
//         ;
//     }
//     const formData = new FormData(form);
//     // 圧縮済みファイルがあれば差し替え
//     if (inputElem.compressedFile) {
//         formData.set('profile_image', inputElem.compressedFile, inputElem.files[0].name);
//     }
//     // methodをPOSTにし、_method=PUTを追加
//     formData.set('_method', 'PUT');
//     // CSRFトークンもFormDataに追加
//     const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
//     if (csrfToken) {
//         formData.set('_token', csrfToken);
//     }
//     if(progressElem) progressElem.value = 10;
//     try {
//         const response = await fetch(form.action, {
//             method: 'POST',
//             headers: {
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//             },
//             body: formData,
//             credentials: 'same-origin'
//         });
//         const text = await response.text();
//         let result;
//         try {
//             result = JSON.parse(text);
//         } catch(parseErr) {
//             showToast('通信エラー詳細: ' + text.slice(0, 200), true);
//             if(progressElem) progressElem.value = 0;
//              false;
//         }
//         if (response.ok && result.success) {
//             showToast('プロフィール画像を更新しました！', false);
//             if(progressElem) progressElem.value = 100;
//             setTimeout(() => window.location.reload(), 1200);
//         } else {
//             showToast((result && result.error) || 'アップロードに失敗しました', true);
//             if(progressElem) progressElem.value = 0;
//         }
//     } catch(err) {
//         showToast('通信エラーが発生しました: ' + err, true);
//         if(progressElem) progressElem.value = 0;
//     }
//      false;
// }

// 圧縮処理
// const options = {
//     maxSizeMB: maxSizeKB / 1024,
//     maxWidthOrHeight: 1024,
//     useWebWorker: true,
//     onProgress: (p) => progressElem.value = p
// };
// progressElem.value = 10;
// let compressedFile;
// try {
//     compressedFile = await imageCompression(file, options);
// } catch (e) {
//     alert('画像圧縮に失敗しました');
//     ;
// }
// progressElem.value = 80;
// // プレビュー表示
// const reader = new FileReader();
// reader.onload = function(e) {
//     previewElem.src = e.target.result;
//     progressElem.value = 100;
// };
// reader.readAsDataURL(compressedFile);
// // 圧縮後ファイルをinputに保持（FormData送信用）
// inputElem.compressedFile = compressedFile;
