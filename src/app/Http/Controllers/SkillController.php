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
}
