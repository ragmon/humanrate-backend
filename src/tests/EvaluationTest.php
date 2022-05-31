<?php

namespace Tests;

use App\Models\Contact;
use App\Models\Evaluation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class EvaluationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_evaluation_selfrate_error()
    {
        $user = User::factory()->create();
        $skill = Skill::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->json('POST', "/api/skills/{$skill->id}/evaluation", [
            'value' => mt_rand(1, 5)
        ], [
            'User-Identify' => $user->phone,
        ]);

        $response->seeStatusCode(400);
        $response->seeJson(['error' => "Can't rate yourself."]);
    }

    public function test_evaluation_not_your_contact_error()
    {
        $user = User::factory()->create();
        $skill = Skill::factory()->create();

        $response = $this->json('POST', "/api/skills/{$skill->id}/evaluation", [
            'value' => mt_rand(1, 5)
        ], [
            'User-Identify' => $user->phone,
        ]);

        $response->seeStatusCode(400);
        $response->seeJson(['error' => "It's not your contact."]);
    }

    public function test_evaluation()
    {
        $user = User::factory()->create();
        $skill = Skill::factory()->create();
        $value = mt_rand(1, 5);

        // добавляем пользователю контакт, чтобы он оценивал того кто есть в его контактах
        $user->contacts()->create([
            'phone' => $skill->user->phone,
            'name' => $skill->user->name,
        ]);

        $response = $this->json('POST', "/api/skills/{$skill->id}/evaluation", [
            'value' => $value,
        ], [
            'User-Identify' => $user->phone,
        ]);

        $response->seeStatusCode(200);
        $this->seeInDatabase('evaluations', [
            'value' => $value,
            'user_id' => $user->id,
            'skill_id' => $skill->id,
        ]);
    }

    public function test_get_evaluations_list()
    {
        $user = User::factory()->create();
        $evaluations = Evaluation::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->json('GET', "/api/user/{$user->id}/evaluations", [], [
            'User-Identify' => $user->phone,
        ]);

        $response->assertResponseStatus(200);
        foreach ($evaluations as $evaluation) {
            $this->seeInDatabase('evaluations', [
                'user_id' => $user->id,
                'skill_id' => $evaluation['skill_id'],
//                'value' => $evaluation['value'],
            ]);
        }
    }
}
