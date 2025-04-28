<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Employer;
use App\Models\ScoutRequest;

class ScoutRequestApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 雇用者が自分のスカウト一覧を取得できる
     */
    public function test_employer_can_get_own_scout_requests()
    {
        // 雇用者ユーザー・プロフィール作成
        $employerUser = User::factory()->create(['user_type' => 'employer']);
        $employer = Employer::factory()->create(['user_id' => $employerUser->id]);

        // 講師ユーザー・プロフィール作成
        $teacherUser = User::factory()->create(['user_type' => 'teacher']);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);

        // スカウト作成
        $scout = ScoutRequest::factory()->create([
            'employer_id' => $employer->id,
            'teacher_id' => $teacher->id,
            'message' => 'テストスカウト',
        ]);

        // employerとしてAPI叩く
        $response = $this->actingAs($employerUser)->getJson('/api/scout-requests');
        $response->assertStatus(200)
                 ->assertJsonFragment(['teacher_id' => $teacher->id]);
    }

    /**
     * 講師が自分宛てのスカウト一覧を取得できる
     */
    public function test_teacher_can_get_received_scout_requests()
    {
        $employerUser = User::factory()->create(['user_type' => 'employer']);
        $employer = Employer::factory()->create(['user_id' => $employerUser->id]);
        $teacherUser = User::factory()->create(['user_type' => 'teacher']);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $scout = ScoutRequest::factory()->create([
            'employer_id' => $employer->id,
            'teacher_id' => $teacher->id,
            'message' => 'テストスカウト',
        ]);

        $response = $this->actingAs($teacherUser)->getJson('/api/scout-requests');
        $response->assertStatus(200)
                 ->assertJsonFragment(['employer_id' => $employer->id]);
    }

    /**
     * 雇用者がスカウトを作成できる
     */
    public function test_employer_can_create_scout_request()
    {
        $employerUser = User::factory()->create(['user_type' => 'corporate_employer']);
        $employer = Employer::factory()->create(['user_id' => $employerUser->id]);
        $teacherUser = User::factory()->create(['user_type' => 'teacher']);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);

        $payload = [
            'teacher_id' => $teacher->id,
            'message' => 'スカウトテスト',
        ];

        $response = $this->actingAs($employerUser)->postJson('/api/scout-requests', $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['teacher_id' => $teacher->id]);
    }

    /**
     * 権限のないユーザーがスカウト一覧取得できない
     */
    public function test_other_user_cannot_access_scout_requests()
    {
        $user = User::factory()->create(['user_type' => 'student']);
        $response = $this->actingAs($user)->getJson('/api/scout-requests');
        $response->assertStatus(403);
    }
}
