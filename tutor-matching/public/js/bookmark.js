// public/js/bookmark.js

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.bookmark-btn').forEach(function (btn) {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            if (btn.classList.contains('loading')) return;
            btn.classList.add('loading');

            const url = btn.dataset.url;
            const method = btn.dataset.method || 'POST';
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                // デバッグ用: 送信データを表示
                console.log('request body:', btn.dataset.body);
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: btn.dataset.body || null
                });
                const data = await res.json();
                if (res.ok) {
                    // 状態を切り替え（例：色やテキスト）
                    btn.classList.toggle('bookmarked', data.bookmarked);
                    // 色切り替え
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
                    // デバッグ用
                    console.log(data);
                } else {
                    alert(data.message || 'エラーが発生しました');
                }
            } catch (err) {
                alert('通信エラー');
            } finally {
                btn.classList.remove('loading');
            }
        });
    });
});
