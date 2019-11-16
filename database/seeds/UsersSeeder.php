<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Apartment;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 20)->create();
        $userData = [
          'firstName'=>'TestName',
          'lastName' => 'TestLastName',
          'birthdate' => '1990/01/04',
          'password' => bcrypt('password'),
          'email' => 'email@prova.it'
        ];

        User::create($userData);

        // $apt = Apartment::inRandomOrder()->first();
        //
        // $apt['user_id'] = '21';

    }
}
