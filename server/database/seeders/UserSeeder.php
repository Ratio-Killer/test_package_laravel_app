<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use TestVendor\UsersList\Models\User;
use TestVendor\UsersList\Models\Phone;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $i) {
            $user = User::create([
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
            ]);

            foreach (range(1, rand(1, 3)) as $_) {
                $user->phones()->create([
                    'number' => $faker->unique()->e164PhoneNumber,
                ]);
            }
        }
    }
}
