<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Evaluation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $skills = $user->skills;

        return response()->json($skills);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'skills' => 'required|array',
            'skills.*.name' => 'string|max:32',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $skills = $request->post('skills');

        $user->skills()->createMany($skills);

        return response()->json([], Response::HTTP_CREATED);
    }

    public function delete(int $skillId)
    {
        /** @var User $user */
        $user = Auth::user();
        $skill = $user->skills()->where('id', $skillId)->firstOrFail();

        $skill->delete();

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function evaluation(Request $request, int $skillId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Self rate protection
        if ($user->skills()->where('id', $skillId)->exists()) {
            throw new \Exception("Can't rate yourself.");
        }

        // Only contact user can't be rated
        /** @var Skill $skill */
        $skill = Skill::where('id', $skillId)->firstOrFail();
        if (!$user->contacts()->where('phone', $skill->user->phone)->exists()) {
            throw new \Exception("It's not your contact.");
        }

        $evaluation = new Evaluation();
        $evaluation->value = $request->post('value');
        $evaluation->user()->associate($user);
        $evaluation->skill()->associate($skill);
        $evaluation->save();

        return response()->json();
    }
}
