<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Console\Commands\Manual;

use Faker\Factory;
use Illuminate\Console\Command;

/**
 * Class CreateRootCommand
 * @package App\Console\Commands\Manual
 */
class CreateRootCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:root {demo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating root user';

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
        $this->line('------');

        $faker = Factory::create();

        $name = $faker->firstName;
        $email = $this->argument('demo') == true ? 'demo@hyipium.com' : $this->ask('Root email', false);

        if (empty($email)) {
            $this->comment('Operation closed. Root email is empty.');
            return;
        }

        $checkExistsEmail = \App\Models\User::where('email', $email)->first();

        if (!empty($checkExistsEmail)) {
            $this->comment('Root with this email already exists');
            return;
        }

        $login = $this->argument('demo') == true ? 'demo' : $this->ask('Root login', false);

        if (empty($login)) {
            $login = $email;
        }

        $checkExistsLogin = \App\Models\User::where('login', $login)->first();

        if (!empty($checkExistsLogin)) {
            $this->comment('Root with this login already exists');
            return;
        }

        $askPassword = $this->argument('demo') == true ? 'demo' : $this->ask('Root password [keep empty to generate automatically]', false);

        if (empty($askPassword)) {
            $this->comment('Password will be generated automatically.');
        }

        if ($this->argument('demo') != true) {
            $askAllFine = $this->ask('Is this data correct? Email: ' . $email . ', login: ' . $login . ', password: ' . $askPassword . ' [yes|no]');

            if ('yes' != $askAllFine) {
                $this->info('Okay, trying again.');
                $this->call('make:root');
                return;
            }
        }

        $password = empty($askPassword) ? str_random(12) : $askPassword;

        $exist = true;

        while ($exist) {
          $my_id = mt_rand();
          $exist = \App\Models\User::where('my_id', $myId)->first();
        }

        $user = \App\Models\User::create([
            'name'     => $name,
            'email'    => $email,
            'login'    => $login,
            'password' => bcrypt($password),
            'my_id'    => $my_id,
        ]);
        $user->assignRole('root');
        $user->save();

        $this->info('registered root:');
        $this->comment('name: ' . $name);
        $this->comment('email: ' . $email);
        $this->comment('login: ' . $login);
        $this->comment('password: ' . $password);
    }
}
