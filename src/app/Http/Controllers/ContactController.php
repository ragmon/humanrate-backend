<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
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

    public function updateContacts(Request $request)
    {
        $this->validate($request, [
            'contacts' => 'required|array',
            'contacts.*.phone' => 'string|max:32',
            'contacts.*.name' => 'string|max:255',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $contacts = $request->post('contacts');

        $user->contacts()->createMany($contacts);

        return response()->json();
    }
}
