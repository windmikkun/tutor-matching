<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Teacher API テストビュー</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        pre { background: #f5f5f5; padding: 1em; border-radius: 6px; }
        button { padding: 0.5em 1em; font-size: 1em; margin-top: 1em; }
        .endpoint { color: #0074d9; }
    </style>
</head>
<body>
    <h1>Teacher API テストビュー</h1>

    <div style="margin-bottom:2em;">
        <h2>新しい先生を登録（POST /api/teachers）</h2>
        <form id="createTeacherForm">
            <label>user_id: <input type="number" name="user_id" required></label><br>
            <label>姓（first_name）: <input type="text" name="first_name" required></label><br>
            <label>名（last_name）: <input type="text" name="last_name" required></label><br>
            <button type="submit">登録</button>
        </form>
        <pre id="createResult">（ここに登録結果が表示されます）</pre>
    </div>

    <div style="margin-bottom:2em;">
        <strong>エンドポイント:</strong>
        <span class="endpoint">GET /api/teachers</span>
        <button id="fetchBtn">先生一覧を取得</button>
        <h2>APIレスポンス</h2>
        <pre id="result">（ここに結果が表示されます）</pre>
    </div>

    <h2>使い方メモ</h2>
    <ul>
        <li>この画面はフロントエンド実装者やAPIテスト用です。</li>
        <li>「先生一覧を取得」ボタンを押すと、APIからデータを取得します。</li>
        <li>APIの仕様：<code>GET /api/teachers</code> で全先生データ（JSON）を取得</li>
    </ul>

    <script>
        // 先生一覧取得
        document.getElementById('fetchBtn').onclick = async function() {
            const result = document.getElementById('result');
            result.textContent = '読み込み中...';
            try {
                const res = await fetch('/api/teachers');
                const data = await res.json();
                result.textContent = JSON.stringify(data, null, 2);
            } catch (e) {
                result.textContent = 'エラー: ' + e;
            }
        };

        // 新規先生登録
        document.getElementById('createTeacherForm').onsubmit = async function(e) {
            e.preventDefault();
            const form = e.target;
            const data = Object.fromEntries(new FormData(form));
            document.getElementById('createResult').textContent = '登録中...';
            try {
                const res = await fetch('/api/teachers', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const json = await res.json();
                document.getElementById('createResult').textContent = JSON.stringify(json, null, 2);
            } catch (err) {
                document.getElementById('createResult').textContent = 'エラー: ' + err;
            }
        };
    </script>
</body>
</html>
