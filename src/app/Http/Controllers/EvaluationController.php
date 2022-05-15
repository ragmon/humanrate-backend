<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
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
