<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Auth API テストビュー</title>
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
    <h1>Auth API テストビュー</h1>
    <div class="section">
        <h2>ユーザー登録</h2>
        <form id="registerForm">
            <label>メール: <input type="email" name="email" required></label>
            <label>パスワード: <input type="password" name="password" required></label>
            <label>パスワード確認: <input type="password" name="password_confirmation" required></label>
            <label>ユーザー種別:
                <select name="user_type" id="userType">
                    <option value="teacher">teacher</option>
                    <option value="individual_employer">individual_employer</option>
                    <option value="corporate_employer">corporate_employer</option>
                </select>
            </label>
            <div id="teacherFields">
                <label>姓: <input type="text" name="first_name"></label>
                <label>名: <input type="text" name="last_name"></label>
            </div>
            <div id="employerFields" style="display:none;">
                <label>名前: <input type="text" name="name"></label>
            </div>
            <button type="submit">登録</button>
        </form>
        <pre id="registerResult">（ここに登録結果が表示されます）</pre>
    </div>

    <div class="section">
        <h2>ログイン</h2>
        <form id="loginForm">
            <label>メール: <input type="email" name="email" required></label>
            <label>パスワード: <input type="password" name="password" required></label>
            <button type="submit">ログイン</button>
        </form>
        <pre id="loginResult">（ここにログイン結果が表示されます）</pre>
    </div>

    <div class="section">
        <h2>ユーザー情報取得</h2>
        <button id="meBtn">ユーザー情報取得（/api/me）</button>
        <pre id="meResult">（ここに取得結果が表示されます）</pre>
    </div>

    <div class="section">
        <h2>ログアウト</h2>
        <button id="logoutBtn">ログアウト</button>
        <pre id="logoutResult">（ここにログアウト結果が表示されます）</pre>
    </div>

    <h2>使い方メモ</h2>
    <ul>
        <li>この画面はフロントエンド実装者やAPIテスト用です。</li>
        <li>登録・ログイン後はアクセストークンが自動で保存され、/api/meやログアウトで利用されます。</li>
        <li>APIの仕様やリクエスト例も一緒に確認できます。</li>
    </ul>

    <script>
    // ユーザー種別によるフォーム切り替え
    document.getElementById('userType').addEventListener('change', function() {
        const type = this.value;
        document.getElementById('teacherFields').style.display = (type === 'teacher') ? '' : 'none';
        document.getElementById('employerFields').style.display = (type !== 'teacher') ? '' : 'none';
    });

    // アクセストークン保存用
    let accessToken = '';

    // ユーザー登録
    document.getElementById('registerForm').onsubmit = async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = Object.fromEntries(new FormData(form));
        document.getElementById('registerResult').textContent = '登録中...';
        try {
            const res = await fetch('/api/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const json = await res.json();
            document.getElementById('registerResult').textContent = JSON.stringify(json, null, 2);
            if(json.access_token) accessToken = json.access_token;
        } catch(err) {
            document.getElementById('registerResult').textContent = 'エラー: ' + err;
        }
    };

    // ログイン
    document.getElementById('loginForm').onsubmit = async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = Object.fromEntries(new FormData(form));
        document.getElementById('loginResult').textContent = 'ログイン中...';
        try {
            const res = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const json = await res.json();
            document.getElementById('loginResult').textContent = JSON.stringify(json, null, 2);
            if(json.access_token) accessToken = json.access_token;
        } catch(err) {
            document.getElementById('loginResult').textContent = 'エラー: ' + err;
        }
    };

    // ユーザー情報取得
    document.getElementById('meBtn').onclick = async function() {
        document.getElementById('meResult').textContent = '取得中...';
        try {
            const res = await fetch('/api/me', {
                headers: { 'Authorization': 'Bearer ' + accessToken }
            });
            const json = await res.json();
            document.getElementById('meResult').textContent = JSON.stringify(json, null, 2);
        } catch(err) {
            document.getElementById('meResult').textContent = 'エラー: ' + err;
        }
    };

    // ログアウト
    document.getElementById('logoutBtn').onclick = async function() {
        document.getElementById('logoutResult').textContent = '処理中...';
        try {
            const res = await fetch('/api/logout', {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + accessToken }
            });
            const json = await res.json();
            document.getElementById('logoutResult').textContent = JSON.stringify(json, null, 2);
            accessToken = '';
        } catch(err) {
            document.getElementById('logoutResult').textContent = 'エラー: ' + err;
        }
    };
    </script>
</body>
</html>
