<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Evaluation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->has(Contact::factory()->count(30))
            ->has(Skill::factory()->count(5))
            ->has(Evaluation::factory()->count(50))
            ->count(10)
            ->create();
    }
}
