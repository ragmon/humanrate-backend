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

    /**
     * Синхронизация контактной книжки.
     * С приложения отправляется список всех контактов и добавляются на бэке в БД те кто не существуют или обновляются
     * имена существующих контактов.
     */
    // TODO: сделать поддержку нескольких номеров телефонов для 1 контакта
    public function syncContacts(Request $request)
    {
        $this->validate($request, [
            'contacts' => 'required|array',
            'contacts.*.phone' => 'string|max:32',
            'contacts.*.name' => 'string|max:255',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $contacts = $request->post('contacts');

        foreach ($contacts as $contact) {
            /** @var  $contactEntity */
            if ($contactEntity = $user->contacts()->where('phone', $contact['phone'])->first()) {
                $contactEntity->update($contact->only(['name']));
            } else {
                $user->contacts()->create($contact);
            }
        }

        return response()->json();
    }

    /**
     * Получаем список всех контактов которые были синхронизированы в БД бэка ранее
     */
    public function getContacts(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $contacts = $user->contacts;

        return response()->json($contacts->toArray());
    }
}
