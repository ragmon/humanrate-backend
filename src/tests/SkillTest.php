<?php

namespace Tests;

use App\Models\Contact;
use App\Models\Evaluation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class SkillTest extends TestCase
{
    use DatabaseMigrations;

    public function test_store_skill()
    {
        $user = User::factory()->create();
        $skills = Skill::factory()->count(3)->make();

        $response = $this->json('POST', '/api/skills', [
            'skills' => $skills,
        ], [
            'User-Identify' => $user->phone,
        ]);

        $response->assertResponseStatus(Response::HTTP_CREATED);
        foreach ($skills as $skill) {
            $this->seeInDatabase('skills', [
                'user_id' => $user->id,
                'name' => $skill['name'],
            ]);
        }
    }

    public function test_delete_skill()
    {
        $user = User::factory()->create();
        $skill = Skill::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->json('DELETE', '/api/skills/' . $skill->id, [], [
            'User-Identify' => $user->phone,
        ]);

        $response->assertResponseStatus(Response::HTTP_OK);
        $this->notSeeInDatabase('skills', [
            'id' => $skill->id,
        ]);
    }

    public function test_list_skill()
    {
        $user = User::factory()->create();
        $skills = Skill::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->json('GET', '/api/skills', [], [
            'User-Identify' => $user->phone,
        ]);

        $response->assertResponseStatus(Response::HTTP_OK);
        foreach ($skills as $skill) {
            $this->seeJsonContains([
                'name' => $skill['name'],
                'user_id' => $user->id,
            ]);
        }
    }

    public function test_evaluation_skill()
    {
        $user = User::factory()->create();
        $evaluationUser = User::factory()->create();
        $skill = Skill::factory()->create([
            'user_id' => $evaluationUser->id,
        ]);
        $contact = Contact::factory()->create([
            'user_id' => $user->id,
            'phone' => $evaluationUser->phone,
        ]);
        $evaluation = Evaluation::factory()->make();

        $response = $this->json('POST', '/api/skills/evaluation/' . $skill->id, $evaluation->toArray(), [
            'User-Identify' => $user->phone,
        ]);

        $response->assertResponseStatus(Response::HTTP_OK);
        $this->seeInDatabase('evaluations', [
            'value' => $evaluation->value,
            'skill_id' => $skill->id,
            'user_id' => $user->id,
        ]);
    }
}
