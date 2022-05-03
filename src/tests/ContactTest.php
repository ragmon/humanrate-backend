<?php

namespace Tests;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use DatabaseMigrations;

    public function test_update_contacts()
    {
        $user = User::factory()->create();
        $contacts = Contact::factory()->count(10)->make();

        $response = $this->json('POST', '/api/contacts', [
            'contacts' => $contacts,
        ], [
            'User-Identify' => $user->phone,
        ]);

        $response->assertResponseStatus(200);
        foreach ($contacts as $contact) {
            $this->seeInDatabase('contacts', [
                'name' => $contact['name'],
                'phone' => $contact['phone'],
                'user_id' => $user->id,
            ]);
        }
    }

    public function test_get_contacts()
    {
        $user = User::factory()->create();
        $contacts = Contact::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->json('GET', '/api/contacts', [], [
            'User-Identify' => $user->phone,
        ]);

        $response->assertResponseStatus(200);
        foreach ($contacts as $contact) {
            $response->seeJsonContains([
                'name' => $contact['name'],
                'phone' => $contact['phone'],
                'user_id' => $user->id,
            ]);
        }
    }
}
