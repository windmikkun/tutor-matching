<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Employer API テストビュー</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        pre { background: #f5f5f5; padding: 1em; border-radius: 6px; }
        button { padding: 0.5em 1em; font-size: 1em; margin: 0.5em 0; }
        input, select { margin: 0.2em 0; padding: 0.3em; }
        label { display: block; margin-top: 1em; }
        .endpoint { color: #0074d9; }
        .section { margin-bottom: 2em; }
    </style>
</head>
<body>
    <h1>Employer API テストビュー</h1>

    <div class="section" style="background:#e0f7fa; border:1px solid #00bcd4; padding:1em; margin-bottom:2em;">
        <button id="loginBtn">ワンクリックでログイン（テストユーザー）</button>
        <span id="loginStatus">（未ログイン）</span>
    </div>

    <div style="background:#ffe0e0; border:1px solid #ffaaaa; padding:1em; margin-bottom:2em;">
        <strong>【注意】</strong><br>
        通常の雇用者（個人・法人）登録は <b>auth_api.blade.php</b> の「ユーザー登録」フォームから行ってください。<br>
        このページの「新規雇用者登録」は<strong>管理者や開発者向けのテスト用</strong>です。<br>
        user_id には既存のユーザーIDを指定してください。
    </div>

    <div class="section">
        <h2>新しい雇用者を登録（POST /api/employers）</h2>
        <form id="createEmployerForm">
            <label>ユーザーID（既存の登録ユーザー）: <input type="number" name="user_id" required></label><br>
            <label>名前（name）: <input type="text" name="name" required></label><br>
            <label>タイプ（個人/法人）:
                <select name="type">
                    <option value="individual_employer">個人</option>
                    <option value="corporate_employer">法人</option>
                </select>
            </label><br>
            <button type="submit">登録</button>
        </form>
        <pre id="createResult">（ここに登録結果が表示されます）</pre>
    </div>

    <div class="section">
        <strong>エンドポイント:</strong>
        <span class="endpoint">GET /api/employers</span>
        <button id="fetchBtn">雇用者一覧を取得</button>
        <h2>APIレスポンス</h2>
        <pre id="result">（ここに結果が表示されます）</pre>
    </div>

    <div class="section">
        <h2>雇用者詳細取得（GET /api/employers/{id}）</h2>
        <label>雇用者ID: <input type="number" id="showEmployerId"></label>
        <button id="showBtn">詳細取得</button>
        <pre id="showResult">（ここに詳細が表示されます）</pre>
    </div>

    <div class="section">
        <h2>雇用者更新（PATCH /api/employers/{id}）</h2>
        <form id="updateEmployerForm">
            <label>雇用者ID: <input type="number" name="id" required></label><br>
            <label>新しい名前（name）: <input type="text" name="name" required></label><br>
            <button type="submit">更新</button>
        </form>
        <pre id="updateResult">（ここに更新結果が表示されます）</pre>
    </div>

    <div class="section">
        <h2>雇用者削除（DELETE /api/employers/{id}）</h2>
        <label>雇用者ID: <input type="number" id="deleteEmployerId"></label>
        <button id="deleteBtn">削除</button>
        <pre id="deleteResult">（ここに削除結果が表示されます）</pre>
    </div>

    <h2>使い方メモ</h2>
    <ul>
        <li>この画面はフロントエンド実装者やAPIテスト用です。</li>
        <li>各フォームやボタンでAPIリクエストを送り、結果を画面で確認できます。</li>
        <li>APIの仕様やリクエスト例も一緒に確認できます。</li>
    </ul>

    <script>
        // ワンクリックログイン（テストユーザー用）
        document.getElementById('loginBtn').onclick = async function() {
            const loginStatus = document.getElementById('loginStatus');
            loginStatus.textContent = 'ログイン中...';
            try {
                // Sanctum用: まずCSRFクッキーを取得
                await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        email: 'example@example.com', // ←テスト用ユーザーのメールに変更してください
                        password: 'password'         // ←テスト用ユーザーのパスワードに変更してください
                    })
                });
                if (res.ok) {
                    loginStatus.textContent = 'ログイン成功！';
                } else {
                    const json = await res.json();
                    loginStatus.textContent = 'ログイン失敗: ' + (json.message || res.status);
                }
            } catch (e) {
                loginStatus.textContent = 'ログインエラー: ' + e;
            }
        };

        // 雇用者一覧取得
        document.getElementById('fetchBtn').onclick = async function() {
            const result = document.getElementById('result');
            result.textContent = '読み込み中...';
            try {
                const res = await fetch('/api/employers', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include'
                });
                const contentType = res.headers.get('content-type') || '';
                const text = await res.text();
                if (contentType.includes('application/json') && text) {
                    const data = JSON.parse(text);
                    result.textContent = JSON.stringify(data, null, 2);
                } else {
                    result.textContent = 'エラー: サーバーからJSON以外のレスポンス、または空のレスポンスが返されました。';
                }
            } catch (e) {
                result.textContent = 'エラー: ' + e;
            }
        };

        // 新規雇用者登録
        document.getElementById('createEmployerForm').onsubmit = async function(e) {
            e.preventDefault();
            const form = e.target;
            const data = Object.fromEntries(new FormData(form));
            document.getElementById('createResult').textContent = '登録中...';
            try {
                const res = await fetch('/api/employers', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                    credentials: 'include'
                });
                const json = await res.json();
                document.getElementById('createResult').textContent = JSON.stringify(json, null, 2);
            } catch (err) {
                document.getElementById('createResult').textContent = 'エラー: ' + err;
            }
        };

        // 雇用者詳細取得
        document.getElementById('showBtn').onclick = async function() {
            const id = document.getElementById('showEmployerId').value;
            const result = document.getElementById('showResult');
            if (!id) { result.textContent = 'IDを入力してください'; return; }
            result.textContent = '取得中...';
            try {
                const res = await fetch('/api/employers/' + id, {
                    credentials: 'include'
                });
                const json = await res.json();
                result.textContent = JSON.stringify(json, null, 2);
            } catch (err) {
                result.textContent = 'エラー: ' + err;
            }
        };

        // 雇用者更新
        document.getElementById('updateEmployerForm').onsubmit = async function(e) {
            e.preventDefault();
            const form = e.target;
            const data = Object.fromEntries(new FormData(form));
            document.getElementById('updateResult').textContent = '更新中...';
            try {
                const res = await fetch('/api/employers/' + data.id, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ name: data.name }),
                    credentials: 'include'
                });
                const json = await res.json();
                document.getElementById('updateResult').textContent = JSON.stringify(json, null, 2);
            } catch (err) {
                document.getElementById('updateResult').textContent = 'エラー: ' + err;
            }
        };

        // 雇用者削除
        document.getElementById('deleteBtn').onclick = async function() {
            const id = document.getElementById('deleteEmployerId').value;
            const result = document.getElementById('deleteResult');
            if (!id) { result.textContent = 'IDを入力してください'; return; }
            result.textContent = '削除中...';
            try {
                const res = await fetch('/api/employers/' + id, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();
                result.textContent = JSON.stringify(json, null, 2);
            } catch (err) {
                result.textContent = 'エラー: ' + err;
            }
        };
    </script>
</body>
</html>
