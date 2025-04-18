<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>雇用者プロフィール登録（本番用）</title>
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
    <h1>雇用者プロフィール登録（本番用）</h1>
    <div class="section" style="background:#e0f7fa; border:1px solid #00bcd4; padding:1em; margin-bottom:2em;">
        <button id="loginBtn">ワンクリックでログイン（テストユーザー）</button>
        <span id="loginStatus">（未ログイン）</span>
    </div>
    <div style="background:#ffe0e0; border:1px solid #ffaaaa; padding:1em; margin-bottom:2em;">
        <strong>【注意】</strong><br>
        このページでは、ログイン中のユーザーのみが自分の雇用者プロフィールを1つだけ登録できます。<br>
        user_idは自動的に設定されるため、入力不要です。
    </div>
    <div class="section">
        <h2>雇用者プロフィールを登録（POST /api/employers）</h2>
        <form id="createEmployerForm">
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
        <span class="endpoint">GET /api/employers/me</span>
        <button id="fetchBtn">自分の雇用者プロフィールを取得</button>
        <h2>APIレスポンス</h2>
        <pre id="result">（ここに結果が表示されます）</pre>
    </div>
    <script>
        document.getElementById('loginBtn').onclick = async function() {
            const loginStatus = document.getElementById('loginStatus');
            loginStatus.textContent = 'ログイン中...';
            try {
                await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({ email: 'example@example.com', password: 'password' })
                });
                if (res.ok) {
                    loginStatus.textContent = 'ログイン済み';
                } else {
                    loginStatus.textContent = 'ログイン失敗';
                }
            } catch (e) {
                loginStatus.textContent = 'ログインエラー';
            }
        };
        document.getElementById('createEmployerForm').onsubmit = async function(e) {
            e.preventDefault();
            const form = e.target;
            const data = Object.fromEntries(new FormData(form));
            document.getElementById('createResult').textContent = '登録中...';
            try {
                const res = await fetch('/api/employers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                document.getElementById('createResult').textContent = JSON.stringify(result, null, 2);
            } catch (e) {
                document.getElementById('createResult').textContent = 'エラー: ' + e.message;
            }
        };
        document.getElementById('fetchBtn').onclick = async function() {
            document.getElementById('result').textContent = '取得中...';
            try {
                const res = await fetch('/api/employers/me', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'include',
                });
                const result = await res.json();
                document.getElementById('result').textContent = JSON.stringify(result, null, 2);
            } catch (e) {
                document.getElementById('result').textContent = 'エラー: ' + e.message;
            }
        };
    </script>
</body>
</html>
