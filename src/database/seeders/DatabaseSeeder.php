<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

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
            UserSeeder::class,
            AreaSeeder::class,
            GenreSeeder::class,
            ShopSeeder::class,
        ]);
        User::factory()->count(10)->create();
        User::factory()->count(10)->state(['role' => 2])->create();

        $this->call([
            ReservationSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
