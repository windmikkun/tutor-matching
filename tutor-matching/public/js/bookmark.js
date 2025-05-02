// public/js/bookmark.js

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.bookmark-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (btn.disabled) return;
            btn.disabled = true;

            const isBookmarked = btn.classList.contains('bookmarked');
            let url = btn.dataset.url;
            let method = btn.dataset.method || 'POST';
            let body = btn.dataset.body ? JSON.parse(btn.dataset.body) : {};
            const type = btn.dataset.type;
            let bookmarkId = btn.dataset.bookmarkId;

            if (isBookmarked && bookmarkId) {
                // 解除リクエスト
                url = `/bookmarks/${bookmarkId}`;
                method = 'DELETE';
                body = undefined;
            }

            console.log('request body:', JSON.stringify(body));

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                ...(body !== undefined ? { body: JSON.stringify(body) } : {}),
            })
                .then(res => res.json().then(data => ({ ok: res.ok, status: res.status, data })))
                .then(({ ok, status, data }) => {
                    if (ok) {
                        // ボタン状態切り替え
                        btn.classList.toggle('bookmarked', data.bookmarked);
                        btn.classList.toggle('btn-blue-box', !data.bookmarked);
                        btn.classList.toggle('btn-gray-box', data.bookmarked);

                        // 星の切り替え
                        const starSpan = btn.querySelector('.bookmark-star');
                        if (starSpan) {
                            starSpan.textContent = data.bookmarked ? '★' : '☆';
                        }
                        // カウントの更新
                        const countSpan = btn.querySelector('.bookmark-count');
                        if (countSpan && typeof data.bookmarkCount !== 'undefined') {
                            countSpan.textContent = data.bookmarkCount;
                        }
                        // data-bookmark-id, url, method, bodyの切り替え
                        if (data.bookmarked && data.bookmarkId) {
                            btn.dataset.bookmarkId = data.bookmarkId;
                            btn.dataset.url = `/bookmarks/${data.bookmarkId}`;
                            btn.dataset.method = 'DELETE';
                            btn.dataset.body = '';
                        } else {
                            btn.removeAttribute('data-bookmark-id');
                            btn.dataset.url = btn.dataset.originalStoreUrl || '/bookmarks';
                            btn.dataset.method = 'POST';
                            btn.dataset.body = btn.getAttribute('data-original-body') || '';
                        }
                        // デバッグ用
                        console.log(data);
                    } else {
                        alert(data.message || 'エラーが発生しました');
                    }
                })
                .catch((err) => {
                    alert('通信エラー');
                })
                .finally(() => {
                    btn.disabled = false;
                });
        });
    });
});
