<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lesture;
use Faker\Factory as Faker;

class LestureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 100000; $i++) {
            Lesture::create([
                'lecture_name' => $faker->unique()->sentence(3),
            ]);
        }
    }
}
