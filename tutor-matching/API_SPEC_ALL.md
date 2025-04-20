# API仕様書（全体まとめ）

---

## 共通事項
- ベースURL: `/api`
- 認証: `auth:sanctum`（ログイン必須）
- レスポンス: JSON

---

## 認証系

### ユーザー登録
- **POST** `/api/register`
- **リクエスト例**:
```json
{
  "name": "ユーザー名",
  "email": "user@example.com",
  "password": "password"
}
```
- **レスポンス例**:
```json
{
  "user": { ... },
  "token": "..."
}
```

### ログイン
- **POST** `/api/login`
- **リクエスト例**:
```json
{
  "email": "user@example.com",
  "password": "password"
}
```
- **レスポンス例**:
```json
{
  "user": { ... },
  "token": "..."
}
```

### ログアウト
- **POST** `/api/logout`
- **レスポンス例**:
```json
{
  "message": "Logged out"
}
```

### ログインユーザー情報取得
- **GET** `/api/me`
- **レスポンス例**:
```json
{
  "id": 1,
  "name": "ユーザー名",
  "email": "user@example.com",
  ...
}
```

---

## 講師（Teacher）API

### 講師一覧取得
- **GET** `/api/teachers`
- employer系ユーザーのみ利用可能
- **レスポンス例**:
```json
[
  {
    "teacher_id": 1,
    "first_name": "太郎",
    "last_name": "山田",
    ...
  }
]
```

### 講師登録
- **POST** `/api/teachers`
- **リクエスト例**:
```json
{
  "first_name": "太郎",
  "last_name": "山田"
}
```
- **レスポンス例**:
```json
{
  "teacher_id": 1,
  "first_name": "太郎",
  "last_name": "山田",
  ...
}
```

### 講師詳細取得
- **GET** `/api/teachers/{teacher_id}`

### 講師削除
- **DELETE** `/api/teachers/{teacher_id}`

---

## 雇用者（Employer）API

### 雇用者一覧取得
- **GET** `/api/employers`
- teacherユーザーのみ利用可能
- **レスポンス例**:
```json
[
  {
    "employer_id": 1,
    "name": "株式会社サンプル",
    ...
  }
]
```

### 雇用者登録
- **POST** `/api/employers`
- **リクエスト例**:
```json
{
  "name": "株式会社サンプル"
}
```
- **レスポンス例**:
```json
{
  "employer_id": 1,
  "name": "株式会社サンプル",
  ...
}
```

### 雇用者詳細取得
- **GET** `/api/employers/{employer_id}`

### 雇用者削除
- **DELETE** `/api/employers/{employer_id}`

---

## ブックマークAPI

### ブックマーク一覧取得
- **GET** `/api/bookmarks`
- **レスポンス例**:
```json
[
  {
    "bookmark_id": 1,
    "user_id": 5,
    "bookmarkable_type": "Teacher",
    "bookmarkable_id": 2,
    "created_at": "2025-04-20T11:00:00Z",
    "updated_at": "2025-04-20T11:00:00Z"
  }
]
```

### ブックマーク登録
- **POST** `/api/bookmarks`
- teacherはemployerを、employerはteacherをブックマーク可能
- **リクエスト例**:
  - teacherの場合: `{ "employer_id": 1 }`
  - employerの場合: `{ "teacher_id": 2 }`
- **レスポンス例**:
```json
{
  "id": 3,
  "user_id": 5,
  "bookmarkable_type": "Employer",
  "bookmarkable_id": 1,
  "created_at": "2025-04-20T12:00:00Z",
  "updated_at": "2025-04-20T12:00:00Z"
}
```

### ブックマーク削除
- **DELETE** `/api/bookmarks/{bookmark_id}`
- **レスポンス例**:
```json
{
  "message": "ブックマークを削除しました"
}
```

---

## その他API（サーベイ・スカウト・契約等）

- `/api/surveys` などもRESTfulな設計（index, store, show, update, destroy）
- 詳細仕様は各コントローラ・モデルの定義を参照

---

## エラー・バリデーション例
- 権限エラー:
```json
{
  "message": "権限がありません",
  "error": "forbidden"
}
```
- バリデーションエラー:
```json
{
  "message": "バリデーションエラー",
  "errors": { "field": ["エラー内容"] }
}
```
- 既に存在:
```json
{
  "message": "既に存在します",
  "error": "already_exists"
}
```

---

## 備考
- 各APIの詳細なパラメータ・バリデーション・レスポンスはコントローラ実装に準拠
- `bookmarkable_type`は "Teacher" または "Employer" のいずれか
- 仕様は今後の要件追加で拡張可能
