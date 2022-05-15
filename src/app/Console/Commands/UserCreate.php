<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Evaluation;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Console\Command;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание нового пользователя';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var User $user */
        $user = User::factory()
            ->has(Contact::factory()->count(10))
            ->has(Skill::factory()
                ->count(5)
                ->has(Evaluation::factory()->count(rand(1, 5)))
            )
            ->create();

        $this->info("Created user ID #{$user->id} \"{$user->name}\" with phone: {$user->phone}");
    }
}
