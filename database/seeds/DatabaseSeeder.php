<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
          UsersSeeder::class,
          TiersSeeder::class,
          ApartmentsSeeder::class,
          FeaturesSeeder::class,
          MessagesSeeder::class,
          // PaymentsSeeder::class 
          // ApartmentsFeaturesSeeder::class
        ]);

    }
}
