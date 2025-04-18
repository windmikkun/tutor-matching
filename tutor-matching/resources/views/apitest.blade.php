<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>APIテストフォーム</title>
    <style>
        body { font-family: sans-serif; }
        form { margin-bottom: 2em; }
        input, button { margin: 0.2em 0; display: block; }
        pre { background: #f4f4f4; padding: 1em; border-radius: 4px; }
        pre.success { border-left: 6px solid #4caf50; color: #256029; }
        pre.error { border-left: 6px solid #f44336; color: #b71c1c; background: #fff0f0; }
        pre.info { border-left: 6px solid #2196f3; color: #0d47a1; background: #f0f8ff; }
    </style>
</head>
<body>
    <h1>ユーザー登録APIテスト</h1>
    <form id="registerForm">
        <input type="email" name="email" placeholder="メールアドレス" required><br>
        <input type="password" name="password" placeholder="パスワード" required><br>
        <input type="password" name="password_confirmation" placeholder="パスワード確認" required><br>
        <select name="user_type" id="userTypeSelect" required>
    <option value="">ユーザータイプを選択</option>
    <option value="teacher">teacher</option>
    <option value="individual_employer">individual_employer</option>
    <option value="corporate_employer">corporate_employer</option>
</select><br>
<div id="teacherFields" style="display:none;">
    <input type="text" name="first_name" placeholder="名"><br>
    <input type="text" name="last_name" placeholder="姓"><br>
    <input type="text" name="subject" placeholder="科目 (任意)"><br>
    <input type="text" name="grade_level" placeholder="学年 (任意)"><br>
</div>
<div id="employerFields" style="display:none;">
    <input type="text" name="name" placeholder="雇用者名"><br>
</div>
        <button type="submit">登録</button>
    </form>
    <div class="response-container" style="position:relative;">
        <button type="button" class="copy-btn" style="position:absolute;top:4px;right:8px;z-index:2;font-size:0.8em;">コピー</button>
        <pre id="registerResult" class="info" style="padding-right:4em;"></pre>
    </div>

    <h1>ログインAPIテスト</h1>
    <form id="loginForm">
        <input type="email" name="email" placeholder="メールアドレス" required><br>
        <input type="password" name="password" placeholder="パスワード" required><br>
        <button type="submit">ログイン</button>
    </form>
    <div class="response-container" style="position:relative;">
        <button type="button" class="copy-btn" style="position:absolute;top:4px;right:8px;z-index:2;font-size:0.8em;">コピー</button>
        <pre id="loginResult" class="info" style="padding-right:4em;"></pre>
    </div>

    <h1>ユーザー情報取得APIテスト</h1>
    <button id="userBtn">ユーザー情報取得</button>
    <div class="response-container" style="position:relative;">
        <button type="button" class="copy-btn" style="position:absolute;top:4px;right:8px;z-index:2;font-size:0.8em;">コピー</button>
        <pre id="userResult" class="info" style="padding-right:4em;"></pre>
    </div>

    <h1>ログアウトAPIテスト</h1>
    <button id="logoutBtn">ログアウト</button>
    <div class="response-container" style="position:relative;">
        <button type="button" class="copy-btn" style="position:absolute;top:4px;right:8px;z-index:2;font-size:0.8em;">コピー</button>
        <pre id="logoutResult" class="info" style="padding-right:4em;"></pre>
    </div>

    <script>
    // コピー機能
    document.querySelectorAll('.response-container').forEach(function(container) {
        const btn = container.querySelector('.copy-btn');
        const pre = container.querySelector('pre');
        btn.addEventListener('click', function() {
            if (!pre.textContent) return;
            navigator.clipboard.writeText(pre.textContent).then(() => {
                const orig = btn.textContent;
                btn.textContent = 'コピーしました!';
                setTimeout(() => { btn.textContent = orig; }, 1200);
            });
        });
    });
    let accessToken = '';
    // ユーザータイプによる入力欄切替
    document.getElementById('userTypeSelect').addEventListener('change', function() {
        const type = this.value;
        document.getElementById('teacherFields').style.display = (type === 'teacher') ? '' : 'none';
        document.getElementById('employerFields').style.display = (type === 'individual_employer' || type === 'corporate_employer') ? '' : 'none';
    });
    // 登録
    document.getElementById('registerForm').onsubmit = async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = {
            email: form.email.value,
            password: form.password.value,
            password_confirmation: form.password_confirmation.value,
            user_type: form.user_type.value,
        };
        if (form.user_type.value === 'teacher') {
            data.first_name = form.first_name.value;
            data.last_name = form.last_name.value;
            data.subject = form.subject.value;
            data.grade_level = form.grade_level.value;
        } else if (form.user_type.value === 'individual_employer' || form.user_type.value === 'corporate_employer') {
            data.name = form.name.value;
        }
        const res = await fetch('/api/register', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const text = await res.text();
        const pre = document.getElementById('registerResult');
        pre.className = res.ok ? 'success' : 'error';
        try {
            const json = JSON.parse(text);
            pre.textContent = JSON.stringify(json, null, 2);
            if (json.access_token) accessToken = json.access_token;
        } catch { pre.textContent = text; }
    };

    // ログイン
    document.getElementById('loginForm').onsubmit = async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = {
            email: form.email.value,
            password: form.password.value
        };
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const text = await res.text();
        const pre = document.getElementById('loginResult');
        pre.className = res.ok ? 'success' : 'error';
        try {
            const json = JSON.parse(text);
            pre.textContent = JSON.stringify(json, null, 2);
            if (json.access_token) accessToken = json.access_token;
        } catch { pre.textContent = text; }
    };
    // ユーザー情報取得
    document.getElementById('userBtn').onclick = async function() {
        const pre = document.getElementById('userResult');
        if (!accessToken) {
            pre.textContent = '先にログインまたは登録してください';
            pre.className = 'error';
            return;
        }
        const res = await fetch('/api/me', {
            headers: { 'Authorization': 'Bearer ' + accessToken }
        });
        const text = await res.text();
        pre.className = res.ok ? 'success' : 'error';
        try {
            const json = JSON.parse(text);
            pre.textContent = JSON.stringify(json, null, 2);
        } catch { pre.textContent = text; }
    };
    // ログアウト
    document.getElementById('logoutBtn').onclick = async function() {
        const pre = document.getElementById('logoutResult');
        if (!accessToken) {
            pre.textContent = '先にログインまたは登録してください';
            pre.className = 'error';
            return;
        }
        const res = await fetch('/api/logout', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + accessToken }
        });
        const text = await res.text();
        pre.className = res.ok ? 'success' : 'error';
        try {
            const json = JSON.parse(text);
            pre.textContent = JSON.stringify(json, null, 2);
        } catch { pre.textContent = text; }
        accessToken = '';
    };
    </script>
</body>
</html>
