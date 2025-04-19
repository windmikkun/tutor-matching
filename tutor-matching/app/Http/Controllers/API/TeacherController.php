<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $user = $request->user();
    // employer系ユーザーのみ許可
    if (!in_array($user->user_type, ['individual_employer', 'corporate_employer'])) {
        return response()->json(['message' => '講師リストは雇用者のみ閲覧可能です'], 403);
    }
    // 講師一覧を返す（idをteacher_idとして返す）
    $teachers = \App\Models\Teacher::all()->map(function($teacher) {
        return [
            'teacher_id' => $teacher->id,
            'first_name' => $teacher->first_name,
            'last_name' => $teacher->last_name,
            'profile_image' => $teacher->profile_image,
            'phone' => $teacher->phone,
            'address' => $teacher->address,
            'university' => $teacher->university,
            'faculty' => $teacher->faculty,
            'graduation_year' => $teacher->graduation_year,
            'teaching_experience' => $teacher->teaching_experience,
            'subject' => $teacher->subject,
            'grade_level' => $teacher->grade_level,
            // 必要に応じて他のカラムも追加
        ];
    });
    return response()->json($teachers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. 入力内容のバリデーション（チェック）
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
        ]);

        // 2. Teacherモデルを使って新しい先生データを作成
        $teacher = Teacher::create($validated);

        // 3. 作成したデータをJSON形式で返す（201: Created）
        return response()->json($teacher, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //指定された先生（Teacher)の情報をJSON形式で返す
        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //1.入力内容のバリデーション
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max;50',
            'last_name' => 'sometimes|required|string|max:50'
        ]);

        //2.ログインユーザーのID取得
        $user_id = $request->user()->id;

        //3. 既にプロフィールが存在する場合  409
        //  user_id一つに対して一つのプロフにするため
        if(Teacher::where('user_id', $user_id->exists())){
            return response()->json(['message' => '既に教師プロフィールが存在します。'], 409);
        }

        //4.新しい講師データを作成(user_id自動セット)
        $teacher = Teacher::create(array_merge($validated, ['user_id' => $user_id]));
        
        //5.jsonで返す
        return response()->json($teacher, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //指定された先生データを削除
        $teacher->delete();

        //削除成功のメッセージを返す
        return response()->json(['message' => '削除しました'], 200);
    }
}
